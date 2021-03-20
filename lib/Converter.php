<?php

namespace DolarApi;

class Converter
{
    protected $lastData;
    protected $currency;
    protected $currencyURL;


    /**
     * Set currency and API endpoint URL
     *
     * @param string $currency
     *
     * @return void
     */

    function setCurrency($currency)
    {
      $this->currency = $currency;
      switch ($this->currency) {
        case 'ARS':
          $this->currencyURL = 'https://api-dolar-argentina.herokuapp.com/api/dolarblue';
          break;

        default:
          $this->currencyURL = 'https://api.exchangeratesapi.io/latest?base=USD';
          break;
      }
    }


    /**
     * Get last data saved from API
     *
     * @param void
     *
     * @return array
     */

    public function getLastData()
    {
      $string = file_get_contents($this->currency.".json");
      $json_a = json_decode($string, true);

      return $json_a;
    }


    /**
     * Set last data fetched from API
     *
     * @param array $data
     *
     * @return void
     */

    public function setLastData($data)
    {
        $fp = fopen($this->currency.'.json','w');
        fwrite($fp,json_encode($data));
        fclose($fp);
    }


    /**
     * Get last rate depending on currency
     *
     * @param void
     *
     * @return decimal
     */

    public function getLastRate()
    {

        $data = $this->getData('GET',$this->currencyURL,false);

        if (!is_null($data)){
          $this->setLastData($data);
        }

        switch ($this->currency) {
          case 'ARS':
            $lastValue = is_null($this->getLastData()) ? 0 : $this->getLastData()['venta'];
            break;

          default:
            $lastValue = is_null($this->getLastData()) ? 0 : $this->getLastData()['rates'][$this->currency];
            break;
        }

        return number_format($lastValue,2);
    }


    /**
     * Get converted value
     *
     * @param $toConvert,$lastValue
     *
     * @return decimal
     */

    public function getConvertedValue($toConvert,$lastValue)
    {
        // if ($lastValue > 0) {
        //   $converted = $toConvert/$lastValue;
        // }else {
        //   $converted = 0;
        // }

        $converted = $lastValue > 0 ? $toConvert/$lastValue : 0;

        return number_format($converted,2);
    }


    /**
     * Get data from API
     *
     * @param $method,$url,$data
     *
     * @return array
     */

    function getData($method, $url, $data = false)
    {
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_HTTPGET, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response_json  = curl_exec($curl);

        curl_close($curl);

        $response=json_decode($response_json, true);

        return $response;
    }

}
