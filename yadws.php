<?php

/*
Plugin Name: YADWS
Plugin URI: https://github.com/AlexZabavsky/yadws
Version: 0.1
Author: Alex Zabavsky
Description: Yet Another Dynamic Wordpress Slider (YADWS). This is just another slider built to fulfill specific requirements. More details are coming soon...
License: GPL2+
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

require_once( plugin_dir_path( __FILE__ ) . 'class-yadws.php' );

// TODO: Register hooks that are fired when the plugin is activated or deactivated.
// When the plugin is deleted, the uninstall.php file is loaded.
//register_activation_hook( __FILE__, array( 'YADWS', 'activate' ) );
//register_deactivation_hook( __FILE__, array( 'YADWS', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'YADWS', 'get_instance' ) );

?>
