<?php

wp_enqueue_style( 'lightbox', plugins_url('lightbox/css/lightbox.css' , dirname(__FILE__) ) );
wp_enqueue_script( 'lightbox', plugins_url('lightbox/js/lightbox.js' , dirname(__FILE__) ), array( 'jquery' ) );

?>
