<?php
/**
 * @package Adverb
 * @version 1.0
 */
 
 
/*
Plugin Name: Advertisement Utility
Plugin URI: https://www.facebook.com/ajaysharma1983
Description: This is a Advertisement Utility
Author: Ajay Sharma
Version: 1.0
Author URI: https://www.facebook.com/ajaysharma1983
Usage: Enter Attributes as follows

adid                			[Unique Number for Adverb]
asin                			[Amazon Identification number]
ribbonText          			[Ribbon Text]
buybtnText						[Buy Button Text]
publishPrice        			[Price of product when the article was published]
picUrl              			[URL of the picture]
affliateUrl         			[Affliate URL]
productName         			[Product Name] 
productTitle        			[Product Title]
shortDesc           			[Short Description]
layout							[values accepted: "imageLeftLayout", "imageBuyBtnDisclaimerLeftLayout", "imageDisclaimerLeftLayout"]
banner_width_percent    		[value range  25-100] 
banner_float="right" 			[value accepter: "right", "left"]
banner_left_width_percent 		[value range  0-100] 
banner_right_width_percent 		[value range  0-100] 

[adverb adid="1" asin="B00MQP6LV4" buybtntext="Buy Here" ribbontext='Great Demand' publishprice="10000" picurl="https://thewirecutter.com/wp-content/uploads/2016/05/01w-cheap-projector-benq-th670-630.jpg" affliateurl="http://www.flipkart.com/redmi-3s-prime-dark-grey-32-gb/p/itmeh6b3hfzupvkj?pid=MOBEKWZYZHUXJFR6&affid=kanujcand" productname="Product Name 1" producttitle="Great Device from ABC Corp." shortdesc="This is a unique device"]


*/


/**** GLOBAL VARIABLES **/
$myrp_prefix = 'adverb_';
$myrp_plugin_name = 'Advertisement Utility';

function adverb_styles_and_scripts()
{	
	$my_js_ver  = date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . 'js/store.js' ));
	$my_css_ver = date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . '/styles/mktslate.css' ));
     
	// load our javascript file
	//wp_enqueue_script( 'store-util',  plugins_url('/js/store.js', __FILE__) , array( 'jquery' ), false, true );
	wp_enqueue_script( 'store-util',  plugins_url('/js/store.js', __FILE__) , array( 'jquery' ), $my_js_ver, true );
	
	// loading our cascading style sheet
	//wp_enqueue_style( 'market-style' , plugins_url( '/styles/mktslate.css', __FILE__ ));
	wp_register_style( 'market-style',    plugins_url( '/styles/mktslate.css',    __FILE__ ), false,   $my_css_ver );
    wp_enqueue_style ( 'market-style' );
	
	// make the ajaxurl var available to the above script
	wp_localize_script( 'store-util', 'the_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}


/**** INCLUDES **/
include('includes/adverb-placeholder.php'); // including the display function file.
include('includes/amzhelp.php'); // including the amzhelp function file.

add_action( 'wp_enqueue_scripts', 'adverb_styles_and_scripts' );

?>