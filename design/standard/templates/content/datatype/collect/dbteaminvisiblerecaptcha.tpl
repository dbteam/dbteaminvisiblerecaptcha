
{ezscript_require(array('DB-Team/invisibleReCAPTCHATool.js'))}

{def $reCAPTCHASiteKey = fetch('dbteaminvisiblerecaptcha', 'site_key')}
{if $reCAPTCHASiteKey|not()}
    <div>Missing site key for Google reCAPTCHA</div>
{/if}
{*<p>Site key: {$reCAPTCHASiteKey}</p>*}

<div id='Recaptcha-{$attribute.id}'
     class="recaptcha-custom g-recaptcha"
     {*data-attr-id="{$attribute.id}"*}
     data-sitekey="{$reCAPTCHASiteKey}"
     data-callback="onSubmitSuccessGoogleReCaptcha"
     data-size="invisible"
     data-badge="inline"
     data-theme="light"
></div>

{undef $reCAPTCHASiteKey}


{run-once}

{* <script src="https://www.google.com/recaptcha/api.js" async defer></script> *}

    <script src="https://www.google.com/recaptcha/api.js" async></script>
{/run-once}

