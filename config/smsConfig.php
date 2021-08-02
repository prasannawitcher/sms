<?php

class SmsConfig
{
    public function __construct()
    {

    }

    public static $apiUrl       = 'https://connect.routee.net/sms';
    public static $authUrl      = 'https://auth.routee.net/oauth/token';
    public static $appId        = 'XXXXXXXXXXXX';
    public static $appSecret    = 'XXXXX';
    public static $smsMsg       = '[[name]] and current temperature is [[moreLess]] than [[threshHold]]C. Current temperature [[temp]]';
    public static $smsMsgSearchArr      = array('[[name]]', '[[moreLess]]', '[[threshHold]]', '[[temp]]');
    public static $curlTimeout          = 30;
    public static $contentType          = 'application/json';
    public static $authContentType      = 'application/x-www-form-urlencoded';
    public static $defaultCookieExp     = 600;
}

?>