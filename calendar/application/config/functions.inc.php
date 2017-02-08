<?php

class Util {

    /**
     * Redirect browser
     *
     * @param string $url
     * @param int $http_response_code
     * @param bool $exit
     * @return void
     * @access public
     * @static
     */
    public static $currencies = array(
        'USD' => array('name' => "U.S. Dollar", 'symbol' => "$", 'ASCII' => "&#36;"),
        'EUR' => array('name' => "Euro", 'symbol' => "€", 'ASCII' => "&#128;"),
        'GBP' => array('name' => "Pound Sterling", 'symbol' => "£", 'ASCII' => "&#163;"),
        'IDR' => array('name' => "Indonesian Rupiah", 'symbol' => "IDR", 'ASCII' => ""),
        'AUD' => array('name' => "Australian Dollar", 'symbol' => "A$", 'ASCII' => "A&#36;"),
        'SGD' => array('name' => "Singapore Dollar", 'symbol' => "$", 'ASCII' => "&#36;"),
        'HKD' => array('name' => "Hong Kong Dollar", 'symbol' => "$", 'ASCII' => "&#36;"),
        'CAD' => array('name' => "Canadian Dollar", 'symbol' => "$", 'ASCII' => "&#36;"),
        'CHF' => array('name' => "Swiss Franc", 'symbol' => "CHF", 'ASCII' => ""),
        'CZK' => array('name' => "Czech Koruna", 'symbol' => "Kč", 'ASCII' => ""),
        'DKK' => array('name' => "Danish Krone", 'symbol' => "Kr", 'ASCII' => ""),
        'HUF' => array('name' => "Hungarian Forint", 'symbol' => "Ft", 'ASCII' => ""),
        'ILS' => array('name' => "Israeli New Sheqel", 'symbol' => "₪", 'ASCII' => "&#8361;"),
        'JPY' => array('name' => "Japanese Yen", 'symbol' => "¥", 'ASCII' => "&#165;"),
        'MXN' => array('name' => "Mexican Peso", 'symbol' => "$", 'ASCII' => "&#36;"),
        'NOK' => array('name' => "Norwegian Krone", 'symbol' => "Kr", 'ASCII' => ""),
        'NZD' => array('name' => "New Zealand Dollar", 'symbol' => "$", 'ASCII' => "&#36;"),
        'PHP' => array('name' => "Philippine Peso", 'symbol' => "₱", 'ASCII' => ""),
        'PLN' => array('name' => "Polish Zloty", 'symbol' => "zł", 'ASCII' => ""),
        'SEK' => array('name' => "Swedish Krona", 'symbol' => "kr", 'ASCII' => ""),
        'THB' => array('name' => "Thai Baht", 'symbol' => "฿", 'ASCII' => "&#3647;"),
        'TWD' => array('name' => "Taiwan New Dollar", 'symbol' => "NT$", 'ASCII' => "NT&#36;"),
    );
    public static $owner_tokens = array(
        'name' => '{Name}',
        'phone' => '{Phone}',
        'email' => '{Email}',
        'message' => '{Message}',
        'startdate' => '{DateFrom}',
        'finishdate' => '{DateTo}',
        'total' => '{Total}',
        'url' => '{URL}',
    );
    public static $owner_tokens_info = array(
        '{Name} - First Name And Last Name',
        '{Phone} - Phone',
        '{Email} - Email',
        '{Message} - Message',
        '{DateFrom} - Start Date',
        '{DateTo} - End Date',
        '{Total} - Total',
        '{URL} - Villa URL',
    );
    public static $tokens = array(
        'id' => '{BookingID}',
        'title' => '{Title}',
        'first_name' => '{FirstName}',
        'second_name' => '{LastName}',
        'phone' => '{Phone}',
        'email' => '{Email}',
        'company' => '{Company}',
        'address_1' => '{Address1}',
        'address_2' => '{Address2}',
        'city' => '{City}',
        'state' => '{State}',
        'zip' => '{Zip}',
        'country' => '{Country}',
        'fax' => '{Fax}',
        'male' => '{Male}',
        'additional' => '{Additional}',
        'adults' => '{Adults}',
        'children' => '{Children}',
        'nights' => '{Nights}',
        'date_from' => '{DateFrom}',
        'date_to' => '{DateTo}',
        'calendars' => '{Calendars}',
        'extras' => '{Extras}',
        'cc_type' => '{CCType}',
        'cc_num' => '{CCNum}',
        'cc_code' => '{CCExpMonth}',
        'cc_exp_month' => '{CCExpYear}',
        'cc_exp_year' => '{CCSec}',
        'payment_method' => '{PaymentMethod}',
        'deposit' => '{Deposit}',
        'tax' => '{Tax}',
        'total' => '{Total}',
        'calendars_price' => '{CalendarPrice}',
        'extra_price' => '{ExtraPrice}',
        'discount' => '{Discount}',
        'nights' => '{Nights}',
    );
    public static $invoice_tokens = array(
        'booking_number' => '{BookingNumber}',
        'booking_id' => '{BookingID}',
        'title' => '{Title}',
        'first_name' => '{FirstName}',
        'second_name' => '{LastName}',
        'phone' => '{Phone}',
        'email' => '{Email}',
        'company' => '{Company}',
        'address_1' => '{Address1}',
        'address_2' => '{Address2}',
        'city' => '{City}',
        'state' => '{State}',
        'zip' => '{Zip}',
        'country' => '{Country}',
        'fax' => '{Fax}',
        'adults' => '{Adults}',
        'children' => '{Children}',
        'date_from' => '{DateFrom}',
        'date_to' => '{DateTo}',
        'rooms' => '{Rooms}',
        'extras' => '{Extras}',
        'payment_method' => '{PaymentMethod}',
        'deposit' => '{Deposit}',
        'tax' => '{Tax}',
        'total' => '{Total}',
        'calendars_price' => '{CalendarPrice}',
        'extra_price' => '{ExtraPrice}',
        'discount' => '{Discount}',
        'invoice_number' => '{InvoiceNumber}',
        'invoice_company' => '{YourCompany}',
        'invoice_name' => '{YourName}',
        'invoice_address' => '{YourAddress}',
        'invoice_city' => '{YourCity}',
        'invoice_state' => '{YourState}',
        'invoice_zip' => '{YourZip}',
        'invoice_fax' => '{YourFax}',
        'invoice_phone' => '{YourPhone}',
        'invoice_email' => '{YourEmail}',
        'date' => '{InvoiceDate}',
        'status' => '{Status}',
        'calendar_price' => '{CalendarPrice}',
        'calendars' => '{Calendars}',
        'nights' => '{Nights}',
        'additional' => '{Additional}',
    );

