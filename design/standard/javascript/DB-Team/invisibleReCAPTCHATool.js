
(function ($) {
    window.dbTeamInvisibleReCAPTCHAService = {};
    /**
     *
     * @type {null|function($form, event)}
     */
    window.dbTeamInvisibleReCAPTCHAService.validate = null;

    var $gReCaptchaContainers = $('body:first .g-recaptcha');

    if(!$gReCaptchaContainers.length)
    {
        return ;
    }

    window.dbTeamInvisibleReCAPTCHAService.$forms = [];
    window.dbTeamInvisibleReCAPTCHAService.$form = null;

    var $forms = [];
    $gReCaptchaContainers.each(function(key, captchaContainer)
    {
        var $captchaContainer = $(captchaContainer);
        var attrId = $captchaContainer.data('attr-id');
        attrId = parseInt(attrId);
        if(isNaN(attrId) || attrId < 1)
        {
            //return ;
        }
        $forms.push($captchaContainer.parents('form:first'));
    });
    window.dbTeamInvisibleReCAPTCHAService.$forms = $($forms);

    if(!window.dbTeamInvisibleReCAPTCHAService.$forms.length)
    {
        return ;
    }

    window.dbTeamInvisibleReCAPTCHAService.validateByButton = function($button, e) {
        window.dbTeamInvisibleReCAPTCHAService.$form = null;

        var buttonName = $button.attr('name');
        if(buttonName === "DiscardButton" || buttonName === "Discard")
        {
            return ;
        }

        if(!$button.hasClass('clicked'))
        {
            $button.addClass('clicked');
        }

        e.preventDefault();
        var $form = $($button.get(0).form);
        window.dbTeamInvisibleReCAPTCHAService.$form = $form;

        if(typeof this.validate === "function" || typeof this.validate === "Function")
        {
            var isValid = this.validate($form, e);
            if(!isValid)
            {
                e.preventDefault();
                return ;
            }
        }

        var $reCaptchaItem = $form.find('.g-recaptcha:first');
        var reCaptchaItemId = $reCaptchaItem.attr('id');
        grecaptcha.execute(reCaptchaItemId);

        //$button.removeClass('clicked');
    };

    /**
     * handler for bushed ENTER on keyboard
     *
     * @param $form
     */
    window.dbTeamInvisibleReCAPTCHAService.validateByForm = function($form, e) {

        window.dbTeamInvisibleReCAPTCHAService.$form = $form;
        if($form.hasClass('re-captcha-success'))
        {
            return ;
        }

        if(typeof this.validate === "function" || typeof this.validate === "Function")
        {
            var isValid = this.validate($form, e);
            if(!isValid)
            {
                e.preventDefault();
                return ;
            }
        }
        e.preventDefault();

        var $reCaptchaItem = $form.find('.g-recaptcha:first');
        var reCaptchaItemId = $reCaptchaItem.attr('id');
        grecaptcha.execute(reCaptchaItemId);
    };

    window.dbTeamInvisibleReCAPTCHAService.isInformationCollectionForm = function($form)
    {
        var isItInformationColletorForm = false;
        if($form.find('input[name="ActionCollectInformation"]:first').length > 0)
        {
            isItInformationColletorForm = true;
        }

        return isItInformationColletorForm;
    };

    $.each(window.dbTeamInvisibleReCAPTCHAService.$forms, function(){
        var $form = (this);
        $form.find('input[type="submit"]').on('click', function(e) {
            var $button = $(this);
            //var $button = $(e.target);
            window.dbTeamInvisibleReCAPTCHAService.validateByButton($button, e);
        });
    });
    $.each(window.dbTeamInvisibleReCAPTCHAService.$forms, function(){
        var $form = $(this);
        $form.find('input[type="submit"]').on('submit', function (e) {
            var $form = $(e.target);
            window.dbTeamInvisibleReCAPTCHAService.validateByForm($form, e);
        });
    });


})(jQuery);

var onSubmitSuccessGoogleReCaptcha = function (responseContent) {
    var $form = window.dbTeamInvisibleReCAPTCHAService.$form;
    var $clickedButton = $form.find('input.clicked:first');

    var $gResponseVar = $form.find('[name="g-recaptcha-response"]:first');
    if(!$gResponseVar.length)
    {
        //alert('No G Response');
    }
    else
    {
        //alert("Response content: " + $gResponseVar.val());
    }
    //alert(4);

    if(!$form.hasClass('re-captcha-success')) {
        $form.addClass('re-captcha-success');
    }
    else
    {
        return ;
    }
    if($clickedButton.length) {
        var fakeButtonData = {};
        fakeButtonData['type'] = "hidden";
        fakeButtonData['name'] = $clickedButton.attr('name');
        fakeButtonData['value'] = $clickedButton.val();
        var fakeButton = document.createElement('input');
        var $fakeButton = $(fakeButton);
        $.each(fakeButtonData, function(attrName, value){
            $fakeButton.attr(attrName, value);
        });
        var $firstChild = $form.find('> *:first');
        $form.get(0).insertBefore(fakeButton, $firstChild.get(0));
        $form.submit();

        return;
    }

    $form.submit();
};

/**
 *
 * Validation service of submit form before send captcha validation request to Google.
 * If return false then form data will NOT be submitted to the server and invalid fields will get CSS class 'invalid'.
 *
 * @param {jQuery} $form
 * @param {object} e - event object
 * @returns {boolean}
 */
dbTeamInvisibleReCAPTCHAService.validate = function($form, e)
{
    var $requiredFields = $form.find('input:required,textarea:required,select:required');
    var isValid = true;
    $requiredFields.each(function(key, field)
    {
        var $field = $(field);
        var value = $field.val();
        $field.removeClass('invalid');
        if(!value || !$.trim(value))
        {
            isValid = false;
            $field.addClass('invalid');

            return ;
        }
    });

    return isValid;
};

