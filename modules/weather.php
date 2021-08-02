<?php
//Weather class to get latest weather data
require_once _FRAMEWORK_ABSOLUTE_PATH . 'config/weatherConfig.php';

Class Weather
{
    public $params = array();
    public function __construct($locationName='')
    {
        if(empty($locationName))
        {
            $locationName = WeatherConfig::$cityName;
        }

        $this->params = [
            'locationName'  => $locationName,
            'units'         => WeatherConfig::$unit,
            'key'           => WeatherConfig::$apiKey,
            'url'           => WeatherConfig::$apiUrl,
        ];
    }

    public function getCurrentTemp()
    {
        $request    = new FormRequest('weather',$this->params);
        $returnData = $request->getResponse();
//        $returnData = json_decode('{"coord":{"lon":22.9439,"lat":40.6403},"weather":[{"id":801,"main":"Clouds","description":"few clouds","icon":"02d"}],"base":"stations","main":{"temp":30.26,"feels_like":30.25,"temp_min":28.54,"temp_max":31.92,"pressure":1012,"humidity":42},"visibility":10000,"wind":{"speed":0.89,"deg":225,"gust":3.58},"clouds":{"all":20},"dt":1627054057,"sys":{"type":2,"id":2036703,"country":"GR","sunrise":1627010197,"sunset":1627062748},"timezone":10800,"id":734077,"name":"Thessaloniki","cod":200}');

        if(!empty($returnData->main->temp))
        {
            return $returnData->main->temp;
        }
        else if(!empty($returnData->cod) && !empty($returnData->message))
        {
            return $returnData;
        }

        return FALSE;
    }

}

?>