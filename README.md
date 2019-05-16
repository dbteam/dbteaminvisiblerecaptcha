# About DB-Team Invisible reCAPTCHA (Google Invisible reCAPTCHA integration)
=================================

Project page: https://github.com/dbteam/dbteaminvisiblerecaptcha

This extension is based on eZ Human CAPTCHA by Piotrek Karas - SELF s.c.


## Features
========

    Protect your forms using Google Invisible reCAPTCHA from BOTs
    for eZ Publiesh 4 / eZ Publish 5 legacy


## Requirements
============

- PHP ^5.3.10 (5.3.10 <= PHP < 6) (with PHP 7.x should it work too without errors)
- eZ Publish ^4.4, or ^5.x (legacy)
- jQuery Core 1.7.2 or newer
    https://code.jquery.com/jquery/
- Google reCAPTCHA site key and secret (each site asscess can have own pair)<br>
    and put it into `invisible_re_captcha.ini`, see/read file:<br>
    `<thisExtension>/settings/invisible_re_captcha.ini`<br>
    https://www.google.com/recaptcha/admin

## Downloads

    Suggested the newest version, example: "^1.4.1" (composer notation) (see Git tags).

- Git: https://github.com/dbteam/dbteaminvisiblerecaptcha
- packagist.org: https://packagist.org/packages/dbteam/dbteaminvisiblerecaptcha
- projects.ez.no: http://projects.ez.no/dbteaminvisiblerecaptcha

## Installation and configuration
===================

Enable extension in

`settings/override/site.ini.append.php`

```
[ExtensionSettings]
...
ActiveExtensions[]=dbteaminvisiblerecaptcha
...

```

INI: <br>
`invisible_re_captcha.ini`, see/read file:<br>
`<thisExtension>/settings/invisible_re_captcha.ini`

Do NOT change extension name.

Regenerate autoloads, clear cache.

In terminal:

```
<ezpublish-root-dir>$ php bin/php/ezpgenerateautoloads.php
<ezpublish-root-dir>$ php bin/php/ezcache.php --clear-all

```

Add content attribute `DB-Team Invisible reCAPTCHA` to your content
class and check `Information collector` on that attribute.
`Required` is not necessary.


## TODO / Known issues
===================




## Technical notes
===============

In file `invisible-recaptcha-error.log` are stored supported issues,
suspected BOT IPs<br>
`var/log/invisible-recaptcha-error.log`

If you need other, better JS form validation then just override `dbTeamInvisibleReCAPTCHAService.validate()`.<br>
It is in global scope.
For more info read annotation of `dbTeamInvisibleReCAPTCHAService.validate` in `invisibleReCAPTCHATool.js` file.

PS: Default webbrowser validation is disabled i must do that, i can't keep it with Google reCAPTCHA JS.
Maybe someone know how to keep it?

Example:

## Authors

- Radosław Z

## License

Copyright (c) 2017 DB-Team. This repository is released under the GNU GPL v2.0 license.

