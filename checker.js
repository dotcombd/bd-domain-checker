jQuery(document).ready(function($){
    $('#bd-domain-submit').on('click', function(){
        let domainName = $('#bd-domain-input').val().trim();
        let ext = $('#bd-domain-ext').val();

        if(domainName === ''){
            $('#bd-domain-result').html('❌ Please enter a domain name');
            return;
        }

        // বাংলা এক্সটেনশন থাকলে স্পেস সাপোর্ট
        let fullDomain = domainName + ext;

        $('#bd-domain-result').html('⏳ Checking...');

        $.post(bdCheckerAjax.ajax_url, {
            action: 'bd_domain_checker',
            domain: fullDomain,
            security: bdCheckerAjax.nonce
        }, function(response){
            if(response.success){
                $('#bd-domain-result').html(response.data.message);
            } else {
                $('#bd-domain-result').html('⚠️ Something went wrong!');
            }
        });
    });
});
