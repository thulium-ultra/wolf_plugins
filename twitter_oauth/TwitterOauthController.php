<?php

class TwitterOauthController extends PluginController {
    function __construct() {
        AuthUser::load();
        if ( ! AuthUser::isLoggedIn()) {
            redirect(get_url('login'));
        }
 
        $this->setLayout('backend');
        $this->assignToLayout('sidebar', new View('../../plugins/twitter_oauth/views/sidebar'));
    }
 
    function index() {
        $this->display('twitter_oauth/views/index');
    }
	
	function settings() {
		$this->display('twitter_oauth/views/settings');
	}
	
	function save() {
		if (get_request_method() == 'POST') {
			return $this->_save();
		}
	}
	
	function _save() {		
		#sanitize the input
		$vals = array("consumer_key","consumer_secret","user_token","user_secret");
		foreach ($vals as &$value) {
			if(preg_match('/[^a-zA-Z0-9-]/i', $_POST[$value])) {
				$string = "Invalid value for ".$value."!";
				Flash::set('error', __($string));
				redirect(get_url('plugin/twitter_oauth/settings'));
			}
		} 
		
		#set the data
		$settings = array(
			'consumer_key'    => $_POST["consumer_key"],
			'consumer_secret' => $_POST["consumer_secret"],
			'user_token'      => $_POST["user_token"],
			'user_secret'     => $_POST["user_secret"],
		);
		Plugin::setAllSettings($settings, 'twitter_oauth');
		
		#Show the success message and redirect
		Flash::set('success', __('Twitter OAuth settings saved!'));
		redirect(get_url('plugin/twitter_oauth/settings'));
	}
}