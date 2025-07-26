jQuery(document).ready(function($) {
    $('#bd-domain-submit').click(function(e) {
        e.preventDefault();
        var domain = $('#bd-domain-input').val();

        if(domain.trim() === '') {
            alert('Please enter a domain!');
            return;
        }

        $('#bd-domain-result').html('⏳ Checking...').fadeIn();

        $.ajax({
            url: bdCheckerAjax.ajax_url,
            type: 'POST',
            data: {
                action: 'bd_domain_checker',
                security: bdCheckerAjax.nonce,
                domain: domain
            },
            success: function(response) {
                if(response.success) {
                    $('#bd-domain-result').html(response.data.message).fadeIn();
                } else {
                    $('#bd-domain-result').html('❌ Something went wrong!').fadeIn();
                }
            }
        });
    });
});
