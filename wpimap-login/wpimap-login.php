<?php
/*
Plugin Name: WordPress IMAP Login
Description: Allow login to WordPress using an IMAP email server
Version: 1.0
Author: @luismisanchz
Author URI: http://luismi.sanchezarteaga.es/
*/

//Admin menus
include_once ('inc/admin-menu.php');
//Testing IMAP connection functions
include_once ('inc/test-imap.php');
//Auth against IMAP functions
include_once ('inc/imap-auth.php');

add_filter( 'plugin_action_links', 'wpimap_login_add_action_plugin', 10, 5 );
function wpimap_login_add_action_plugin( $actions, $plugin_file )
{
    static $plugin;

    if (!isset($plugin))
        $plugin = plugin_basename(__FILE__);
    if ($plugin == $plugin_file) {

        $settings = array('settings' => '<a href="options-general.php?page=wpimap_login">' . __('Settings', 'General') . '</a>');
        $site_link = array('support' => '<a href="http://luismi.sanchezarteaga.es/" target="_blank">Support</a>');

        $actions = array_merge($settings, $actions);
        $actions = array_merge($site_link, $actions);

    }

    return $actions;
}