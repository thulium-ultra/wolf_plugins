<?php if (Dispatcher::getAction() != 'view'): ?>


<h1><?php echo __('Twitter OAuth Plugin'); ?></h1>
<br>
<p>This plugin displays a certain number of tweets from a particular user.</p>
<p>To do this, just add the code <span style="font-family:monospace">&lt;?php printTweets("username",count); ?&gt;</span>, where <b>username</b> is the Twitter handle and <b>count</b> is the number of tweets you want to display!</p>

<?php endif; ?>