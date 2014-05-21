<?php

//wp_enqueue_style( 'lightbox', plugins_url('sly/css/lightbox.css' , dirname(__FILE__) ) );
wp_enqueue_style( 'horizontal', plugins_url('sly/css/horizontal.css' , dirname(__FILE__) ) );

//wp_enqueue_script( 'modernizr', plugins_url('sly/js/vendor/modernizr.js' , dirname(__FILE__) ), array('jquery'));
wp_enqueue_script( 'plugin', plugins_url('sly/js/vendor/plugin.js' , dirname(__FILE__) ), array('jquery'),'1.0', true);
wp_enqueue_script( 'sly', plugins_url('sly/js/sly.min.js' , dirname(__FILE__) ), array('jquery'),'1.0', true);
wp_enqueue_script( 'horizontal', plugins_url('sly/js/horizontal.js' , dirname(__FILE__) ), array('jquery'),'1.0', true);
//wp_enqueue_script( 'lightbox', plugins_url('sly/js/lightbox.js' , dirname(__FILE__) ), array( 'jquery' ),'1.0', true);


wp_enqueue_style( 'fancybox',  plugins_url('fancybox/source/jquery.fancybox.css' , dirname(__FILE__) ) );
wp_enqueue_style( 'fancybox-buttons',  plugins_url('fancybox/source/helpers/jquery.fancybox-buttons.css' , dirname(__FILE__) ) );
wp_enqueue_style( 'fancybox-thumbs',  plugins_url('fancybox/source/helpers/jquery.fancybox-thumbs.css' , dirname(__FILE__) ) );

wp_enqueue_script( 'mousewheel', plugins_url('fancybox/lib/jquery.mousewheel-3.0.6.pack.js' , dirname(__FILE__) ), array( 'jquery' ) );

wp_enqueue_script( 'fancybox', plugins_url('fancybox/source/jquery.fancybox.js' , dirname(__FILE__) ), array( 'jquery','mousewheel' ) );
wp_enqueue_script( 'fancybox-run', plugins_url('fancybox/source/jquery.fancybox-run.js' , dirname(__FILE__) ), array( 'fancybox' ) );
wp_enqueue_script( 'fancybox-buttons', plugins_url('fancybox/source/helpers/jquery.fancybox-buttons.js' , dirname(__FILE__) ), array( 'fancybox' ) );
wp_enqueue_script( 'fancybox-thumbs',  plugins_url('fancybox/source/helpers/jquery.fancybox-thumbs.js' , dirname(__FILE__) ), array( 'fancybox' ) );
wp_enqueue_script( 'fancybox-media',   plugins_url('fancybox/source/helpers/jquery.fancybox-media.js' , dirname(__FILE__) ), array( 'fancybox' ) );


?>
