<?php
Class CurlLib
{
    private $curlOptions;
    private $results;

    public function __construct($curlOptions = array())
    {
        $this->setCurlOptions($curlOptions);
    }

    private function setCurlOptions($curlOptions)
    {
        $this->curlOptions = $curlOptions;
    }

    public function getAllResults()
    {
        $this->runCurl();

        return $this->results;
    }

    private function runCurl()
    {
        $ch = curl_init();

        foreach ($this->curlOptions as $option => $value)
        {
            curl_setopt($ch, $option, $value);
        }
        $response = curl_exec($ch);
        $err = curl_error($ch);
echo "<pre>";print_r($response);echo "-------------------";
        curl_close($ch);

        if ($err) {
            $this->results = $err;
        } else {
            $this->results = $response;
        }
    }
}
?>