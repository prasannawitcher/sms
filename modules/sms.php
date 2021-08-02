<?php
require_once _FRAMEWORK_ABSOLUTE_PATH . 'config/smsConfig.php';

Class Sms
{
    public $toSms       = '';
    public $currTemp    = '';
    public $thTemp      = '';
    public $authorizationToken ='';

    public function __construct($to='', $currentTemp, $threshHold)
    {
        if(empty($currentTemp) || empty($threshHold))
        {
            return FALSE;
        }

        $this->setAuthorizationToken();
        $this->currTemp = $currentTemp;
        $this->thTemp   = $threshHold;
        $this->toSms    = $to;
        if(empty($to))
        {
            $this->toSms = '+306911111111';
        }
        $this->send();

    }

    public function setAuthorizationToken()
    {
        $cookie_name = "authorizationToken";

        if(!empty($_COOKIE[$cookie_name]))
        {
            $this->authorizationToken = $_COOKIE[$cookie_name];
        }
        else
        {
            $params = [
                'authorization' => base64_encode(smsConfig::$appId.':'.smsConfig::$appSecret),
                'contentType'   => smsConfig::$authContentType,
                'url'           => smsConfig::$authUrl,
            ];
            $request    = new FormRequest('authTokenGen',$params);
            $returnData = $request->getResponse();

//            $returnData =  json_decode('{"access_token":"7d8194db-d296-453d-ab55-0b8b8a4e2ae4","token_type":"bearer","expires_in":3599,"scope":"voice lookup virtual_number contact report sms 2step number_validator account failover number_pool forms transactional_email email_sender promotional_email email_validator url_analyzer","permissions":["MT_ROLE_LOOKUP","MT_ROLE_NUMBER_VALIDATOR","MT_ROLE_ACCOUNT_FINANCE","MT_ROLE_SMS","MT_ROLE_REPORT","MT_ROLE_VOICE","MT_ROLE_NUMBER_POOL","MT_ROLE_2STEP","MT_ROLE_VIRTUAL_NUMBER","MT_ROLE_CONTACT","MT_ROLE_FAILOVER","MT_ROLE_FORMS","MT_ROLE_TRANSACTIONAL_EMAIL","MT_ROLE_EMAIL_SENDER","MT_ROLE_PRICING_PACKAGES","MT_ROLE_PROMOTIONAL_EMAIL","MT_ROLE_EMAIL_VALIDATOR","MT_ROLE_URL_ANALYZER"]}');

            if(!empty($returnData->access_token))
            {
                $expires_in = smsConfig::$defaultCookieExp;
                if(!empty($returnData->expires_in))
                {
                    $expires_in = $returnData->expires_in;
                }
                setcookie($cookie_name, $returnData->access_token, time() + $expires_in);
                $this->authorizationToken = $returnData->access_token;
            }

        }


    }

    public function getSmsContent()
    {
        $comp = 'more than';

        if($this->currTemp < $this->thTemp)
        {
            $comp = 'less than';
        }
        else if($this->currTemp == $this->thTemp)
        {
            $comp = 'same as';
        }

        $body = str_replace(smsConfig::$smsMsgSearchArr, array('Prasanna Mondal', $comp, $this->thTemp, $this->currTemp) , smsConfig::$smsMsg);

        return json_encode(array('body' => $body,'to' => $this->toSms,'from' => 'amdTelecom',));
    }


    public function send()
    {
        $params = [
            'smsContent'            => $this->getSmsContent(),
            'curlTimeout'           => smsConfig::$curlTimeout,
            'url'                   => smsConfig::$apiUrl,
            'authorizationToken'    => $this->authorizationToken,
            'contentType'           => smsConfig::$contentType,
        ];
        $request    = new FormRequest('sendSms',$params);
        $returnData = $request->getResponse();

        //$returnData = json_decode('{"trackingId":"6d52493e-006f-4d87-a544-20eab1d81d99","status":"Queued","createdAt":"2021-07-25T04:54:19.966Z","from":"amdTelecom","to":"+919836240762","body":"Hello my name is Prasanna Mondal and current temperature is more than than 20C. Current temperature 23.55","bodyAnalysis":{"parts":1,"unicode":false,"characters":105}');

        if(empty($returnData->error))
        {
            return TRUE;
        }
        else
        {
            return $returnData->error;
        }
    }

}

?>