<?php
if (isset($_POST['domain'])) {
    $domain = trim($_POST['domain']);
    $domain = htmlspecialchars($domain);

    $available = checkDomainAvailability($domain);

    if ($available) {
        echo '
        <div class="domain-success-msg">
          🎉 Congratulations! ✅ '.$domain.' is Available for Registration.
        </div>
        <div class="buy-now-box">
          <span class="price-line">💰 Price: ৳800/year</span>
          <a href="#" class="buy-btn">Buy Now</a>
        </div>';
    } else {
        echo '
        <div class="domain-error-msg">
          ❌ Sorry! '.$domain.' is already taken.
        </div>';
    }
    exit;
}

function checkDomainAvailability($domain) {
    return (rand(0, 1) === 1); // ডেমো
}
?>
