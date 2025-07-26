<?php
/**
 * Plugin Name: BD Domain Checker
 * Description: Debug test for AJAX.
 * Version: 0.1
 * Author: DOT.COM.BD
 */

if (!defined('ABSPATH')) exit;

// AJAX Debug Handler
function bd_domain_checker_ajax() {
    wp_send_json_success(['message' => '✅ AJAX is working!']);
}
add_action('wp_ajax_bd_domain_checker', 'bd_domain_checker_ajax');
add_action('wp_ajax_nopriv_bd_domain_checker', 'bd_domain_checker_ajax');

// শর্টকোড (আগের মতোই থাকবে)
function bd_domain_checker_shortcode() {
    ob_start(); ?>
    
    <div class="bd-domain-checker-wrapper">
        <h2 class="bd-domain-title">BD Domain Checker</h2>
        <div class="bd-domain-form">
            <input type="text" id="bd-domain-input" placeholder="Enter domain name">
            <select id="bd-domain-ext">
                <option value=".com.bd">.com.bd</option>
                <option value=".edu.bd">.edu.bd</option>
                <option value=".gov.bd">.gov.bd</option>
                <option value=".net.bd">.net.bd</option>
                <option value=".org.bd">.org.bd</option>
                <option value=".ac.bd">.ac.bd</option>
                <option value=".mil.bd">.mil.bd</option>
                <option value=".info.bd">.info.bd</option>
                <option value=".বাংলা">.বাংলা</option>
            </select>
            <button id="bd-domain-submit">Search</button>
        </div>
        <div id="bd-domain-result"></div>
    </div>

    <?php return ob_get_clean();
}
add_shortcode('bd_domain_checker', 'bd_domain_checker_shortcode');

// CSS + JS লোড
function bd_checker_assets() {
    wp_enqueue_script('bd-checker-js', plugin_dir_url(__FILE__).'checker.js', ['jquery'], '1.0', true);
    wp_localize_script('bd-checker-js', 'bdAjax', [
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('bd_checker_nonce')
    ]);
}
add_action('wp_enqueue_scripts', 'bd_checker_assets');
