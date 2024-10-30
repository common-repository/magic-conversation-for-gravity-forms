<?php
/*
 * Plugin Name: Magic Conversation For Gravity Forms
 * Version: 3.0.94
 * Description: Turn your Gravity Forms into Conversation Form
 * Author: Magic Conversation
 * Author URI: https://magicconversation.net
 * Requires at least: 3.9
 * Tested up to: 6.5
 *
 * @package WordPress
 * @author Flannian
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
define('MCFGFP_VER', '3.0.94.489');
$mcfgfBaseDir = dirname(__FILE__);

if(!class_exists('ComposerAutoloaderInit65896642f0922a376b328aea5753bb45')) {
	require_once ($mcfgfBaseDir.'/lib/vendor/autoload.php');
}
if ( ! defined( 'MCFGFP_CURRENT_PAGE' ) ) {
	/**
	 * Defines the current page.
	 *
	 * @since   Unknown
	 *
	 * @used-by GFForms::init()
	 * @used-by GFCommon::ensure_wp_version()
	 *
	 * @var string RG_CURRENT_PAGE The current page.
	 */
	define( 'MCFGFP_CURRENT_PAGE', basename( $_SERVER['PHP_SELF'] ) );
}
require_once ($mcfgfBaseDir.'/functions.php');
require_once ($mcfgfBaseDir.'/install.php');
//Init Settings
require_once ($mcfgfBaseDir .'/settings.php');
//Init Conversation Questions
// require_once ($mcfgfBaseDir.'/conversation-questions.php');
require_once ($mcfgfBaseDir.'/css-generator.php');
// require_once ($mcfgfBaseDir.'/woo-product-picker-generator.php');
require_once ($mcfgfBaseDir.'/help.php');
require_once ($mcfgfBaseDir.'/main.php');
require_once ($mcfgfBaseDir.'/yakker-gravityforms/gfyakkeraddon.php');

// add_filter( 'gform_field_value_author_email', 'populate_post_author_email' );
// function populate_post_author_email( $value ) {
//     global $post;
 
//     //$author_email = get_the_author_meta( 'email', $post->post_author );
 
//     return 'flannian@qq.com'; //$author_email;
// }