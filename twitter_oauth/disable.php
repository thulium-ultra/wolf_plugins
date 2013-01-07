<?php

if (!defined('IN_CMS')) { exit(); }
Plugin::deleteAllSettings("twitter_oauth");

/* Delete special table */
$conn = Record::getConnection();
$conn->exec("drop table twitter_oauth_contents;");
exit();

?>