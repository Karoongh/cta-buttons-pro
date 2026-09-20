// assets/js/admin-script.js
document.addEventListener('DOMContentLoaded', function () {

    // 1. نمایش مقدار اسلایدر شفافیت
    const slider = document.querySelector('[name="opacity"]');
    if (slider) {
        const valueSpan = slider.parentNode.querySelector('.opacity-value') || document.createElement('span');
        if (!slider.parentNode.querySelector('.opacity-value')) {
            valueSpan.className = 'opacity-value';
            valueSpan.style.marginRight = '10px';
            valueSpan.style.fontWeight = 'bold';
            valueSpan.style.color = '#2271b1';
            slider.parentNode.appendChild(valueSpan);
        }
        const updateValue = () => {
            valueSpan.textContent = slider.value + '%';
        };
        updateValue();
        slider.addEventListener('input', updateValue);
    }

    // 2. آپلودر آیکون سفارشی
    document.querySelectorAll('.upload-icon-btn').forEach(button => {
        button.addEventListener('click', function () {
            const container = this.closest('.cta-icon-uploader');
            const img = container.querySelector('img');
            const input = container.querySelector('input[type="hidden"]');
            const removeBtn = container.querySelector('.remove-icon-btn');

            const frame = wp.media({
                title: 'انتخاب آیکون سفارشی',
                button: { text: 'استفاده از این تصویر' },
                multiple: false,
                library: { type: 'image' }
            });

            frame.on('select', function () {
                const attachment = frame.state().get('selection').first().toJSON();
                img.src = attachment.url;
                input.value = attachment.id;
                removeBtn.style.display = 'inline-block';
            });

            frame.open();
        });
    });

    // 3. حذف آیکون سفارشی
    document.querySelectorAll('.remove-icon-btn').forEach(button => {
        button.addEventListener('click', function () {
            const container = this.closest('.cta-icon-uploader');
            const img = container.querySelector('img');
            const input = container.querySelector('input[type="hidden"]');
            const defaultSrc = img.getAttribute('data-src');

            img.src = defaultSrc || CTA_PRO_URL + 'assets/icons/default-phone.png';
            input.value = '';
            this.style.display = 'none';
        });
    });
});
