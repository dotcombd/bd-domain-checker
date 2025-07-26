jQuery(document).ready(function($){
    $('#bd-domain-submit').on('click', function(){

        let domainName = $('#bd-domain-input').val().trim(); // শুধু নাম
        let ext = $('#bd-domain-ext').val(); // এক্সটেনশন

        if(domainName === ''){
            $('#bd-domain-result').html('❌ Please enter a domain name');
            return;
        }

        let fullDomain = domainName + ext;

        console.log("🔍 Searching:", fullDomain); // Debug log
        $('#bd-domain-result').html('⏳ Checking...');

        $.ajax({
            url: bdCheckerAjax.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'bd_domain_checker',
                domain: fullDomain,
                security: bdCheckerAjax.nonce
            },
            success: function(response){
                console.log("✅ AJAX Response:", response); // Debug log
                if(response.success){
                    $('#bd-domain-result').html(response.data.message);
                } else {
                    $('#bd-domain-result').html('⚠️ Invalid response from server');
                }
            },
            error: function(xhr, status, error){
                console.error("❌ AJAX Error:", status, error); // Debug log
                $('#bd-domain-result').html('⚠️ AJAX request failed. Check console.');
            }
        });

    });
});
