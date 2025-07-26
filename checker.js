jQuery(document).ready(function($){
    $('#bd-domain-submit').on('click', function(){
        let domainName = $('#bd-domain-input').val().trim(); // শুধু নাম
        let ext = $('#bd-domain-ext').val(); // এক্সটেনশন ড্রপডাউন

        if(domainName === ''){
            $('#bd-domain-result').html('❌ Please enter a domain name');
            return;
        }

        // চূড়ান্ত পূর্ণ ডোমেইন
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
