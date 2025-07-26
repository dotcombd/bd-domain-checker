jQuery(document).ready(function($) {

    $('#bd-domain-submit').on('click', function() {
        let domainName = $('#bd-domain-input').val().trim();
        let ext = $('#bd-domain-ext').val();
        let fullDomain = domainName + ext;

        if (domainName === '') {
            $('#bd-domain-result').html('❌ অনুগ্রহ করে একটি ডোমেইন নাম লিখুন');
            $('#bd-domain-others').html('');
            return;
        }

        $('#bd-domain-result').html('⏳ অনুসন্ধান চলছে...');
        $('#bd-domain-others').html('');

        $.post(bdAjax.ajaxurl, {
            action: 'bd_domain_checker',
            domain: fullDomain,
            security: bdAjax.nonce
        }, function(response) {
            if (response.success) {
                const data = response.data;
                const main = data.primary;
                const others = data.others;

                if (main && main.available) {
                    $('#bd-domain-result').html(`✅ <strong>${main.domain}</strong> অ্যাভেলেবল ✅`);
                } else {
                    $('#bd-domain-result').html(`❌ <strong>${main.domain}</strong> ইতোমধ্যে নিবন্ধিত ❌`);
                }

                if (others && others.length > 0) {
                    let html = '<div class="bd-domain-boxes">';
                    others.forEach(item => {
                        let cls = item.available ? 'available' : 'unavailable';
                        let text = item.available ? '✔️ Available' : '❌ Taken';
                        html += `<div class="bd-domain-box ${cls}">${item.domain}<br><small>${text}</small></div>`;
                    });
                    html += '</div>';

                    $('#bd-domain-others').html(html);
                }
            } else {
                $('#bd-domain-result').html('⚠️ সার্ভার থেকে ফলাফল পাওয়া যায়নি');
            }
        }).fail(function(xhr, status, error) {
            console.error("AJAX Error:", status, error);
            $('#bd-domain-result').html('⚠️ নেটওয়ার্ক সমস্যা হয়েছে, দয়া করে আবার চেষ্টা করুন');
        });
    });

});
