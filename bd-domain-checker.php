<?php
/**
 * Plugin Name: BD Domain Checker
 * Description: Beautiful AJAX-based .bd and .বাংলা domain availability checker with live result.
 * Version: 3.0
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

    // সব ভ্যালিড এক্সটেনশন
    $allowed_extensions = [
        'com.bd','edu.bd','gov.bd','net.bd','org.bd',
        'ac.bd','mil.bd','info.bd','বাংলা'
    ];

    // ভ্যালিড কিনা চেক
    $valid = false;
    foreach ($allowed_extensions as $ext) {
        if (str_ends_with(mb_strtolower($domain), $ext)) {
            $valid = true;
            break;
        }
    }

    if (!$valid) {
        wp_send_json_success([
            'status' => 'invalid',
            'message' => '❌ Only valid .bd or .বাংলা domains are allowed!'
        ]);
    }

    // DNS চেকের জন্য ডোমেইন পরিষ্কার করা
    $clean_domain = preg_replace('/^https?:\/\//', '', $domain);
    $clean_domain = preg_replace('/^www\./', '', $clean_domain);

    // ✅ DNS রেকর্ড চেক
    $has_records = false;

    // প্রথমে dns_get_record() দিয়ে চেষ্টা
    $records = @dns_get_record($clean_domain, DNS_A + DNS_AAAA + DNS_CNAME);
    if ($records && count($records) > 0) {
        $has_records = true;
    }

    // fallback: checkdnsrr() দিয়ে চেষ্টা
    if (!$has_records && function_exists('checkdnsrr')) {
        if (checkdnsrr($clean_domain, "A") || checkdnsrr($clean_domain, "CNAME") || checkdnsrr($clean_domain, "MX")) {
            $has_records = true;
        }
    }

    if ($has_records) {
        wp_send_json_success([
            'status' => 'taken',
            'message' => "❌ Domain <strong>{$user_input}</strong> is <span style='color:red;'>NOT available</span>"
        ]);
    } else {
        wp_send_json_success([
            'status' => 'available',
            'message' => "✅ Domain <strong>{$user_input}</strong> is <span style='color:green;'>AVAILABLE</span>"
        ]);
    }
}
add_action('wp_ajax_bd_domain_checker', 'bd_domain_checker_ajax');
add_action('wp_ajax_nopriv_bd_domain_checker', 'bd_domain_checker_ajax');

// ✅ শর্টকোড UI (স্ক্রিনশটের মতো)
function bd_domain_checker_shortcode() {
    ob_start(); ?>
    
    <div class="bd-domain-checker-wrapper">
        <div class="bd-domain-checker-bg">
            <div class="bd-domain-form">
                <div class="bd-domain-input-group">
                    <input type="text" id="bd-domain-input" placeholder="Find your .bd or .বাংলা domain right">
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
                </div>
                <button id="bd-domain-submit">Search</button>
            </div>
            <div id="bd-domain-result" class="bd-result-box"></div>
            <p class="bd-domain-welcome">
                Welcome to the World of <span class="bangla">.বাংলা</span> & <span class="bd">.bd</span> Domain Service
            </p>
        </div>
    </div>

    <?php return ob_get_clean();
}
add_shortcode('bd_domain_checker', 'bd_domain_checker_shortcode');
