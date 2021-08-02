<?php
//these can be moved to a global config file
$absPath = $_SERVER['DOCUMENT_ROOT'] . ((substr($_SERVER['DOCUMENT_ROOT'], -1) != '/') ? '/' : '') .'weather/';
if(!defined("_FRAMEWORK_ABSOLUTE_PATH"))        define("_FRAMEWORK_ABSOLUTE_PATH", $absPath);

require_once _FRAMEWORK_ABSOLUTE_PATH . 'lib/auth.php';
require_once _FRAMEWORK_ABSOLUTE_PATH . 'lib/curlLib.php';
require_once _FRAMEWORK_ABSOLUTE_PATH . 'lib/formRequest.php';
require_once _FRAMEWORK_ABSOLUTE_PATH . 'modules/weather.php';
require_once _FRAMEWORK_ABSOLUTE_PATH . 'modules/sms.php';

class BaseClass
{
    public function __construct()
    {
        new Auth();
    }

    public function run()
    {
        //calling the weather module to get current temp
        $weatherObj = new Weather('Thessaloniki');
        $currentTemp = $weatherObj->getCurrentTemp();

        if($currentTemp !== FALSE)
        {
            if(isset($currentTemp->cod) || isset($currentTemp->message))
            {
                echo "Unable to fetch weather data. Error ".$currentTemp->cod." ".$currentTemp->message;
                return;
            }

            //sending sms
            $to         = '+0000000000';
            $smsObj     = new Sms($to, $currentTemp, 20);
            $returnData = $smsObj->send();

            if($returnData === TRUE)
            {
                echo "Sms send successfully";
            }
            else
            {
                echo "Unable to send sms. Error ".$returnData;
            }
        }
        else
        {
            echo "Unable to fetch weather data. Please try after some time.";
        }
    }
}

$baseObj = new BaseClass();
$baseObj->run();
?>


