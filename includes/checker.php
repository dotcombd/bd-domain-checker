<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Check BD domain availability via whois
 */
function bd_domain_checker_handle_request() {
    check_ajax_referer('bd_domain_checker_nonce', 'security');

    if (empty($_POST['domain'])) {
        wp_send_json_error(['message' => '⚠️ No domain provided.']);
    }

    $input = sanitize_text_field($_POST['domain']);
    $parts = explode('.', $input, 2);
    $name = $parts[0];
    $mainExt = '.' . $parts[1];

    $extensions = [
        '.com.bd', '.edu.bd', '.gov.bd', '.net.bd', '.org.bd',
        '.ac.bd', '.mail.bd', '.info.bd', '.বাংলা'
    ];

    $results = [];

    foreach ($extensions as $ext) {
        $fullDomain = $name . $ext;
        $available = bd_domain_checker_is_available($fullDomain);
        $results[] = [
            'domain' => $fullDomain,
            'available' => $available
        ];
    }

    // প্রধান এক্সটেনশনকে আলাদা করি
    $primary = null;
    $others = [];

    foreach ($results as $res) {
        if ($res['domain'] === $input) {
            $primary = $res;
        } else {
            $others[] = $res;
        }
    }

    wp_send_json_success([
        'message' => '✔️ Domain check completed.',
        'primary' => $primary,
        'others' => $others
    ]);
}

add_action('wp_ajax_bd_domain_checker', 'bd_domain_checker_handle_request');
add_action('wp_ajax_nopriv_bd_domain_checker', 'bd_domain_checker_handle_request');


/**
 * Check BD domain availability using WHOIS
 */
function bd_domain_checker_is_available($domain) {
    $whoisServer = "whois.bd";
    $output = null;

    // Try to connect to whois server
    $connection = @fsockopen("whois.bd", 43, $errno, $errstr, 10);
    if (!$connection) {
        return false; // treat as unavailable if server unreachable
    }

    fwrite($connection, $domain . "\r\n");
    $response = '';
    while (!feof($connection)) {
        $response .= fgets($connection, 128);
    }
    fclose($connection);

    // Domain not found = available
    if (stripos($response, 'not found') !== false) {
        return true;
    }

    return false;
}
