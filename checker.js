document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("domainCheckerForm");
  const input = document.getElementById("domainInput");
  const extension = document.getElementById("domainExtension");
  const result = document.getElementById("resultContainer");

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const namePart = input.value.trim();
    const extPart = extension.value;
    const domain = namePart + extPart; // ইউজার যা লিখেছে + সিলেক্টেড এক্সটেনশন

    if (!namePart) {
      result.innerHTML = '<div class="domain-error-msg">⚠️ Please enter a domain name!</div>';
      return;
    }

    fetch("bd-domain-checker.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "domain=" + encodeURIComponent(domain)
    })
    .then(response => response.text())
    .then(data => {
      result.innerHTML = data;
    })
    .catch(err => {
      result.innerHTML = '<div class="domain-error-msg">❌ Something went wrong. Try again!</div>';
      console.error(err);
    });
  });
});
