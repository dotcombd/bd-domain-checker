<?php
// form-layout.php

?>
<!-- ডিজাইন শুরু -->
<style>
/* CSS কোড এখানে দিন (আগের উত্তর থেকে কপি করুন) */
</style>

<div id="bd-domain-checker">
  <h2>ডোমেইন নাম অনুসন্ধান করুন</h2>
  <div class="bd-form">
    <input type="text" id="bd-domain-input" placeholder="example">
    <select id="bd-domain-ext">
      <option value=".com.bd">.com.bd</option>
      <option value=".edu.bd">.edu.bd</option>
      <option value=".gov.bd">.gov.bd</option>
      <option value=".net.bd">.net.bd</option>
      <option value=".org.bd">.org.bd</option>
      <option value=".ac.bd">.ac.bd</option>
      <option value=".mail.bd">.mail.bd</option>
      <option value=".info.bd">.info.bd</option>
      <option value=".বাংলা">.বাংলা</option>
    </select>
    <button id="bd-domain-submit">অনুসন্ধান করুন</button>
  </div>
  <div id="bd-domain-result"></div>
</div>
