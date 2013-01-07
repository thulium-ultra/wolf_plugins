<?php

Plugin::setInfos(array(
    'id'          => 'twitter_oauth',
    'title'       => 'Twitter OAuth', 
    'description' => 'Allows you to display Twitter statuses where you want.', 
    'version'     => '0.0.1', 
    'website'     => 'http://www.wolfcms.org/',
	'author'	  => 'thulium',)
);

Plugin::addController('twitter_oauth', 'Twitter OAuth','administrator'); 


/**
 * Main function to determine whether or not to print the
 * stored tweets or get new tweets first and then print
 * them.  This is because the API only allows 150 requests
 * per hour, so we'll limit fetching new tweet to 1/min
 * through our own check
 *
 * @package Plugins
 * @subpackage twitter_oauth
 * @author thulium
 * @todo use the poor man's cron instead of this
 * @todo if not using the poor man's cron, make the count a variable
 * @license http://www.gnu.org/licenses/gpl.html GPLv3 license
 */
function printTweets($username,$count) {
	$last_time = Plugin::getSetting("last_update","twitter_oauth");
	$curr_time = time();
	if($curr_time - $last_time > 60) { /* Poor man's cron alternative */
		getTweets($username,$count);
	}
	displayTweets();
}


/** 
 * Gets a certain number of tweets for any username provided
 *
 * This function is called by specifing the username and
 * the number of tweets that you choose to display.  The
 * function returns not just the tweets but the  format
 * of the tweets, which can be modified by modifying this
 * function.  The code for this function and the files in
 * the "inc" directory are lifted entirely from Matt 
 * Harris' implementation of OAuth authentication for 
 * Twitter with PHP, found at 
 * https://github.com/themattharris/tmhOAuth
 *
 * @package Plugins
 * @subpackage twitter_oauth
 * @author thulium
 * @license http://www.gnu.org/licenses/gpl.html GPLv3 license
 */
//function printTweets($username,$count) {
function getTweets($username,$count) {
	date_default_timezone_set('UTC');

	require 'inc/tmhOAuth.php';
	require 'inc/tmhUtilities.php';
	
	$ckey = Plugin::getSetting("consumer_key", "twitter_oauth");
	$csec = Plugin::getSetting("consumer_secret", "twitter_oauth");
	$utok = Plugin::getSetting("user_token", "twitter_oauth");
	$usec = Plugin::getSetting("user_secret", "twitter_oauth");
	$tmhOAuth = new tmhOAuth(array(
	  'consumer_key'    => $ckey,
	  'consumer_secret' => $csec,
	  'user_token'      => $utok,
	  'user_secret'     => $usec,
	));

	$code = $tmhOAuth->request('GET', $tmhOAuth->url('1/statuses/user_timeline'), array(
	  'include_entities' => '1',
	  'include_rts'      => '1',
	  'screen_name'      => $username,
	  'count'            => $count,
	));

	if ($code == 200) {
	  $tweets_results = '';
	  $timeline = json_decode($tmhOAuth->response['response'], true);
	  foreach ($timeline as $tweet) :
		$entified_tweet = tmhUtilities::entify_with_options($tweet);
		$is_retweet = isset($tweet['retweeted_status']);

		$diff = time() - strtotime($tweet['created_at']);
		if ($diff < 60*60)
		  $created_at = floor($diff/60) . ' minutes ago';
		elseif ($diff < 60*60*24)
		  $created_at = floor($diff/(60*60)) . ' hours ago';
		else
		  $created_at = date('d M', strtotime($tweet['created_at']));

		$permalink  = str_replace(
		  array(
			'%screen_name%',
			'%id%',
			'%created_at%'
		  ),
		  array(
			$tweet['user']['screen_name'],
			$tweet['id_str'],
			$created_at,
		  ),
		  '<a href="https://twitter.com/%screen_name%/%id%">%created_at%</a>'
		);
	
		/*echo "<div id=\"individualtweet\" style=\"margin-bottom: 1em\">";
		echo "<span>$entified_tweet</span><br>";
		echo "<small>$permalink";
		if ($is_retweet) : echo "is retweet"; endif; 
		echo "via ".$tweet['source']."</small>";
		echo "</div>"; */
		$tweets_results .= "<div id=\"individualtweet\" style=\"margin-bottom: 1em\">";
		$tweets_results .= "<span>$entified_tweet</span><br>";
		$tweets_results .= "<small>$permalink";
		if ($is_retweet) : $tweets_results.= "is retweet"; endif; 
		$tweets_results .= "via ".$tweet['source']."</small>";
		$tweets_results .= "</div>";
		
		$thetime = time();
		
		$settings = array(
			'last_tweets'    => $tweets_results,
			'last_update'    => $thetime,
		);
		Plugin::setAllSettings($settings, 'twitter_oauth');
		
		/* Add the latest tweets to a separate table */
		$conn = Record::getConnection();
		$conn->exec("truncate twitter_oauth_contents");
		$conn->exec("insert into twitter_oauth_contents (latest_tweets) values ('".addslashes($tweets_results)."');");
	  endforeach;
	} else {
	  //tmhUtilities::pr($tmhOAuth->response);
	}
}

/** 
 * Prints the tweets stored by the getTweets function
 *
 * @package Plugins
 * @subpackage twitter_oauth
 * @author thulium
 * @license http://www.gnu.org/licenses/gpl.html GPLv3 license
 */
function displayTweets() {
	date_default_timezone_set('UTC');
	$conn = Record::getConnection();
	$sql = "select latest_tweets from twitter_oauth_contents limit 1;";
	$stmt = Record::query($sql);
	$results = $stmt->fetchObject();
	foreach($results as $element) {
	    $tweets = $element;
	}
	echo $tweets;
}