    public static function redirect($url, $http_response_code = null, $exit = true) {

        echo '<html><head><title></title><script type="text/javascript">window.location.href="' . $url . '";</script></head><body></body></html>';

        if ($exit) {
            exit();
        }
    }

    /**
     * Print notice
     *
     * @param string $value
     * @return string
     * @access public
     * @static
     */
    public static function printNotice($value, $class, $convert = true) {
        ?>
        <div class="box-body">
            <div class="alert <?php echo $class; ?> alert-dismissable">
                <i class="fa fa-ban"></i>
                <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
                <b>Alert!</b>
                <?php echo $convert ? htmlspecialchars(stripslashes($value)) : stripslashes($value); ?>
            </div>
        </div>
        <?php
    }

    public static function getTimezone($offset) {
        $db = array(
            '-14400' => 'America/Porto_Acre',
            '-18000' => 'America/Porto_Acre',
            '-7200' => 'America/Goose_Bay',
            '-10800' => 'America/Halifax',
            '14400' => 'Asia/Baghdad',
            '-32400' => 'America/Anchorage',
            '-36000' => 'America/Anchorage',
            '-28800' => 'America/Anchorage',
            '21600' => 'Asia/Aqtobe',
            '18000' => 'Asia/Aqtobe',
            '25200' => 'Asia/Almaty',
            '10800' => 'Asia/Yerevan',
            '43200' => 'Asia/Anadyr',
            '46800' => 'Asia/Anadyr',
            '39600' => 'Asia/Anadyr',
            '0' => 'Atlantic/Azores',
            '-3600' => 'Atlantic/Azores',
            '7200' => 'Europe/London',
            '28800' => 'Asia/Brunei',
            '3600' => 'Europe/London',
            '-39600' => 'America/Adak',
            '32400' => 'Asia/Shanghai',
            '36000' => 'Asia/Choibalsan',
            '-21600' => 'America/Chicago',
            '-25200' => 'Chile/EasterIsland',
            '-43200' => 'Pacific/Kwajalein'
        );
        if (is_null($offset) && strlen($offset) === 0) {
            return $db;
        }
        return array_key_exists($offset, $db) ? $db[$offset] : false;
    }

