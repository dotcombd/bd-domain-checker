document.addEventListener("DOMContentLoaded", function(){
  const form = document.getElementById("bdDomainCheckerForm");
  if(!form) return;

  const input = document.getElementById("domainInput");
  const extension = document.getElementById("domainExtension");
  const result = document.getElementById("bdResultContainer");

  form.addEventListener("submit", function(e){
    e.preventDefault();

    const namePart = input.value.trim();
    const extPart = extension.value;
    const domain = namePart + extPart;

    if(!namePart){
      result.innerHTML = '<div class="domain-error-msg">⚠️ Please enter a domain name!</div>';
      return;
    }

    result.innerHTML = "⏳ Checking...";
    fetch(bdChecker.ajax_url, {
      method: "POST",
      headers: {"Content-Type": "application/x-www-form-urlencoded"},
      body: "action=bd_checker&domain="+encodeURIComponent(domain)
    })
    .then(r=>r.text())
    .then(data=>{
      result.innerHTML = data;
    })
    .catch(err=>{
      result.innerHTML = '<div class="domain-error-msg">❌ AJAX Failed. Try again!</div>';
      console.error(err);
    });
  });
});
