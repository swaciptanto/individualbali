<?php

//optional
set_time_limit(0);

//every cron must include this
require_once 'config.php';

function refreshExchangeRate()
{
    $result = array();
    $currency_from = 'USD';
    $currency_tos = array(
        'AUD',
        'CAD',
        'CZK',
        'DKK',
        'EUR',
        'HKD',
        'HUF',
        'IDR',
        'ILS',
        'JPY',
        'MXN',
        'NOK',
        'NZD',
        'PHP',
        'PLN',
        'GBP',
        'SGD',
        'SEK',
        'CHF',
        'TWD',
        'THB'
        );
    foreach ($currency_tos as $currency_to) {
        echo "$currency_to: ";
        $url_target = 'http://query.yahooapis.com/v1/public/yql'
            . '?q=select%20*%20from%20yahoo.finance.xchange%20where%20pair%20in%20'
            . '%28%22' . $currency_from . $currency_to . '%22%29'
            . '&format=json&env=store://datatables.org/alltableswithkeys';
        $json = file_get_contents($url_target);
        //$json = '{"query":{"count":1,"created":"2016-12-28T04:20:49Z","lang":"en-US","results":{"rate":{"id":"USDIDR","Name":"USD/IDR","Rate":"13448.0000","Date":"12/28/2016","Time":"2:41am","Ask":"13548.0000","Bid":"13448.0000"}}}}';
        $decoded = json_decode($json);
        if (is_object($decoded->query->results->rate)) {
            $result["$currency_from$currency_to"] = (float)$decoded->query->results->rate->Rate;
            echo $decoded->query->results->rate->Rate;
        }
        echo "\n";
    }
    if (count($result) > 0) {
        $fc  = '<?php' . "\n";
        $fc .= '$exchange_rate = array();' . "\n";
        $fc .= '$exchange_rate["USDUSD"] = 1;' . "\n";
        foreach($result as $ckey => $cval) {
            $fc .= '$exchange_rate[' . "'$ckey'" . '] = ' . "'$cval';\n";
        }
        $fc .= '?>';

        $exchange_rate_filename = CONFIG_PATH . 'exchange.rate.php';
        if (!file_exists($exchange_rate_filename)) {
            $handle = fopen($exchange_rate_filename, 'w');
            fclose($handle);
        }
        file_put_contents($exchange_rate_filename, $fc);
    }
}

echo "Start on " . date('d-m-Y H:i:s') . "\n";
refreshExchangeRate();
echo "Done on " . date('d-m-Y H:i:s') . "\n";

