jQuery(document).ready(function($) {
    $('#bddc-check-btn').click(function() {
        var domain = $('#bddc-domain').val().trim();
        if (domain === '') {
            $('#bddc-result').html('<span style="color:red;">অনুগ্রহ করে একটি ডোমেইন নাম লিখুন।</span>');
            return;
        }

        $('#bddc-result').text('অনুসন্ধান চলছে...');
        $.post(bddc_ajax_obj.ajax_url, {
            action: 'bddc_check_domain',
            domain: domain
        }, function(response) {
            var data = JSON.parse(response);
            if (data.available) {
                $('#bddc-result').html(`<span style="color:green;">${data.domain} পাওয়া যাচ্ছে ✅</span>`);
            } else {
                $('#bddc-result').html(`<span style="color:red;">${data.domain} পাওয়া যাচ্ছে না ❌</span>`);
            }
        });
    });
});
