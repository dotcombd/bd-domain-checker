<?php
/**
 * Plugin Name: BD Domain Checker
 * Description: Beautiful AJAX-based .bd domain availability checker with live result.
 * Version: 2.1
 * Author: DOT.COM.BD
 * Author URI: https://github.com/dotcombd
 * License: GPL2
 */

if (!defined('ABSPATH')) exit;

// ✅ CSS & JS লোড করা
function bd_domain_checker_assets() {
    wp_enqueue_style('bd-domain-checker-css', plugin_dir_url(__FILE__) . 'style.css');
    wp_enqueue_script('bd-domain-checker-js', plugin_dir_url(__FILE__) . 'checker.js', array('jquery'), '1.0', true);
    wp_localize_script('bd-domain-checker-js', 'bdCheckerAjax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('bd_checker_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'bd_domain_checker_assets');

// ✅ AJAX হ্যান্ডলার
function bd_domain_checker_ajax() {
    check_ajax_referer('bd_checker_nonce', 'security');

    if (!isset($_POST['domain'])) {
        wp_send_json_error('No domain provided');
    }

    // ইউজার যা লিখেছে তা 그대로 রাখছি
    $domain = sanitize_text_field($_POST['domain']);
    $user_input = $domain; 

    // .bd ডোমেইন এক্সটেনশন লিস্ট
    $allowed_extensions = [
        'com.bd','net.bd','org.bd','edu.bd','co.bd','mil.bd',
        'gov.bd','ac.bd','info.bd','tv.bd','sw.bd'
    ];
    
    // ভ্যালিড কিনা চেক
    $valid = false;
    foreach ($allowed_extensions as $ext) {
        if (str_ends_with(strtolower($domain), $ext)) {
            $valid = true;
            break;
        }
    }

    if (!$valid) {
        wp_send_json_success([
            'status' => 'invalid',
            'message' => '❌ Only .bd domains are allowed!'
        ]);
    }

    // DNS চেকের জন্য ডোমেইন পরিষ্কার করা
    $clean_domain = preg_replace('/^https?:\/\//', '', $domain);
    $clean_domain = preg_replace('/^www\./', '', $clean_domain);

    // DNS রেকর্ড চেক
    $records = dns_get_record($clean_domain, DNS_ANY);

    if ($records === false || count($records) === 0) {
        wp_send_json_success([
            'status' => 'available',
            'message' => "✅ Domain <strong>{$user_input}</strong> is <span style='color:green;'>AVAILABLE</span>"
        ]);
    } else {
        wp_send_json_success([
            'status' => 'taken',
            'message' => "❌ Domain <strong>{$user_input}</strong> is <span style='color:red;'>NOT available</span>"
        ]);
    }
}
add_action('wp_ajax_bd_domain_checker', 'bd_domain_checker_ajax');
add_action('wp_ajax_nopriv_bd_domain_checker', 'bd_domain_checker_ajax');

// ✅ Shortcode দিয়ে UI রেন্ডার করা
function bd_domain_checker_shortcode() {
    ob_start(); ?>
    <div class="bd-domain-checker-box">
        <h2>🔍 Check .BD Domain Availability</h2>
        <div class="bd-form-group">
            <input type="text" id="bd-domain-input" placeholder="Enter domain e.g. example.com.bd">
            <button id="bd-domain-submit">Check Now</button>
        </div>
        <div id="bd-domain-result" class="bd-result-box"></div>
    </div>
    <?php return ob_get_clean();
}
add_shortcode('bd_domain_checker', 'bd_domain_checker_shortcode');
