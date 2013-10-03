<?php

/*
Plugin Name: YADWS
Plugin URI: https://github.com/AlexZabavsky/yadws
Version: 0.1
Author: Alex Zabavsky
Description: Yet Another Dynamic Wordpress Slider (YADWS). This is just another slider built to fulfill specific requirements. More details are coming soon...
License: GPL2+
*/

    include_once dirname( __FILE__ ).'/yadws_install.php';
    
    include_once dirname( __FILE__ ).'/yadws_admin.php';

	require_once dirname( __FILE__ ).'/yadws_frontend.php';


?>
