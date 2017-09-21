<?php


$FunctionList = array();

$FunctionList['site_key'] = array(
    'name' => 'site_key',
    'operation_types' => array('read'),
    'call_method' => array(
        'class' => '\DBTeam\Google\InvisibleReCaptcha\EzInvisibleReCaptchaService',
        'method' => 'fetchSiteKey'
    ),
    'parameter_type' => 'standard',
    'parameters' => array()
);


