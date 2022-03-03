<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

// Google reCAPTCHA
$siteKey = Bitrix\Main\Config\Option::get(GDZ\Local\Helper::MODULE_ID, 'CAPTCHA_SITE_KEY');

echo "
<script>
    window.addEventListener('DOMContentLoaded', () => {
        const loadScript = async (src) =>
            new Promise((resolve, reject) => {
                const script = document.createElement('script');
                script.setAttribute('src', src);
                document.body.appendChild(script);

                script.onload = resolve;
                script.onerror = reject;
            })

        loadScript('https://www.google.com/recaptcha/api.js?render=$siteKey')
            .then(() => {
                grecaptcha.ready(function () {                    
                    let field = document.getElementById('token');

                    if (field) {
                        grecaptcha.execute('$siteKey', {action: 'feedback'}).then(function (token) {
                            field.value = token
                        })
                    }
                })
            })
            .catch((error) => {
                console.log(error);
            });
    })
</script>
";