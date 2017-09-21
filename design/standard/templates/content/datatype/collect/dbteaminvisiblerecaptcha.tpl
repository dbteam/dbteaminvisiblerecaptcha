{* @todo: this TPL *}

{def $reCAPTCHASiteKey = fetch('dbteaminvisiblerecaptcha', 'site_key')}
{if $reCAPTCHASiteKey|not()}
    <div>Missing site key for Google reCAPTCHA</div>
{/if}
<p>Site key: {$reCAPTCHASiteKey}</p>

<div id='Recaptcha-{$attribute.id}'
     class="g-recaptcha"
     data-attr-id="{$attribute.id}"
     data-sitekey="{$reCAPTCHASiteKey}"
     data-callback="callbackSubmittedReCaptcha"
     data-size="invisible"></div>

{undef $reCAPTCHASiteKey}
