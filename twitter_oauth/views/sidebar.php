<?php if (Dispatcher::getAction() != 'view'): ?>

<p class="button"><a href="<?php echo get_url('plugin/twitter_oauth/'); ?>"><img src="<?php echo PLUGINS_URI;?>/twitter_oauth/images/twitter-bird.png" width=32 height=32 align="middle" alt="page icon" />Home</a></p>
<p class="button"><a href="<?php echo get_url('plugin/twitter_oauth/settings'); ?>"><img src="<?php echo PLUGINS_URI;?>/twitter_oauth/images/settings.png" width=32 height=32 align="middle" alt="page icon" />Settings</a></p>

<?php endif; ?>