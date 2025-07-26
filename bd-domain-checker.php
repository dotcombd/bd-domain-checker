<?php
/**
 * Plugin Name: BD Domain Availability Checker
 * Description: Check availability of .bd domains like .com.bd, .net.bd, .org.bd, etc.
 * Version: 1.0.0
 * Author: Your Name
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Define plugin paths
define( 'BDDC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'BDDC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include domain checking logic
include_once BDDC_PLUGIN_DIR . 'includes/checker.php';

// Enqueue Scripts & Styles
function bddc_enqueue_assets() {
    wp_enqueue_style( 'bddc-style', BDDC_PLUGIN_URL . 'assets/css/style.css' );

    wp_enqueue_script( 'bddc-script', BDDC_PLUGIN_URL . 'assets/js/script.js', ['jquery'], null, true );
    wp_localize_script( 'bddc-script', 'bdAjax', [
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'bd_domain_checker_nonce' )
    ]);
}
add_action( 'wp_enqueue_scripts', 'bddc_enqueue_assets' );

// Shortcode handler
function bddc_render_form_shortcode() {
    ob_start();
    include BDDC_PLUGIN_DIR . 'templates/form-layout.php';
    return ob_get_clean();
}
add_shortcode( 'bd_domain_checker', 'bddc_render_form_shortcode' );
