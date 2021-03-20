<?php

namespace DolarApi;

class App
{
    protected $printer;
    protected $converter;

    public function __construct()
    {
      $this->printer = new Printer();
      $this->converter = new Converter();
    }


    /**
     * Get printer
     *
     * @param void
     *
     * @return $printer
     */

    public function getPrinter()
    {
        return $this->printer;
    }

    /**
     * Get converter
     *
     * @param void
     *
     * @return $converter
     */

    public function getConverter()
    {
        return $this->converter;
    }


    /**
     * Display conversion of param1 on currency of param2
     *
     * @param array $argv
     *
     * @return void
     */

    public function displayConversion(array $argv)
    {
      //Validando que el primer parámetro sea numérico y esté seteado
      $toConvert = 1;
      if (isset($argv[1]) && is_numeric($argv[1])) {
          $toConvert = $argv[1];
      }else {
        $this->getPrinter()->display('El primer parámetro debe ser numérico.');
        exit;
      }
      //Validando segundo parámetro filtrando aquellos valores por fuera de los predefinidos
      $validCurrencies = array ("ARS","CAD","EUR","PHP","AUD","NZD","TRY");

      $currency = isset($argv[2]) ? $argv[2] : "";

      if (!in_array($currency,$validCurrencies)){
        $this->getPrinter()->display('Debe seleccionar entre las siguientes monedas para obtener la conversión a USD.');
        foreach ($validCurrencies as $validCurrency) {
          $this->getPrinter()->display(' - '.$validCurrency);
        }
        exit;
      }

      //Seteamos la moneda en el conversor para evitar pasarla como parámetro más adelante
      $this->getConverter()->setCurrency($currency);

      //Nos guardamos la cotización para poder hacer la conversión y luego mostrarla en pantalla
      $rate = $this->getConverter()->getLastRate();

      //Convirtiendo según la cotización obtenida
      $converted = $this->getConverter()->getConvertedValue($toConvert,$rate);

      if ($converted > 0) {
        $this->getPrinter()->display('Cotización: 1 USD -> $'.$rate.' '.$currency.'.');
        $this->getPrinter()->display('$'.$toConvert.' '.$currency .' -> '.$converted.' USD.');
      }else {
        $this->getPrinter()->display('Ha ocurrido algún error con la API.');
      }
    }


}
