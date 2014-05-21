<?php

wp_enqueue_style( 'responsiveslides_css', plugins_url('responsiveslides/css/responsiveslides.css' , dirname(__FILE__) ) );

wp_enqueue_script( 'responsiveslides_js', plugins_url('responsiveslides/js/responsiveslides.min.js' , dirname(__FILE__) ), array('jquery'),'1.0', true);

wp_enqueue_script( 'responsiveslides_hook', plugins_url('responsiveslides/js/responsiveslides.hook.js' , dirname(__FILE__) ), array('jquery'),'1.0', true);

?>
