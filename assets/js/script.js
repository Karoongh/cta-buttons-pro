// assets/js/script.js
document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.querySelector('.cta-floating-toggle');
    const menu = document.querySelector('.cta-floating-menu');
    const floating = document.querySelector('.cta-floating');

    if (toggle && menu && floating) {
        toggle.addEventListener('click', e => {
            e.stopPropagation();
            menu.classList.toggle('active');
        });

        document.addEventListener('click', e => {
            if (!floating.contains(e.target)) {
                menu.classList.remove('active');
            }
        });

        menu.addEventListener('click', e => e.stopPropagation());
    }

    document.querySelectorAll('a[href^="tel:"], a[href^="https://wa.me"], a[href^="https://t.me"], a[href^="mailto:"], a[href*="instagram.com"], a[href*="google.com/maps"], a[href*="maps.app.goo.gl"], .cta-pro-btn, .cta-floating-item, .cta-fixed-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            let channel = 'unknown';
            const href = this.getAttribute('href') || '';

            if (href.includes('wa.me')) channel = 'whatsapp';
            else if (href.includes('t.me')) channel = 'telegram';
            else if (href.startsWith('tel:')) channel = 'phone';
            else if (href.startsWith('mailto:')) channel = 'email';
            else if (href.includes('instagram.com')) channel = 'instagram';
            else if (href.match(/google\.com\/maps|maps\.app\.goo\.gl|waze\.com/)) channel = 'location';

            if (channel !== 'unknown' && typeof ctaProAjax !== 'undefined') {
                fetch(ctaProAjax.ajax_url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `action=cta_pro_record_click&channel=${channel}&nonce=${ctaProAjax.nonce}`
                }).catch(() => {});
            }
        });
    });
});
