<?php

class Web_Ppfix_Helper_Data extends Mage_Core_Helper_Abstract {
    public static function getSupportedCurrency()
    {
        return array('AUD', 'CAD', 'CZK', 'DKK', 'EUR', 'HKD', 'HUF', 'ILS', 'JPY', 'MXN',
            'NOK', 'NZD', 'PLN', 'GBP', 'SGD', 'SEK', 'CHF', 'USD', 'TWD', 'THB');
    }
    public static function shouldConvert()
    {
        return !in_array(Mage::app()->getStore()-> getCurrentCurrencyCode(),self::getSupportedCurrency());
    }
    public static function getConfig($name = '')
    {
        if($name)
        {
            return Mage::getStoreConfig('payment/ppfix/'.$name);
        }
        return ;
    }
    public static function getToCurrency()
    {
        $to = self::getConfig('to_currency');
        if(!$to){
            $to = 'USD';
        }
        return $to;
    }
    public function isActive()
    {

    }
    public function convertAmount($amount = false)
    {
        return self::getExchangeRate($amount);
    }
    public static function getExchangeRate($amount = false)
    {
        if(!self::shouldConvert())
        {
            return $amount;
        }
        if(!$amount){
            return ;
        }
        $auto = self::getConfig('auto_rate');
        if($auto){
            $current = Mage::app()->getStore()-> getCurrentCurrencyCode();
            $to = self::getToCurrency();
            $rate = Mage::getModel('directory/currency')->getCurrencyRates($current, $to);
            var_dump($rate);
            if(!empty($rate[$to]))
            {
                $rate = $rate[$to];
            }else{
                $rate = 1;
            }
        }else{
            $rate = self::getConfig('rate');
        }
        if($rate){
            return $amount * $rate;
        }
        return ;
    }

}