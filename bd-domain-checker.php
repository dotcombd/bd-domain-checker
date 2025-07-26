<?php
/**
 * Plugin Name: BD Domain Checker
 * Description: .bd ডোমেইন অ্যাভেইলেবিলিটি চেক করার জন্য WordPress Plugin (আগের মতো লাইটওয়েট)
 * Version: 1.0
 * Author: DOT.COM.BD
 */

if (!defined('ABSPATH')) exit; 

// ✅ CSS + JS লোড
add_action('wp_enqueue_scripts', function(){
    wp_enqueue_style('bd-checker-css', plugin_dir_url(__FILE__).'style.css');
    wp_enqueue_script('bd-checker-js', plugin_dir_url(__FILE__).'checker.js', array('jquery'), false, true);
    wp_localize_script('bd-checker-js', 'bdChecker', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
});

// ✅ Shortcode -> আগের মতো UI
add_shortcode('bd_domain_checker', function(){
ob_start(); ?>
<div class="bd-domain-checker-wrapper">
  <form id="bdDomainCheckerForm">
    <div class="domain-input-group">
      <input type="text" id="domainInput" placeholder="Enter domain name">
      <select id="domainExtension">
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
      <button type="submit">Check Domain</button>
    </div>
  </form>
  <div id="bdResultContainer"></div>
</div>
<?php return ob_get_clean(); 
});

// ✅ AJAX Handler (আগের মতো সহজ)
add_action('wp_ajax_bd_checker', 'bd_checker_handler');
add_action('wp_ajax_nopriv_bd_checker', 'bd_checker_handler');

function bd_checker_handler() {
    $domain = sanitize_text_field($_POST['domain']);
    // ✅ এখানে আসল ডোমেইন চেক API লাগাবেন
    $available = rand(0,1); // ডেমো

    if($available){
        echo '<div class="domain-success-msg">🎉 Congratulations! ✅ '.$domain.' is Available for Registration.</div>
              <div class="buy-now-box">
                <span class="price-line">💰 Price: ৳800/year</span>
                <a href="#" class="buy-btn">Buy Now</a>
              </div>';
    } else {
        echo '<div class="domain-error-msg">❌ Sorry! '.$domain.' is already taken.</div>';
    }
    wp_die();
}
