<?php

if (!defined('IN_CMS')) { exit(); }
$settings = array(
	'consumer_key'    => '',
	'consumer_secret' => '',
	'user_token'      => '',
	'user_secret'     => '',
	'last_update'     => '',
);

Plugin::setAllSettings($settings, 'twitter_oauth');

/* Add a separate table just for the latest tweets */
$conn = Record::getConnection();
$conn->exec("create table twitter_oauth_contents ( latest_tweets text );");

exit();

?>