<?php
/*
Plugin Name: TTT Gallery
Plugin URI: http://www.33themes.com
Description: Simple and quickly gallery system
Version: 0.1
Author: 33 Themes GmbH
Author URI: http://www.33themes.com
*/





define('TTTINC_GALLERY', dirname(__FILE__) );
define('TTTVERSION_GALLERY', 0.1 );


function ttt_autoload_gallery( $class ) {
	if ( 0 !== strpos( $class, 'TTTGallery_' ) )
		return;
	
	$file = TTTINC_GALLERY . '/class/' . $class . '.php';
	if (is_file($file))
		require_once $file;
		return true;
	
	throw new Exception("Unable to load $class at ".$file);
}

if ( function_exists( 'spl_autoload_register' ) ) {
	spl_autoload_register( 'ttt_autoload_gallery' );
} else {
	require_once TTTINC_GALLERy . '/class/TTTGallery_Common.php';
}

function tttgallery_init () {
	$s = load_plugin_textdomain( 'tttgallery', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	if ( !is_admin() ) {
		global $TTTGallery_Front;
		$TTTGallery_Front = new TTTGallery_Front();
		$TTTGallery_Front->init();
	}
	else {
		global $TTTGallery_Front;
		$TTTGallery_Admin = new TTTGallery_Admin();
		$TTTGallery_Admin->init();
	}

}

add_action('init', 'tttgallery_init');

//register_deactivation_hook( __FILE__ ,'tttgallery_uninstall' );
register_uninstall_hook( __FILE__ , 'tttgallery_uninstall' );

function tttgallery_uninstall() {
	require_once TTTINC_GALLERY . '/class/TTTGallery_Common.php';
	require_once TTTINC_GALLERY . '/class/TTTGallery_Admin.php';

	$TTTGallery_Admin = new TTTGallery_Admin();
	$TTTGallery_Admin->uninstall();
}


