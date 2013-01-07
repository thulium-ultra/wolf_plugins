<h1><?php echo __('Twitter OAuth Plugin'); ?></h1>
<br>
The Twitter OAuth Plugin requires to have four pieces of information:<p>
<form method="post" action="<?php echo get_url("plugin/twitter_oauth/save"); ?>">
<div style="width: 150px; float: left;">consumer_key:</div>
<div style="float: left;"><input type="text" name="consumer_key" value="<?php echo Plugin::getSetting("consumer_key", "twitter_oauth") ?>" size=35></div>
<div style="width: 150px; clear: left; float: left;">consumer_secret:</div>
<div style="float: left;"><input type="text" name="consumer_secret" value="<?php echo Plugin::getSetting("consumer_secret", "twitter_oauth") ?>" size=60></div>
<div style="width: 150px; clear: left; float: left;">user_token:</div>
<div style="float: left;"><input type="text" name="user_token" value="<?php echo Plugin::getSetting("user_token", "twitter_oauth") ?>" size=60></div>
<div style="width: 150px; clear: left; float: left;">user_secret:</div>
<div style="float: left;"><input type="text" name="user_secret" value="<?php echo Plugin::getSetting("user_secret", "twitter_oauth") ?>" size=60></div>
<p style="clear: both;"><input type="submit" value="Save"></p>
</form>

<h2>How do I add tweets to my page?</h2>
Just add &nbsp;<span style="font-family:monospace;">&lt;?php printTweets("twitterID",number_of_tweets) ?&gt;</span>&nbsp; to your layout where you'd like it!

<h2>How do you get this information?</h2>
<ol style="padding-left: 20px;">
<li>Log into your Twitter account.
<li>Navigate to <a href="https://dev.twitter.com/apps" target="_blank">https://dev.twitter.com/apps</a> and create a new application.
<li>Once you fill in the relevant information, you'll be given a "Consumer key" and "Consumer secret".  Go to the bottom of the application and click "Create my access token".
</ol>

<br>Once you have that information created, add it above and click save!

<h2>How do I change the format of the Tweets?</h2>
<p>Since it is currently not built into the settings (at least at this time), one can edit the <span style="font-family:monospace;">printTweets()</span> function found in the index.php page of the plugin.</p>
