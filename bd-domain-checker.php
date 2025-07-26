<?php
/**
 * Plugin Name: BD Domain Checker
 * Description: Check availability of .bd domains using shortcode.
 * Version: 1.0
 * Author: DOT.COM.BD
 */

defined('ABSPATH') || exit;

define('BDDC_PATH', plugin_dir_path(__FILE__));

function bddc_enqueue_assets() {
    wp_enqueue_style('bddc-style', plugin_dir_url(__FILE__) . 'assets/style.css');
    wp_enqueue_script('bddc-script', plugin_dir_url(__FILE__) . 'assets/script.js', ['jquery'], null, true);

    wp_localize_script('bddc-script', 'bddc_ajax_obj', [
        'ajax_url' => admin_url('admin-ajax.php')
    ]);
}
add_action('wp_enqueue_scripts', 'bddc_enqueue_assets');

// Shortcode
function bddc_domain_form() {
    ob_start(); ?>
    <div class="bddc-checker">
        <label for="bddc-domain">ডোমেইন নাম লিখুন:</label><br>
        <div class="bddc-input-group">
            <input type="text" id="bddc-domain" placeholder="example" />
            <span class="suffix">.bd</span>
        </div>
        <button id="bddc-check-btn">খুঁজুন</button>
        <div id="bddc-result"></div>
    </div>
    <?php return ob_get_clean();
}
add_shortcode('bd_domain_checker', 'bddc_domain_form');

// AJAX Handler
add_action('wp_ajax_bddc_check_domain', 'bddc_check_domain');
add_action('wp_ajax_nopriv_bddc_check_domain', 'bddc_check_domain');

function bddc_check_domain() {
    $domain = sanitize_text_field($_POST['domain']);
    require_once BDDC_PATH . 'includes/checker.php';

    $full_domain = $domain . '.bd';
    $result = checkBDDomain($full_domain);

    echo json_encode(['domain' => $full_domain, 'available' => $result]);
    wp_die();
}