    public static function getPageURL() {
        $pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }

//You do not need to alter these functions
    public static function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale = 1) {
        list($imagewidth, $imageheight, $imageType) = getimagesize($image);
        $imageType = image_type_to_mime_type($imageType);

        $newImageWidth = ceil($width * $scale);
        $newImageHeight = ceil($height * $scale);
        $newImage = imagecreatetruecolor($newImageWidth, $newImageHeight);
        switch ($imageType) {
            case "image/gif":
                $source = imagecreatefromgif($image);
                break;
            case "image/pjpeg":
            case "image/jpeg":
            case "image/jpg":
                $source = imagecreatefromjpeg($image);
                break;
            case "image/png":
            case "image/x-png":
                $source = imagecreatefrompng($image);
                break;
        }
        imagecopyresampled($newImage, $source, 0, 0, $start_width, $start_height, $newImageWidth, $newImageHeight, $width, $height);
        switch ($imageType) {
            case "image/gif":
                imagegif($newImage, $thumb_image_name);
                break;
            case "image/pjpeg":
            case "image/jpeg":
            case "image/jpg":
                imagejpeg($newImage, $thumb_image_name, 90);
                break;
            case "image/png":
            case "image/x-png":
                imagepng($newImage, $thumb_image_name);
                break;
        }
        chmod($thumb_image_name, 0777);
        return $thumb_image_name;
    }

    public static function parse_query_str($query) {

        $arr1 = explode('&', $query);
        $arr2 = array();

        foreach ($arr1 as $k => $v) {
            $arr = explode('=', $v);

            if (is_array($arr[0]) || strpos($arr[0], '%5B%5D')) {

                $key = str_replace("%5B%5D", "", $arr[0]);
                $arr2[$key][] = $arr[1];
            } else {
                $arr2[$arr[0]] = $arr[1];
            }
        }
        return $arr2;
    }

    public static function getJsDateFormat($php_format) {

        $dateFormats['Y-m-d'] = array('js' => 'yy-mm-dd', 'php' => 'Y-m-d', 'separator' => '-', 'iso' => 'YYYY-MM-DD');
        $dateFormats['Y/m/d'] = array('js' => 'yy/mm/dd', 'php' => 'Y/m/d', 'separator' => '/', 'iso' => 'YYYY/MM/DD');
        $dateFormats['Y.m.d'] = array('js' => 'yy.mm.dd', 'php' => 'Y.m.d', 'separator' => '.', 'iso' => 'YYYY.MM.DD');
        $dateFormats['m-d-Y'] = array('js' => 'mm-dd-yy', 'php' => 'm-d-Y', 'separator' => '-', 'iso' => 'MM-DD-YYYY');
        $dateFormats['m/d/Y'] = array('js' => 'mm/dd/yy', 'php' => 'm/d/Y', 'separator' => '/', 'iso' => 'MM/DD/YYYY');
        $dateFormats['m.d.Y'] = array('js' => 'mm.dd.yy', 'php' => 'm.d.Y', 'separator' => '.', 'iso' => 'MM.DD.YYYY');
        $dateFormats['d-m-Y'] = array('js' => 'dd-mm-yy', 'php' => 'd-m-Y', 'separator' => '-', 'iso' => 'DD-MM-YYYY');
        $dateFormats['d/m/Y'] = array('js' => 'dd/mm/yy', 'php' => 'd/m/Y', 'separator' => '/', 'iso' => 'DD/MM/YYYY');
        $dateFormats['d.m.Y'] = array('js' => 'dd.mm.yy', 'php' => 'd.m.Y', 'separator' => '.', 'iso' => 'DD.MM.YYYY');

        if (!empty($php_format)) {
            if (array_key_exists($php_format, $dateFormats)) {
                return $dateFormats[$php_format]['js'];
            }
        }
        return $dateFormats['d.m.Y']['js'];
    }

    public static function getISODateFormat($php_format) {

        $dateFormats['Y-m-d'] = array('js' => 'yy-mm-dd', 'php' => 'Y-m-d', 'separator' => '-', 'iso' => 'YYYY-MM-DD');
        $dateFormats['Y/m/d'] = array('js' => 'yy/mm/dd', 'php' => 'Y/m/d', 'separator' => '/', 'iso' => 'YYYY/MM/DD');
        $dateFormats['Y.m.d'] = array('js' => 'yy.mm.dd', 'php' => 'Y.m.d', 'separator' => '.', 'iso' => 'YYYY.MM.DD');
        $dateFormats['m-d-Y'] = array('js' => 'mm-dd-yy', 'php' => 'm-d-Y', 'separator' => '-', 'iso' => 'MM-DD-YYYY');
        $dateFormats['m/d/Y'] = array('js' => 'mm/dd/yy', 'php' => 'm/d/Y', 'separator' => '/', 'iso' => 'MM/DD/YYYY');
        $dateFormats['m.d.Y'] = array('js' => 'mm.dd.yy', 'php' => 'm.d.Y', 'separator' => '.', 'iso' => 'MM.DD.YYYY');
        $dateFormats['d-m-Y'] = array('js' => 'dd-mm-yy', 'php' => 'd-m-Y', 'separator' => '-', 'iso' => 'DD-MM-YYYY');
        $dateFormats['d/m/Y'] = array('js' => 'dd/mm/yy', 'php' => 'd/m/Y', 'separator' => '/', 'iso' => 'DD/MM/YYYY');
        $dateFormats['d.m.Y'] = array('js' => 'dd.mm.yy', 'php' => 'd.m.Y', 'separator' => '.', 'iso' => 'DD.MM.YYYY');

        if (!empty($php_format)) {
            if (array_key_exists($php_format, $dateFormats)) {
                return $dateFormats[$php_format]['iso'];
            }
        }
        return $dateFormats['d.m.Y']['iso'];
    }

    public static function dateToTimestamp($stFormat, $stData) {

        $aDataRet = array();

        $aPieces = preg_split('[\.|-|/]', $stFormat);
        $aDatePart = preg_split('[\.|-|/]', $stData);

        foreach ($aPieces as $key => $chPiece) {
            switch ($chPiece) {
                case 'd':
                case 'j':
                    $aDataRet['day'] = $aDatePart[$key];
                    break;

                case 'F':
                case 'M':
                case 'm':
                case 'n':
                    $aDataRet['month'] = $aDatePart[$key];
                    break;

                case 'o':
                case 'Y':
                case 'y':
                    $aDataRet['year'] = $aDatePart[$key];
                    break;

                case 'g':
                case 'G':
                case 'h':
                case 'H':
                    $aDataRet['hour'] = $aDatePart[$key];
                    break;

                case 'i':
                    $aDataRet['minute'] = $aDatePart[$key];
                    break;

                case 's':
                    $aDataRet['second'] = $aDatePart[$key];
                    break;
            }
        }
        return mktime(0, 0, 0, (int)$aDataRet['month'], (int)$aDataRet['day'], (int)$aDataRet['year']);
    }

    public static function getCurrencySimbol($code) {
        if (!empty($code) && array_key_exists($code, self::$currencies)) {
            return self::$currencies[$code]['symbol'];
        } else {
            return $code;
        }
    }

    public static function currencyFormat($code, $price) {
        if (!empty($_SESSION['currency'])) {
            $currency_from = $code;
            $currency_to = $_SESSION['currency'];
            $currency_input = $price;
            $price = self::currencyConverter($currency_from, $currency_to, $currency_input);
            $code = $_SESSION['currency'];
        }
        $price = self::formatMoney($price, $currency_to);
        if (!empty($code) && array_key_exists($code, self::$currencies)) {
            if ($code == 'SEK') {
                return $price . ' ' . self::$currencies[$code]['symbol'];
            } else {
                return self::$currencies[$code]['symbol'] . ' ' . $price;
            }
        } else {
            return $code . ' ' . $price;
        }
    }

    //modified: new
    public static function formatMoney($money, $code = "USD"){
        $currencies['ARS'] = array(2,',','.');          //  Argentine Peso
        $currencies['AMD'] = array(2,'.',',');          //  Armenian Dram
        $currencies['AWG'] = array(2,'.',',');          //  Aruban Guilder
        $currencies['AUD'] = array(2,'.',' ');          //  Australian Dollar
        $currencies['BSD'] = array(2,'.',',');          //  Bahamian Dollar
        $currencies['BHD'] = array(3,'.',',');          //  Bahraini Dinar
        $currencies['BDT'] = array(2,'.',',');          //  Bangladesh, Taka
        $currencies['BZD'] = array(2,'.',',');          //  Belize Dollar
        $currencies['BMD'] = array(2,'.',',');          //  Bermudian Dollar
        $currencies['BOB'] = array(2,'.',',');          //  Bolivia, Boliviano
        $currencies['BAM'] = array(2,'.',',');          //  Bosnia and Herzegovina, Convertible Marks
        $currencies['BWP'] = array(2,'.',',');          //  Botswana, Pula
        $currencies['BRL'] = array(2,',','.');          //  Brazilian Real
        $currencies['BND'] = array(2,'.',',');          //  Brunei Dollar
        $currencies['CAD'] = array(2,'.',',');          //  Canadian Dollar
        $currencies['KYD'] = array(2,'.',',');          //  Cayman Islands Dollar
        $currencies['CLP'] = array(0,'','.');           //  Chilean Peso
        $currencies['CNY'] = array(2,'.',',');          //  China Yuan Renminbi
        $currencies['COP'] = array(2,',','.');          //  Colombian Peso
        $currencies['CRC'] = array(2,',','.');          //  Costa Rican Colon
        $currencies['HRK'] = array(2,',','.');          //  Croatian Kuna
        $currencies['CUC'] = array(2,'.',',');          //  Cuban Convertible Peso
        $currencies['CUP'] = array(2,'.',',');          //  Cuban Peso
        $currencies['CYP'] = array(2,'.',',');          //  Cyprus Pound
        $currencies['CZK'] = array(2,'.',',');          //  Czech Koruna
        $currencies['DKK'] = array(2,',','.');          //  Danish Krone
        $currencies['DOP'] = array(2,'.',',');          //  Dominican Peso
        $currencies['XCD'] = array(2,'.',',');          //  East Caribbean Dollar
        $currencies['EGP'] = array(2,'.',',');          //  Egyptian Pound
        $currencies['SVC'] = array(2,'.',',');          //  El Salvador Colon
        $currencies['ATS'] = array(2,',','.');          //  Euro
        $currencies['BEF'] = array(2,',','.');          //  Euro
        $currencies['DEM'] = array(2,',','.');          //  Euro
        $currencies['EEK'] = array(2,',','.');          //  Euro
        $currencies['ESP'] = array(2,',','.');          //  Euro
        $currencies['EUR'] = array(2,',','.');          //  Euro
        $currencies['FIM'] = array(2,',','.');          //  Euro
        $currencies['FRF'] = array(2,',','.');          //  Euro
        $currencies['GRD'] = array(2,',','.');          //  Euro
        $currencies['IEP'] = array(2,',','.');          //  Euro
        $currencies['ITL'] = array(2,',','.');          //  Euro
        $currencies['LUF'] = array(2,',','.');          //  Euro
        $currencies['NLG'] = array(2,',','.');          //  Euro
        $currencies['PTE'] = array(2,',','.');          //  Euro
        $currencies['GHC'] = array(2,'.',',');          //  Ghana, Cedi
        $currencies['GIP'] = array(2,'.',',');          //  Gibraltar Pound
        $currencies['GTQ'] = array(2,'.',',');          //  Guatemala, Quetzal
        $currencies['HNL'] = array(2,'.',',');          //  Honduras, Lempira
        $currencies['HKD'] = array(2,'.',',');          //  Hong Kong Dollar
        $currencies['HUF'] = array(0,'','.');           //  Hungary, Forint
        $currencies['ISK'] = array(0,'','.');           //  Iceland Krona
        $currencies['INR'] = array(2,'.',',');          //  Indian Rupee
        $currencies['IDR'] = array(2,',','.');          //  Indonesia, Rupiah
        $currencies['IRR'] = array(2,'.',',');          //  Iranian Rial
        $currencies['JMD'] = array(2,'.',',');          //  Jamaican Dollar
        $currencies['JPY'] = array(0,'',',');           //  Japan, Yen
        $currencies['JOD'] = array(3,'.',',');          //  Jordanian Dinar
        $currencies['KES'] = array(2,'.',',');          //  Kenyan Shilling
        $currencies['KWD'] = array(3,'.',',');          //  Kuwaiti Dinar
        $currencies['LVL'] = array(2,'.',',');          //  Latvian Lats
        $currencies['LBP'] = array(0,'',' ');           //  Lebanese Pound
        $currencies['LTL'] = array(2,',',' ');          //  Lithuanian Litas
        $currencies['MKD'] = array(2,'.',',');          //  Macedonia, Denar
        $currencies['MYR'] = array(2,'.',',');          //  Malaysian Ringgit
        $currencies['MTL'] = array(2,'.',',');          //  Maltese Lira
        $currencies['MUR'] = array(0,'',',');           //  Mauritius Rupee
        $currencies['MXN'] = array(2,'.',',');          //  Mexican Peso
        $currencies['MZM'] = array(2,',','.');          //  Mozambique Metical
        $currencies['NPR'] = array(2,'.',',');          //  Nepalese Rupee
        $currencies['ANG'] = array(2,'.',',');          //  Netherlands Antillian Guilder
        $currencies['ILS'] = array(2,'.',',');          //  New Israeli Shekel
        $currencies['TRY'] = array(2,'.',',');          //  New Turkish Lira
        $currencies['NZD'] = array(2,'.',',');          //  New Zealand Dollar
        $currencies['NOK'] = array(2,',','.');          //  Norwegian Krone
        $currencies['PKR'] = array(2,'.',',');          //  Pakistan Rupee
        $currencies['PEN'] = array(2,'.',',');          //  Peru, Nuevo Sol
        $currencies['UYU'] = array(2,',','.');          //  Peso Uruguayo
        $currencies['PHP'] = array(2,'.',',');          //  Philippine Peso
        $currencies['PLN'] = array(2,'.',' ');          //  Poland, Zloty
        $currencies['GBP'] = array(2,'.',',');          //  Pound Sterling
        $currencies['OMR'] = array(3,'.',',');          //  Rial Omani
        $currencies['RON'] = array(2,',','.');          //  Romania, New Leu
        $currencies['ROL'] = array(2,',','.');          //  Romania, Old Leu
        $currencies['RUB'] = array(2,',','.');          //  Russian Ruble
        $currencies['SAR'] = array(2,'.',',');          //  Saudi Riyal
        $currencies['SGD'] = array(2,'.',',');          //  Singapore Dollar
        $currencies['SKK'] = array(2,',',' ');          //  Slovak Koruna
        $currencies['SIT'] = array(2,',','.');          //  Slovenia, Tolar
        $currencies['ZAR'] = array(2,'.',' ');          //  South Africa, Rand
        $currencies['KRW'] = array(0,'',',');           //  South Korea, Won
        $currencies['SZL'] = array(2,'.',', ');         //  Swaziland, Lilangeni
        $currencies['SEK'] = array(2,',','.');          //  Swedish Krona
        $currencies['CHF'] = array(2,'.','\'');         //  Swiss Franc 
        $currencies['TZS'] = array(2,'.',',');          //  Tanzanian Shilling
        $currencies['THB'] = array(2,'.',',');          //  Thailand, Baht
        $currencies['TOP'] = array(2,'.',',');          //  Tonga, Paanga
        $currencies['AED'] = array(2,'.',',');          //  UAE Dirham
        $currencies['UAH'] = array(2,',',' ');          //  Ukraine, Hryvnia
        $currencies['USD'] = array(2,'.',',');          //  US Dollar
        $currencies['VUV'] = array(0,'',',');           //  Vanuatu, Vatu
        $currencies['VEF'] = array(2,',','.');          //  Venezuela Bolivares Fuertes
        $currencies['VEB'] = array(2,',','.');          //  Venezuela, Bolivar
        $currencies['VND'] = array(0,'','.');           //  Viet Nam, Dong
        $currencies['ZWD'] = array(2,'.',' ');          //  Zimbabwe Dollar

        if ($code == "INR"){
            return self::formatinr($money);
        } else {
            return number_format($money, $currencies[$code][0], $currencies[$code][1], $currencies[$code][2]);
        }
    }
    
    function formatINR($input){
        //CUSTOM FUNCTION TO GENERATE ##,##,###.##
        $dec = "";
        $pos = strpos($input, ".");
        if ($pos === false){
            //no decimals   
        } else {
            //decimals
            $dec = substr(round(substr($input,$pos),2),1);
            $input = substr($input,0,$pos);
        }
        $num = substr($input,-3); //get the last 3 digits
        $input = substr($input,0, -3); //omit the last 3 digits already stored in $num
        while (strlen($input) > 0) //loop the process - further get digits 2 by 2
        {
            $num = substr($input,-2).",".$num;
            $input = substr($input,0,-2);
        }
        return $num . $dec;
    }
    
    public static function replaceInquiryFormToken($searhing_in, $replacement) {

        foreach ($replacement as $k => $v) {
            if (!empty(self::$owner_tokens[$k])) {
                $token = self::$owner_tokens[$k];
                $searhing_in = str_replace($token, $v, $searhing_in);
            }
        }

        return $searhing_in;
    }

    public static function replaceToken($searhing_in, $replacement) {

        foreach ($replacement as $k => $v) {
            if (!empty(self::$tokens[$k])) {
                $token = self::$tokens[$k];
                $searhing_in = str_replace($token, $v, $searhing_in);
            }
        }

        return $searhing_in;
    }

    public static function replaceInvoiceToken($searhing_in, $replacement) {

        foreach ($replacement as $k => $v) {
            if (!empty(self::$invoice_tokens[$k])) {
                $token = self::$invoice_tokens[$k];
                $searhing_in = str_replace($token, $v, $searhing_in);
            }
        }

        return $searhing_in;
    }

    public static function incrementalHash($len = 10) {

        return rand(pow(10, $len - 1), pow(10, $len) - 1);
    }

    public static function currencyConverter($currency_from, $currency_to, $currency_input) {
        /* $yql_base_url = "http://query.yahooapis.com/v1/public/yql";
          $yql_query = 'select * from yahoo.finance.xchange where pair in ("' . $currency_from . $currency_to . '")';
          $yql_query_url = $yql_base_url . "?q=" . urlencode($yql_query);
          $yql_query_url .= "&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys";
          $yql_session = curl_init($yql_query_url);
          curl_setopt($yql_session, CURLOPT_RETURNTRANSFER, true);
          $yqlexec = curl_exec($yql_session);
          $yql_json = json_decode($yqlexec, true);
          var_dump($yql_json);
          $currency_output = (float) $currency_input * $yql_json['query']['results']['rate']['Rate'];

          return $currency_output; */

        /* modified: changed, we not using realtime access really slow
         * so we change using file CONFIG_PATH . 'exchange.rate.php'
         * instead which refreshed daily
        $url = 'http://finance.yahoo.com/d/quotes.csv?e=.csv&f=sl1d1t1&s=' . $currency_from . $currency_to . '=X';
        $handle = fopen($url, 'r');

        if ($handle) {
            $result = fgets($handle, 4096);
            fclose($handle);
        }

        $allData = explode(',', $result);
        $currencyValue = $allData[1];

        return $currency_input * $currencyValue;
         * 
         */
        $exchange_rate_filename = CONFIG_PATH . 'exchange.rate.php';
        if (!file_exists($exchange_rate_filename)) {
            $url = 'http://individualbali.com/cal/cron/daily-exchange-rate.php';
            $curl_handle = curl_init();
            curl_setopt($curl_handle, CURLOPT_URL, $url);
            curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl_handle, CURLOPT_USERAGENT, 'IBH Calendar');
            curl_exec($curl_handle);
            curl_close($curl_handle);
        }
        require $exchange_rate_filename;
        if (isset($exchange_rate["$currency_from$currency_to"])) {
            return $currency_input * $exchange_rate["$currency_from$currency_to"];
        } else {
            return 0;
        }
    }

}
?>