# About DB-Team Invisible reCAPTCHA (Google Invisible reCAPTCHA integration)
=================================

Project page: http://projects.ez.no/

This extension is based on eZ Human CAPTCHA by Piotrek Karas - SELF s.c.


## Features
========

    Google Invisible reCAPTCHA


## Requirements
============

- PHP >= 5.3.10 (with PHP 7.x should it work too)
- eZ Publish 4.4 or newer, or 5.x (legacy)
- jQuery Core 1.7.2 or newer
    https://code.jquery.com/jquery/
- Google reCAPTCHA site key and secret (each site asscess can have own pair)<br>
    and put it into `invisible_re_captcha.ini`, see/read file:<br>
    `<thisExtension>/settings/invisible_re_captcha.ini`<br>
    https://www.google.com/recaptcha/admin


## Installation
===================

Enable extension in

`settings/override/site.ini.append.php`

```
[ExtensionSettings]
...
ActiveExtensions[]=dbteaminvisiblerecaptcha
...
```

Do NOT change extension name.

Regenerate autoloads, clear cache.

In terminal:

```
<ezpublish-root-dir>$ php bin/php/ezpgenerateautoloads.php
<ezpublish-root-dir>$ php bin/php/ezcache.php --clear-all

```

Add content attribute `DB-Team Invisible reCAPTCHA` to you content
class and check `Information collector` on that attribute.
`Required` is not necessary.


## TODO / Known issues
===================




## Technical notes
===============

Example:


