<?php

/**
 * Plugin Name: dynamic-lazyloader
 * Plugin URI: https://github.com/seunghaekim/dynamic-lazyloader
 * Description: Dynamic lazyloader
 * Version: 0.0.1
 * Author: seunghaekim
 * Author URI: https://github.com/seunghaekim/
 * License: MIT
 */

class DynamicLazyloader {
    function __construct(){
        add_action('wp_enqueue_scripts', array($this, 'my_adding_scripts'));
        add_action('wp_ajax_nopriv_dynamicImageProcessor', array($this, 'dynamicImageProcessor'));
        add_action('wp_ajax_dynamicImageProcessor', array($this, 'dynamicImageProcessor'));
    }

    function my_adding_scripts(){
        wp_register_script( 'lazyload', plugins_url( '/lazyload.js', __FILE__ ), array( 'jquery' ), '3.1', true );
        wp_enqueue_script( 'lazyload' );
        wp_register_script('my_scripts', plugins_url('/request.imgurl.js', __FILE__), array('jquery'), '', true);
        wp_localize_script('my_scripts', 'my_ajax_data', array( 'url' => admin_url('admin-ajax.php')) );
        wp_enqueue_script('my_scripts');
    }

    function dynamicImageProcessor(){
        include('Aqua-Resizer/aq_resizer.php');
        $response['result'] = false;
        if($response['url'] = aq_resize( $_POST['url'], $_POST['imageWidth'], $_POST['imageHeight'])){
            $response['result'] = true;
        };
        header('Content-Type: Application/json');
        echo json_encode($response);
        wp_die();
    }
}

new DynamicLazyloader();
?>
