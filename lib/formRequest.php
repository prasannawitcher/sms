<?php
Class FormRequest
{
    public $requestType;
    public $params;
    public function __construct($requestType,$params)
    {
        $this->requestType  = $requestType;
        $this->params       = $params;
    }

    public function getResponse()
    {
        $data = FALSE;

        switch ($this->requestType)
        {
            case 'weather': //for getting weather data
                $curlOptions = [
                    CURLOPT_URL             => $this->params['url'].'?q='.$this->params['locationName'].'&units='.$this->params['units'].'&appid='.$this->params['key'],
                    CURLOPT_CONNECTTIMEOUT  => 2,
                    CURLOPT_RETURNTRANSFER  => 1,
                ];
                $curlLib = new CurlLib($curlOptions);
                $data = $curlLib->getAllResults();
                break;

            case 'authTokenGen': //for generating auth token
                $curlOptions = [
                    CURLOPT_URL                 => $this->params['url'],
                    CURLOPT_RETURNTRANSFER      => true,
                    CURLOPT_ENCODING            => "",
                    CURLOPT_MAXREDIRS           => 10,
                    CURLOPT_TIMEOUT             => 30,
                    CURLOPT_HTTP_VERSION        => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST       => "POST",
                    CURLOPT_POSTFIELDS          => "grant_type=client_credentials",
                    CURLOPT_HTTPHEADER          => array(
                            "authorization: Basic ".$this->params['authorization'],
                            "content-type: ".$this->params['contentType'],
                    ),
                ];
                $curlLib = new CurlLib($curlOptions);
                $data = $curlLib->getAllResults();
                break;

            case 'sendSms': //for sending sms
                $curlOptions = [
                    CURLOPT_URL             => $this->params['url'],
                    CURLOPT_RETURNTRANSFER  => true,
                    CURLOPT_ENCODING        => "",
                    CURLOPT_MAXREDIRS       => 10,
                    CURLOPT_TIMEOUT         => $this->params['curlTimeout'],
                    CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST   => "POST",
                    CURLOPT_POSTFIELDS      => $this->params['smsContent'],
                    CURLOPT_HTTPHEADER      => array(
                        "authorization: Bearer ".$this->params['authorizationToken'],
                        "content-type: ".$this->params['contentType'],
                    ),
                ];
                $curlLib    = new CurlLib($curlOptions);
                $data       = $curlLib->getAllResults();
                break;
        }

        return json_decode($data);

    }
}


?>