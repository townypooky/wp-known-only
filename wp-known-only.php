<?php
/*
Plugin Name: Known Only
Plugin URI: https://twitter.com/townypooky
Description: Accept only the guests who has a secret word in URI.
Version: 1.0
Author: Towny Pooky
Author URI: https://twitter.com/townypooky
License: MIT
*/

/// !HEY! Please setup the following values before you start using ///

/**
 * Key for GET of the secret word.
 */
define('KY_KEY', 'ky');

/**
 * Secret word to permit an user browsing your WP.
 * This string would be the value for GET key.
 *
 * NOTE: you have to hide the string from other people
 * otherwise nothing works for you.
 */
define('KY_PWD', 'thxcme');

/// Thanks, you've done ///


/**
 * Get the full, current URI
 *
 * @return string
 * @todo $_SERVER array access is no longer popular maybe.
 */
function ky_getUrl() {
  $url  = @( $_SERVER["HTTPS"] != 'on' ) ? 'http://' : 'https://';
  $url .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
  return $url;
}

/**
 * Check the accesibility if the current page is not WP login form.
 * Then force to jump to the login page if not.
 */
add_action('init', function(){
	$url = ky_getUrl();
	if( !is_user_logged_in() && strpos($url, '/wp-login.php') === false ) {
		if(session_status() !== PHP_SESSION_ACTIVE) @session_start();
		if(isset($_SESSION[__FILE__]) && $_SESSION[__FILE__]) return;
		if(isset($_GET[KY_KEY]) && $_GET[KY_KEY] === KY_PWD){
			$_SESSION[__FILE__] = true;
			return;
		}
		header('Location: '.wp_login_url( $url ));
		exit(0);
	}
}, 100);


