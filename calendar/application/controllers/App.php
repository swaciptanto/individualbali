<?php

require_once FRAMEWORK_PATH . 'Controller.class.php';

class App extends Controller {

    var $models = array();

    function __construct() {

        Object::loadFiles('Model', array('Languages', 'Local'));
        $LanguagesModel = new LanguagesModel();
        $LocalModel = new LocalModel();

        $this->tpl['languages'] = $LanguagesModel->getAll(null, 'order');

        foreach ($this->tpl['languages'] as $k => $v) {
            $this->tpl['local'][$v['id']] = $LocalModel->getAll(array('language_id' => $v['id']));
        }

        $default_language = $LanguagesModel->getAll(array('isdefault' => 1), 'order');
        $select_language = $this->getLanguage();

        $language = $LanguagesModel->getAll(array('id' => $select_language['id']), 'order');

        if (empty($language)) {
            $this->setLanguage($default_language[0]);
        }

        $this->tpl['default_language'] = $default_language[0];

        Object::loadFiles('Model', 'Option');
        $OptionModel = new OptionModel();

        $this->tpl['option_arr_values'] = $OptionModel->getAllPairValues();
        date_default_timezone_set($this->tpl['option_arr_values']['timezone']);
    }
    
    function getClient() {
        $client = new Google_Client();
        $client->setApplicationName(APPLICATION_NAME);
        $client->setScopes(SCOPES);
        $client->setAuthConfigFile(CLIENT_SECRET_PATH);
        //$client->setClientId($option_arr_values['client_id']);
        //$client->setClientSecret($option_arr_values['client_secret']);
        $client->setAccessType('offline');

        // Load previously authorized credentials from a file.
        $credentialsPath = $this->expandHomeDirectory(CREDENTIALS_PATH);
        if (file_exists($credentialsPath)) {
            $accessToken = file_get_contents($credentialsPath);
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            //$authCode = '4/3ZQKgIWKDZKQR0MCoCfFj6quIchjLzfxSy41rkzd-vA';
            // Exchange authorization code for an access token.
            $accessToken = $client->authenticate(AUTH_CODE);

            // Store the credentials to disk.
            if (!file_exists(dirname($credentialsPath))) {
                mkdir(dirname($credentialsPath), 0700, true);
            }
            file_put_contents($credentialsPath, $accessToken);
            printf("Credentials saved to %s\n", $credentialsPath);
        }
        $client->setAccessToken($accessToken);

        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {
            $client->refreshToken($client->getRefreshToken());
            file_put_contents($credentialsPath, $client->getAccessToken());
        }
        return $client;
    }
    
    function expandHomeDirectory($path) {
        $homeDirectory = getenv('HOME');
        if (empty($homeDirectory)) {
            $homeDirectory = getenv("HOMEDRIVE") . getenv("HOMEPATH");
        }
        return str_replace('~', realpath($homeDirectory), $path);
    }


    function isUser() {
        return $this->getRoleId() == 2;
    }

    function calclateBookingPrice($params) {
        //modified: add new model
        Object::loadFiles('Model', array('Price', 'Calendar', 'Extra', 'Option', 'Discount', 'DrupalRateTax'));
        $PriceModel = new PriceModel();
        $CalendarModel = new CalendarModel();
        $ExtraModel = new ExtraModel();
        $DiscountModel = new DiscountModel();
        $OptionModel = new OptionModel();
        $DrupalRateTaxModel = new DrupalRateTaxModel();
        
        $option_arr = $OptionModel->getAllPairValues(array('calendar_id' => $params['calendar_id']));

        $result = array(
            'calendars_price' => 0,
            'extra_price' => 0,
            'discount' => 0,
            'total' => 0,
            'tax' => 0,
            'security' => 0,
            'deposit' => 0,
            'formated_calendars_price' => Util::currenctFormat($option_arr['currency'], 0),
            'formated_extra_price' => Util::currenctFormat($option_arr['currency'], 0),
            'formated_discount' => Util::currenctFormat($option_arr['currency'], 0),
            'formated_total' => Util::currenctFormat($option_arr['currency'], 0),
            'formated_tax' => Util::currenctFormat($option_arr['currency'], 0),
            'formated_security' => Util::currenctFormat($option_arr['currency'], 0),
            'formated_deposit' => Util::currenctFormat($option_arr['currency'], 0)
            );

        if (empty($params['calendar_id']) || empty($params['to_date']) || empty($params['from_date'])) {
            return $result;
        }

        $person = 0;

        if (empty($params['adults'])) {
            $params['adults'] = 0;
        }

        if (empty($params['children'])) {
            $params['children'] = 0;
        }

        $person = $params['adults'] + $params['children'];
        $nights = ceil(($params['to_date'] - $params['from_date']) / 86400);

        $price_arr = array();
        $arr = array();

        $opts = array();
        $opts['id'] = $params['calendar_id'];

        $calendar_id = array();

        $arr = array();

        $id = $params['calendar_id'];

        //search for price that match with adults and children 
        $arr = $PriceModel->getPrices($params, $id);

        if (empty($arr) || count($arr) == 0) {
            //search for default price if not price that match with adults and children 

            $arr = $PriceModel->getDefaultPrices($params, $id);
        }

        //foreach all room type prices, beccaouse if not price for any day, ather price may be will,
        //if one or two prices for days it will be replace
        foreach ($arr as $k => $v) {
            for ($i = $params['from_date']; $i <= $params['to_date']; $i = strtotime('+1 day', $i)) {
                if ($i >= $v['from_date'] && $i <= $v['to_date']) {

                    switch (date('N', $i)) {
                        case 1:
                            $price_arr[$i]['price'] = $v['monday'];
                            break;
                        case 2:
                            $price_arr[$i]['price'] = $v['tuesday'];
                            break;
                        case 3:
                            $price_arr[$i]['price'] = $v['wednesday'];
                            break;
                        case 4:
                            $price_arr[$i]['price'] = $v['thursday'];
                            break;
                        case 5:
                            $price_arr[$i]['price'] = $v['friday'];
                            break;
                        case 6:
                            $price_arr[$i]['price'] = $v['saturday'];
                            break;
                        case 7:
                            $price_arr[$i]['price'] = $v['sunday'];
                            break;
                    }
                }
            }
        }

        ksort($price_arr);

        if (!empty($params['promo_code'])) {

            $discount_arr = $DiscountModel->getDiscount($params);
        }

        if ($option_arr['based_on'] == 'night') {
            array_pop($price_arr);
        }
        $price = 0;

        foreach ($price_arr as $k => $v) {

            if (!empty($discount_arr[$k])) {
                switch ($discount_arr[$k]['type']) {
                    case 'price':
                        $result['discount'] += $discount_arr[$k]['discount'];
                        break;
                    case 'percent':
                        $result['discount'] += $v['price'] * $discount_arr[$k]['discount'] / 100;
                        break;
                }
            }
            $price += $v['price'];
        }


        if (!empty($price)) {

            $result['calendars_price'] = $price;
            $result['total'] = $price - $result['discount'];

            $extra_price = 0;
            if (!empty($params['extra_id'])) {
                $extra_id = array();

                foreach ($params['extra_id'] as $id) {
                    $extra_id[] = $id;
                }

                $opts = array();
                $opts['id'] = $extra_id;

                $extras_arr = $ExtraModel->getAll($opts);

                foreach ($extras_arr as $extra) {
                    switch ($extra['per']) {
                        case 'booking':
                            switch ($extra['type']) {
                                case 'price':
                                    $extra_price += $extra['price'];
                                    break;
                                case 'percent':
                                    $extra_price += ($result['total'] * $extra['price']) / 100;
                                    break;
                            }
                            break;
                        case 'day':
                            switch ($extra['type']) {
                                case 'price':
                                    $extra_price += $extra['price'] * $nights;
                                    break;
                                case 'percent':
                                    $extra_price += ($result['total'] * $extra['price'] * $nights) / 100;
                                    break;
                            }
                            break;
                        case 'day_person':
                            switch ($extra['type']) {
                                case 'price':
                                    $extra_price += $extra['price'] * $nights * $person;
                                    break;
                                case 'percent':
                                    $extra_price += ($result['total'] * $extra['price'] * $nights * $person) / 100;
                                    break;
                            }
                            break;
                        case 'person':
                            switch ($extra['type']) {
                                case 'price':
                                    $extra_price += $extra['price'] * $person;
                                    break;
                                case 'percent':
                                    $extra_price += ($result['total'] * $extra['price'] * $person) / 100;
                                    break;
                            }
                            break;
                    }
                }
            }

            $result['extra_price'] = $extra_price;
            $result['total'] += $extra_price;


            /* modified: change get tax by using drupal
            if (!empty($option_arr['tax'])) {
                switch ($option_arr['tax_type']) {
                    case 'price':
                        $result['tax'] = $option_arr['tax'];
                        $result['total'] = $result['total'] + $result['tax'];
                        break;
                    case 'percent':
                        $result['tax'] = ($result['total'] * $option_arr['tax']) / 100;
                        $result['total'] = $result['total'] + $result['tax'];
                        break;
                }
            }
             * 
             */

            //modified: add get tax from drupal
            $calendar_data = $CalendarModel->get($params['calendar_id']);
            if ((int)$calendar_data['villa_node_id'] > 0) {
                //notes: field_text_value is in percent (e.g.: 10, 15, etc.)
                $villa_tax = $DrupalRateTaxModel->get($calendar_data['villa_node_id'])['field_tax_value'];
                $result['tax'] = ($result['total'] * $villa_tax) / 100;
                $result['total_with_tax'] = $result['total'] + $result['tax'];
            }

            if (!empty($option_arr['deposit'])) {
                switch ($option_arr['deposit_type']) {
                    case 'price':
                        $result['deposit'] = $option_arr['deposit'];
                        //$result['total'] = $result['total'] - $result['deposit'];
                        break;
                    case 'percent':
                        $result['deposit'] = ($result['total'] * $option_arr['deposit']) / 100;
                        // $result['total'] = $result['total'] - $result['deposit'];
                        break;
                }
            }
            $result['formated_discount'] = Util::currenctFormat($option_arr['currency'], $result['discount']);
            $result['formated_extra_price'] = Util::currenctFormat($option_arr['currency'], $result['extra_price']);
            $result['formated_deposit'] = Util::currenctFormat($option_arr['currency'], $result['deposit']);
            $result['formated_total'] = Util::currenctFormat($option_arr['currency'], $result['total']);
            $result['formated_total_with_tax'] = Util::currenctFormat($option_arr['currency'], $result['total_with_tax']);
            $result['formated_calendars_price'] = Util::currenctFormat($option_arr['currency'], $result['calendars_price']);
            $result['formated_tax'] = Util::currenctFormat($option_arr['currency'], $result['tax']);
        }
        return $result;
    }

    function getNotAvailabilityCalendarArray($arr = array()) {
        Object::loadFiles('Model', array('Calendar', 'Booking', 'Blocking', 'Option'));
        $CalendarModel = new CalendarModel();
        $BookingModel = new BookingModel();
        $OptionModel = new OptionModel();
        $BlockingModel = new BlockingModel();

        $this->option_arr = $OptionModel->getAllPairValues();
        $option_arr_values = $this->option_arr;

        if ($option_arr_values['based_on'] != 'night') {

            if (!empty($arr['id'])) {
                $sql = "SELECT id FROM " . $CalendarModel->getTable() . " as t1 
                   WHERE (
                    (SELECT CT.limit FROM " . $CalendarModel->getTable() . " as CT WHERE t1.id=CT.id)
                    <= 
                    (SELECT SUM(BT.calendar_id) FROM " . $BookingModel->getTable() . " as BT
                    WHERE 
                    (
                    (BT.date_from >= '" . $arr['date_from'] . "' AND BT.date_from <= '" . $arr['date_to'] . "' ) 
                     OR (BT.date_to >= '" . $arr['date_from'] . "' AND BT.date_to <= '" . $arr['date_to'] . "' )
                     OR (BT.date_to >= '" . $arr['date_to'] . "' AND BT.date_from <= '" . $arr['date_to'] . "' AND BT.date_from <= '" . $arr['date_from'] . "')) 
                     AND t1.id=BT.calendar_id AND BT.id !='" . $arr['id'] . "' GROUP BY BT.calendar_id))
                GROUP BY id";
            } else {
                $sql = "SELECT id FROM " . $CalendarModel->getTable() . " as t1 
                   WHERE (
                    (SELECT CT.limit FROM " . $CalendarModel->getTable() . " as CT WHERE t1.id=CT.id)
                    <= 
                    (SELECT SUM(BT.calendar_id) FROM " . $BookingModel->getTable() . " as BT
                    WHERE 
                    (
                    (BT.date_from >= '" . $arr['date_from'] . "' AND BT.date_from <= '" . $arr['date_to'] . "' ) 
                     OR (BT.date_to >= '" . $arr['date_from'] . "' AND BT.date_to <= '" . $arr['date_to'] . "' )
                     OR (BT.date_to >= '" . $arr['date_to'] . "' AND BT.date_from <= '" . $arr['date_to'] . "' AND BT.date_from <= '" . $arr['date_from'] . "')) 
                     AND t1.id=BT.calendar_id GROUP BY BT.calendar_id))
                GROUP BY id";
            }
        } else {
            if (!empty($arr['id'])) {
                $sql = "SELECT id  FROM " . $CalendarModel->getTable() . " as t1     
                WHERE (
                        (SELECT CT.limit FROM " . $CalendarModel->getTable() . " as CT WHERE t1.id=CT.id)
                        <= 
                        (
                            SELECT SUM(BT.calendar_id) FROM " . $BookingModel->getTable() . " as BT
                            WHERE 
                                ((BT.date_from >= '" . $arr['date_from'] . "' AND BT.date_from < '" . $arr['date_to'] . "' ) 
                                  OR (BT.date_from <= '" . $arr['date_from'] . "' AND BT.date_to > '" . $arr['date_from'] . "' )                                  
                                  OR (BT.date_to > '" . $arr['date_from'] . "' AND BT.date_to < '" . $arr['date_to'] . "' )
                                  OR (BT.date_to = '" . $arr['date_to'] . "' AND BT.date_from = '" . $arr['date_from'] . "' ) 
                                  OR (BT.date_to > '" . $arr['date_to'] . "' AND BT.date_from < '" . $arr['date_to'] . "' AND BT.date_from < '" . $arr['date_from'] . "')                        
                                ) 
                            AND t1.id=BT.calendar_id AND BT.id !='" . $arr['id'] . "'  GROUP BY BT.calendar_id 
                        ))
                GROUP BY id";
            } else {
                $sql = "SELECT id  FROM " . $CalendarModel->getTable() . " as t1     
                WHERE (
                        (SELECT CT.limit FROM " . $CalendarModel->getTable() . " as CT WHERE t1.id=CT.id)
                        <= 
                        (
                            SELECT SUM(BT.calendar_id) FROM " . $BookingModel->getTable() . " as BT
                            WHERE 
                                ((BT.date_from >= '" . $arr['date_from'] . "' AND BT.date_from < '" . $arr['date_to'] . "' ) 
                                  OR (BT.date_from <= '" . $arr['date_from'] . "' AND BT.date_to > '" . $arr['date_from'] . "' )                                  
                                  OR (BT.date_to > '" . $arr['date_from'] . "' AND BT.date_to < '" . $arr['date_to'] . "' )
                                  OR (BT.date_to = '" . $arr['date_to'] . "' AND BT.date_from = '" . $arr['date_from'] . "' ) 
                                  OR (BT.date_to > '" . $arr['date_to'] . "' AND BT.date_from < '" . $arr['date_to'] . "' AND BT.date_from < '" . $arr['date_from'] . "')                        
                                ) 
                            AND t1.id=BT.calendar_id GROUP BY BT.calendar_id 
                        ))
                GROUP BY id";
            }
        }

        $result_arr = $BookingModel->execute($sql);

        if ($option_arr_values['based_on'] != 'night') {
            $sql = "SELECT t3.id
                FROM " . $BlockingModel->getTable() . " as t1 
                LEFT JOIN " . $CalendarModel->getTable() . " as t3  ON t1.calendar_id = t3.id    
                WHERE 1=1 AND
                    ((t1.from_date <= '" . $arr['date_from'] . "' AND t1.to_date >= '" . $arr['date_to'] . "' AND t1.to_date >= '" . $arr['date_from'] . "' )
                    OR (t1.from_date >= '" . $arr['date_from'] . "' AND t1.from_date <= '" . $arr['date_to'] . "' )
                    OR (t1.to_date >= '" . $arr['date_from'] . "' AND t1.to_date <= '" . $arr['date_to'] . "' ))";
        } else {
            $sql = "SELECT t3.id
                FROM " . $BlockingModel->getTable() . " as t1 
                LEFT JOIN " . $CalendarModel->getTable() . " as t3  ON t1.calendar_id = t3.id    
                WHERE 1=1 AND
                ((t1.from_date <= '" . $arr['date_from'] . "' AND t1.to_date >= '" . $arr['date_to'] . "' AND t1.to_date >= '" . $arr['date_from'] . "' )
                OR (t1.from_date > '" . $arr['date_from'] . "' AND t1.from_date < '" . $arr['date_to'] . "' )
                OR (t1.to_date > '" . $arr['date_from'] . "' AND t1.to_date < '" . $arr['date_to'] . "' )
                OR (t1.from_date = '" . $arr['date_from'] . "' AND t1.to_date = '" . $arr['date_to'] . "' ))";
        }

        $block_arr = $BlockingModel->execute($sql);

        return array_merge($block_arr, $result_arr);
    }

    function getAvailabilityTypeArray($arr = array()) {
        Object::loadFiles('Model', array('Calendar', 'BookingCalendar', 'Booking', 'Blocking', 'Option'));
        $CalendarModel = new CalendarModel();
        $BookingCalendarModel = new BookingCalendarModel();
        $BookingModel = new BookingModel();
        $BlockingModel = new BlockingModel();
        $OptionModel = new OptionModel();

        $this->option_arr = $OptionModel->getAllPairValues();
        $option_arr_values = $this->option_arr;

        if ($option_arr_values['based_on'] != 'night') {

            $sql = "SELECT t3.type_id  FROM " . $BookingCalendarModel->getTable() . " as t1 
                LEFT JOIN " . $BookingModel->getTable() . " as t2  ON t1.booking_id = t2.id
                LEFT JOIN " . $CalendarModel->getTable() . " as t3  ON t1.calendar_id = t3.id    
                WHERE ((t2.date_from >= '" . $arr['date_from'] . "' AND t2.date_from <= '" . $arr['date_to'] . "' ) 
                    OR (t2.date_to >= '" . $arr['date_from'] . "' AND t2.date_to <= '" . $arr['date_to'] . "' )
                    OR (t2.date_to >= '" . $arr['date_to'] . "' AND t2.date_from <= '" . $arr['date_to'] . "' AND t2.date_from <= '" . $arr['date_from'] . "')                                                
                    )  
                        AND
                        (SELECT SUM(RT.count) FROM " . $CalendarModel->getTable() . " as RT WHERE t3.type_id=RT.type_id GROUP BY RT.type_id)
                        <= 
                        (
                            SELECT COUNT(BRT.calendar_id) FROM " . $BookingCalendarModel->getTable() . " as BRT LEFT JOIN " . $BookingModel->getTable() . " as BT ON BRT.booking_id = BT.id LEFT JOIN " . $RoomModel->getTable() . " as RT ON BRT.room_id = RT.id  LEFT JOIN " . $RoomTypeModel->getTable() . " as RTT ON RT.type_id = RTT.id
                            WHERE 
                                ((BT.date_from >= '" . $arr['date_from'] . "' AND BT.date_from <= '" . $arr['date_to'] . "' ) 
                                 OR (BT.date_to >= '" . $arr['date_from'] . "' AND BT.date_to <= '" . $arr['date_to'] . "' )
                                 OR (BT.date_to >= '" . $arr['date_to'] . "' AND BT.date_from <= '" . $arr['date_to'] . "' AND BT.date_from <= '" . $arr['date_from'] . "')                         
                                 ) 
                            AND t3.type_id=RT.type_id GROUP BY RT.type_id 
                        )
                GROUP BY t3.type_id";
        } else {

            $sql = "SELECT t3.type_id  FROM " . $BookingCalendarModel->getTable() . " as t1 
                LEFT JOIN " . $BookingModel->getTable() . " as t2  ON t1.booking_id = t2.id
                LEFT JOIN " . $CalendarModel->getTable() . " as t3  ON t1.calendar_id = t3.id    
                WHERE ((t2.date_from >= '" . $arr['date_from'] . "' AND t2.date_from < '" . $arr['date_to'] . "' ) 
                    OR (t2.date_from <= '" . $arr['date_from'] . "' AND t2.date_to > '" . $arr['date_from'] . "' )
                    OR (t2.date_to >= '" . $arr['date_from'] . "' AND t2.date_to < '" . $arr['date_to'] . "' )
                    OR (t2.date_to = '" . $arr['date_to'] . "' AND t2.date_from = '" . $arr['date_from'] . "' )
                    OR (t2.date_to > '" . $arr['date_to'] . "' AND t2.date_from < '" . $arr['date_to'] . "' AND t2.date_from <= '" . $arr['date_from'] . "')                        
                    )  
                        AND
                        (SELECT SUM(RT.count) FROM " . $CalendarModel->getTable() . " as RT WHERE t3.type_id=RT.type_id GROUP BY RT.type_id)
                        <= 
                        (
                            SELECT COUNT(BRT.calendar_id) FROM " . $BookingCalendarModel->getTable() . " as BRT LEFT JOIN " . $BookingModel->getTable() . " as BT ON BRT.booking_id = BT.id LEFT JOIN " . $RoomModel->getTable() . " as RT ON BRT.room_id = RT.id  LEFT JOIN " . $RoomTypeModel->getTable() . " as RTT ON RT.type_id = RTT.id
                            WHERE 
                                ((BT.date_from >= '" . $arr['date_from'] . "' AND BT.date_from < '" . $arr['date_to'] . "' ) 
                                  OR (BT.date_from <= '" . $arr['date_from'] . "' AND BT.date_to > '" . $arr['date_from'] . "' )                                  
                                  OR (BT.date_to > '" . $arr['date_from'] . "' AND BT.date_to < '" . $arr['date_to'] . "' )
                                  OR (BT.date_to = '" . $arr['date_to'] . "' AND BT.date_from = '" . $arr['date_from'] . "' ) 
                                  OR (BT.date_to > '" . $arr['date_to'] . "' AND BT.date_from < '" . $arr['date_to'] . "' AND BT.date_from < '" . $arr['date_from'] . "')                        
                                ) 
                            AND t3.type_id=RT.type_id GROUP BY RT.type_id 
                        )
                GROUP BY t3.type_id";
        }

        $result_arr = $BookingCalendarModel->execute($sql);

        if ($option_arr_values['based_on'] != 'night') {
            $sql = "SELECT t3.type_id
                FROM " . $BlockingModel->getTable() . " as t1 
                LEFT JOIN " . $CalendarModel->getTable() . " as t3  ON t1.calendar_id = t3.id    
                WHERE 1=1 AND
                    ((t1.from_date <= '" . $arr['date_from'] . "' AND t1.to_date >= '" . $arr['date_to'] . "' AND t1.to_date >= '" . $arr['date_from'] . "' )
                    OR (t1.from_date >= '" . $arr['date_from'] . "' AND t1.from_date <= '" . $arr['date_to'] . "' )
                    OR (t1.to_date >= '" . $arr['date_from'] . "' AND t1.to_date <= '" . $arr['date_to'] . "' ))";
        } else {
            $sql = "SELECT t3.type_id
                FROM " . $BlockingModel->getTable() . " as t1 
                LEFT JOIN " . $CalendarModel->getTable() . " as t3  ON t1.calendar_id = t3.id    
                WHERE 1=1 AND
                ((t1.from_date <= '" . $arr['date_from'] . "' AND t1.to_date >= '" . $arr['date_to'] . "' AND t1.to_date >= '" . $arr['date_from'] . "' )
                OR (t1.from_date > '" . $arr['date_from'] . "' AND t1.from_date < '" . $arr['date_to'] . "' )
                OR (t1.to_date > '" . $arr['date_from'] . "' AND t1.to_date < '" . $arr['date_to'] . "' )
                OR (t1.from_date = '" . $arr['date_from'] . "' AND t1.to_date = '" . $arr['date_to'] . "' ))";
        }

        $block_arr = $BlockingModel->execute($sql);

        return array_merge($block_arr, $result_arr);
    }

    function getAvailabilityCalendarCount() {
        Object::loadFiles('Model', array('Calendar'));
        $CalendarModel = new CalendarModel();

        $opts = array();

        $not_availability_calendar = $this->getNotAvailabilityCalendarArray(array('date_from' => Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $_POST['date_from']), 'date_to' => Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $_POST['date_to'])));

        $calendar_id = array();

        foreach ($not_availability_calendar as $v) {

            $calendar_id[] = $v['id'];
        }

        if (!empty($calendar_id)) {

            $opts["`t1`.`id` NOT IN ('" . implode("', '", $calendar_id) . "') AND 1 = ?"] = "1";
        }

        $calendars_arr = $CalendarModel->getAll($opts);

        if (!empty($calendars_arr)) {
            return count($calendars_arr);
        } else {
            return 0;
        }
    }

    function sendBookingEmails($id, $type, $group) {
        Object::loadFiles('Model', array('Option', 'Booking', 'Invoice', 'User', 'Calendar'));
        $OptionModel = new OptionModel();
        $BookingModel = new BookingModel();
        $InvoiceModel = new InvoiceModel();
        $CalendarModel = new CalendarModel();
        $UserModel = new UserModel();

        $opts = array();
        $opts['booking_id'] = $id;
        $invoice = $InvoiceModel->getAll($opts, 'id');

        $booking_details = $BookingModel->getBookingDetails($id);
        $calendar = $CalendarModel->get($booking_details['calendar_id']);
        $owner = $UserModel->get($calendar['user_id']);

        $option_arr = $OptionModel->getAllPairValues(array('calendar_id' => $booking_details['calendar_id']));

        $replacement = array();
        $replacement['id'] = $booking_details['id'];
        $replacement['title'] = $booking_details['title'];
        $replacement['first_name'] = $booking_details['first_name'];
        $replacement['second_name'] = $booking_details['second_name'];
        $replacement['phone'] = $booking_details['phone'];
        $replacement['email'] = $booking_details['email'];
        $replacement['company'] = $booking_details['company'];
        $replacement['address_1'] = $booking_details['address_1'];
        $replacement['address_2'] = $booking_details['address_2'];
        $replacement['city'] = $booking_details['city'];
        $replacement['state'] = $booking_details['state'];
        $replacement['zip'] = $booking_details['zip'];
        $replacement['country'] = $booking_details['country'];
        $replacement['fax'] = $booking_details['fax'];
        $replacement['male'] = $booking_details['male'];
        $replacement['additional'] = $booking_details['additional'];
        $replacement['adults'] = $booking_details['adults'];
        $replacement['children'] = $booking_details['children'];
        $replacement['nights'] = $booking_details['nights'];
        $replacement['date_from'] = $booking_details['date_from'];
        $replacement['date_to'] = $booking_details['date_to'];
        $replacement['calendars'] = $booking_details['calendar'];
        $replacement['extras'] = implode(',', $booking_details['extras']);
        $replacement['cc_type'] = $booking_details['cc_type'];
        $replacement['cc_num'] = $booking_details['cc_num'];
        $replacement['cc_code'] = $booking_details['cc_code'];
        $replacement['cc_exp_month'] = $booking_details['cc_exp_month'];
        $replacement['cc_exp_year'] = $booking_details['cc_exp_year'];
        $replacement['payment_method'] = $booking_details['payment_method'];
        $replacement['deposit'] = $booking_details['deposit'];
        $replacement['tax'] = $booking_details['tax'];
        $replacement['total'] = $booking_details['total'];
        $replacement['calendars_price'] = $booking_details['calendars_price'];
        $replacement['extra_price'] = $booking_details['extra_price'];
        $replacement['discount'] = $booking_details['discount'];
        $replacement['nights'] = $booking_details['nights'];

        switch ($type) {
            case 'create':
                switch ($group) {
                    case 'client':
                        $message = Util::replaceToken($option_arr['client_create_email_booking'], $replacement);
                        $subjetc = $option_arr['client_create_subject_booking'];
                        $to = $booking_details['email'];
                        break;
                    case 'admin':
                        $message = Util::replaceToken($option_arr['admin_create_email_booking'], $replacement);
                        $subjetc = $option_arr['admin_create_subject_booking'];
                        $to = $option_arr['notify_email'];
                        break;
                    case 'owner':
                        $message = Util::replaceToken($option_arr['owner_create_email_booking'], $replacement);
                        $subjetc = $option_arr['owner_create_subject_booking'];
                        $to = $owner['email'];
                        break;
                }
                break;
            case 'confirmation':
                switch ($group) {
                    case 'client':
                        $message = Util::replaceToken($option_arr['client_confirmation_email_booking'], $replacement);
                        $subjetc = $option_arr['client_confirmation_subject_booking'];
                        $to = $booking_details['email'];
                        break;
                    case 'admin':
                        $message = Util::replaceToken($option_arr['admin_confirmation_email_booking'], $replacement);
                        $subjetc = $option_arr['admin_confirmation_subject_booking'];
                        $to = $option_arr['notify_email'];
                        break;
                    case 'owner':
                        $message = Util::replaceToken($option_arr['owner_confirmation_email_booking'], $replacement);
                        $subjetc = $option_arr['owner_confirmation_subject_booking'];
                        $to = $owner['email'];
                        break;
                }
                break;
            case 'cancellation':
                switch ($group) {
                    case 'client':
                        $message = Util::replaceToken($option_arr['client_cancellation_email_booking'], $replacement);
                        $subjetc = $option_arr['client_cancellation_subject_booking'];
                        $to = $booking_details['email'];
                        break;
                    case 'admin':
                        $message = Util::replaceToken($option_arr['admin_cancellation_email_booking'], $replacement);
                        $subjetc = $option_arr['admin_cancellation_subject_booking'];
                        $to = $option_arr['notify_email'];
                        break;
                    case 'owner':
                        $message = Util::replaceToken($option_arr['owner_cancellation_email_booking'], $replacement);
                        $subjetc = $option_arr['owner_cancellation_subject_booking'];
                        $to = $owner['email'];
                        break;
                }
                break;
        }

        require_once APP_PATH . '/helpers/PHPMailer_5.2.4/class.phpmailer.php';

        try {
            $mail = new PHPMailer(true); //New instance, with exceptions enabled
            $mail->CharSet = "UTF-8";
//$mail->IsSendmail();  // tell the class to use Sendmail
            $mail->AddReplyTo($option_arr['notify_email'], "Admin");
            $mail->From = $option_arr['notify_email'];
            $mail->FromName = $option_arr['notify_email'];
            $to = $to;
            $mail->AddAddress($to);
            $mail->Subject = $subjetc;
            $mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
            $mail->WordWrap = 80; // set word wrap
            $mail->MsgHTML($message);
            if (!empty($invoice)) {
                $invoice_id = $invoice[0]['id'];
                if (is_file(INSTALL_PATH . UPLOAD_PATH . 'invoice/' . 'booking_' . $id . '_invoice_' . $invoice_id . '.pdf')) {
                    $mail->AddAttachment(INSTALL_PATH . UPLOAD_PATH . 'invoice/' . 'booking_' . $id . '_invoice_' . $invoice_id . '.pdf'); // attachment
                }
            }
            $mail->IsHTML(true); // send as HTML
            $mail->Send();
        } catch (phpmailerException $e) {
            //echo $e->errorMessage();
        }
    }
    
    function sendInquiryFormEmail($group){
        //modified: add new model
        Object::loadFiles('Model', array('Option', 'User', 'Calendar', 'DrupalEmailReservation'));
        $OptionModel = new OptionModel();
        $CalendarModel = new CalendarModel();
        $UserModel = new UserModel();

        $calendar = $CalendarModel->get($_GET['cid']);
        $owner = $UserModel->get($calendar['user_id']);

        $option_arr = $OptionModel->getAllPairValues(array('calendar_id' => $calendar['id']));

        $replacement = array();
        $replacement['startdate'] = $_POST['startdate'];
        $replacement['finishdate'] = $_POST['finishdate'];
        $replacement['phone'] = $_POST['country_phone_code'].$_POST['phone_number'];
        $replacement['message'] = $_POST['message'];
        $replacement['email'] = $_POST['email'];
        $replacement['name'] = $_POST['name'];
        $replacement['url'] = $calendar['url'];
        
        $_POST['from_date'] = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $_POST['startdate']);
        $_POST['to_date'] = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $_POST['finishdate']);
        $_POST['calendar_id'] = $_GET['cid'];
        
        $price = $this->calclateBookingPrice($_POST);

        $replacement['total'] = $price['formated_total'];

        switch ($group) {
            case 'client':
                $message = Util::replaceInquiryFormToken($option_arr['client_inquiry_form'], $replacement);
                $subjetc = $option_arr['client_inquiry_form_subject'];
                $to = $_POST['email'];
                break;
            case 'admin':
                $message = Util::replaceInquiryFormToken($option_arr['admin_inquiry_form'], $replacement);
                $subjetc = $option_arr['admin_inquiry_form_subject'];
                $to = $option_arr['notify_email'];
                break;
            case 'owner':
                $message = Util::replaceInquiryFormToken($option_arr['owner_inquiry_form'], $replacement);
                $subjetc = $option_arr['owner_inquiry_form_subject'];
                $to = $owner['email'];
                break;
            //modified: add new
            case 'reservation':
                $DrupalEmailReservation = new DrupalEmailReservationModel();
                $email_reservation_data = $DrupalEmailReservation->get($calendar['villa_node_id']);
                $message = Util::replaceInquiryFormToken($option_arr['owner_inquiry_form'], $replacement);
                $subjetc = $option_arr['owner_inquiry_form_subject'];
                //$to = $calendar['villa_reservation_email'];
                $to = $email_reservation_data['field_email_reservasi_value'];
                break;
        }
        $option_arr['notify_email'] = 'admin@individualbali.com';
        require_once APP_PATH . '/helpers/PHPMailer_5.2.4/class.phpmailer.php';

        try {
            //*/
            $mail = new PHPMailer(true); //New instance, with exceptions enabled
            $mail->CharSet = "UTF-8";
            //$mail->IsSendmail();  // tell the class to use Sendmail
            //modified: use SMTP
            $mail->IsSMTP();
            $mail->Host = "mail.individualbali.com";
            $mail->Port = 587;
            $mail->SMTPSecure = 'tls';
            $mail->Username = 'admin@individualbali.com';
            $mail->Password = 'Rian&Dewa';
            $mail->AddReplyTo($option_arr['notify_email'], "Admin");
            $mail->From = $option_arr['notify_email'];
            $mail->FromName = $option_arr['notify_email'];
            $mail->AddAddress($to);
            $mail->Subject = $subjetc;
            $mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
            $mail->WordWrap = 80; // set word wrap
            $mail->MsgHTML($message);
            $mail->IsHTML(true); // send as HTML
            $mail->Send();
        } catch (phpmailerException $e) {
            echo $e->errorMessage();
            /*
            echo "group: $group\n";
            echo "notify_email: $option_arr[notify_email]\n";
            echo "to: $to\n";
            echo "<pre>";var_dump($_POST);
            die();
             * 
             */
        }
    }

    function replaceMultyCalendarCSS() {
        header("Content-type: text/css");

        $css = file_get_contents(INSTALL_PATH . 'application/web/css/admin/gzMultyAbCalCalendar.css');

        $search = array(
            '{bg_past_dates}',
            '{color_past_dates}',
            '{bg_nav_month}',
            '{bg_nav_hover_month}',
            '{color_month}',
            '{bg_month}',
            '{month_size_past}',
            '{font_style_month}',
            '{font_famaly_month}',
            '{border_color}',
            '{border_widht}',
            '{color_legend}',
            '{font_size_legend}',
            '{font_famaly_legend}',
            '{font_style_legend}',
            '{color_week}',
            '{bg_week}',
            '{bg_booked}',
            '{color_booked}',
            '{font_size_booked}',
            '{font_famaly_booked}',
            '{font_style_booked}',
            '{bg_pending}',
            '{color_pending}',
            '{font_size_pending}',
            '{font_famaly_pending}',
            '{font_style_pending}',
            '{font_size_past}',
            '{font_famaly_past}',
            '{font_style_past}',
            '{bg_available}',
            '{color_available}',
            '{font_size_available}',
            '{font_famaly_available}',
            '{font_style_available}',
            '{bg_empty}',
            '{bg_selected}',
            '{calendarContainer}',
        );

        $replace = array(
            str_replace('#', '', $this->tpl['option_arr_values']['bg_past_dates']),
            str_replace('#', '', $this->tpl['option_arr_values']['color_past_dates']),
            str_replace('#', '', $this->tpl['option_arr_values']['bg_nav_month']),
            str_replace('#', '', $this->tpl['option_arr_values']['bg_nav_hover_month']),
            str_replace('#', '', $this->tpl['option_arr_values']['color_month']),
            str_replace('#', '', $this->tpl['option_arr_values']['bg_month']),
            $this->tpl['option_arr_values']['month_size_past'],
            $this->tpl['option_arr_values']['font_style_month'],
            $this->tpl['option_arr_values']['font_famaly_month'],
            str_replace('#', '', $this->tpl['option_arr_values']['border_color']),
            $this->tpl['option_arr_values']['border_widht'],
            str_replace('#', '', $this->tpl['option_arr_values']['color_legend']),
            $this->tpl['option_arr_values']['font_size_legend'],
            $this->tpl['option_arr_values']['font_famaly_legend'],
            $this->tpl['option_arr_values']['font_style_legend'],
            str_replace('#', '', $this->tpl['option_arr_values']['color_week']),
            str_replace('#', '', $this->tpl['option_arr_values']['bg_week']),
            str_replace('#', '', $this->tpl['option_arr_values']['bg_booked']),
            str_replace('#', '', $this->tpl['option_arr_values']['color_booked']),
            $this->tpl['option_arr_values']['font_size_booked'],
            $this->tpl['option_arr_values']['font_famaly_booked'],
            $this->tpl['option_arr_values']['font_style_booked'],
            str_replace('#', '', $this->tpl['option_arr_values']['bg_pending']),
            str_replace('#', '', $this->tpl['option_arr_values']['color_pending']),
            $this->tpl['option_arr_values']['font_size_pending'],
            $this->tpl['option_arr_values']['font_famaly_pending'],
            $this->tpl['option_arr_values']['font_style_pending'],
            $this->tpl['option_arr_values']['font_size_past'],
            $this->tpl['option_arr_values']['font_famaly_past'],
            $this->tpl['option_arr_values']['font_style_past'],
            str_replace('#', '', $this->tpl['option_arr_values']['bg_available']),
            str_replace('#', '', $this->tpl['option_arr_values']['color_available']),
            $this->tpl['option_arr_values']['font_size_available'],
            $this->tpl['option_arr_values']['font_famaly_available'],
            $this->tpl['option_arr_values']['font_style_available'],
            str_replace('#', '', $this->tpl['option_arr_values']['bg_empty']),
            str_replace('#', '', $this->tpl['option_arr_values']['bg_selected']),
            '#gz-abc-main-container-' . $_GET['cid']
        );

        echo str_replace($search, $replace, $css);
    }

    function getCSS() {
        header("Content-type: text/css");

        $css = file_get_contents(INSTALL_PATH . 'application/web/css/front/gzAbCalCalendar.css');

        Object::loadFiles('Model', 'Option');
        $OptionModel = new OptionModel();

        $cid = $_GET['cid'];
        $opts['calendar_id'] = $cid;
        $option_arr_values = $OptionModel->getAllPairValues($opts);

        $search = array(
            '{bg_past_dates}',
            '{color_past_dates}',
            '{bg_nav_month}',
            '{bg_nav_hover_month}',
            '{color_month}',
            '{bg_month}',
            '{month_size_past}',
            '{font_style_month}',
            '{font_famaly_month}',
            '{border_color}',
            '{border_widht}',
            '{color_legend}',
            '{font_size_legend}',
            '{font_famaly_legend}',
            '{font_style_legend}',
            '{color_week}',
            '{bg_week}',
            '{bg_booked}',
            '{color_booked}',
            '{font_size_booked}',
            '{font_famaly_booked}',
            '{font_style_booked}',
            '{bg_pending}',
            '{color_pending}',
            '{font_size_pending}',
            '{font_famaly_pending}',
            '{font_style_pending}',
            '{font_size_past}',
            '{font_famaly_past}',
            '{font_style_past}',
            '{bg_available}',
            '{color_available}',
            '{font_size_available}',
            '{font_famaly_available}',
            '{font_style_available}',
            '{bg_empty}',
            '{bg_selected}',
            '{color_today}',
            '{font_size_today}',
            '{font_famaly_today}',
            '{font_style_today}',
            '{calendarContainer}',
            '{SELECTDATE}',
            '{DATEPENDING}',
            '{DATERESERVED}',
            '{DATEPASSED}',
        );

        $replace = array(
            $option_arr_values['bg_past_dates'],
            $option_arr_values['color_past_dates'],
            $option_arr_values['bg_nav_month'],
            $option_arr_values['bg_nav_hover_month'],
            $option_arr_values['color_month'],
            $option_arr_values['bg_month'],
            $option_arr_values['month_size_past'],
            $option_arr_values['font_style_month'],
            $option_arr_values['font_famaly_month'],
            $option_arr_values['border_color'],
            $option_arr_values['border_widht'],
            $option_arr_values['color_legend'],
            $option_arr_values['font_size_legend'],
            $option_arr_values['font_famaly_legend'],
            $option_arr_values['font_style_legend'],
            $option_arr_values['color_week'],
            $option_arr_values['bg_week'],
            $option_arr_values['bg_booked'],
            $option_arr_values['color_booked'],
            $option_arr_values['font_size_booked'],
            $option_arr_values['font_famaly_booked'],
            $option_arr_values['font_style_booked'],
            $option_arr_values['bg_pending'],
            $option_arr_values['color_pending'],
            $option_arr_values['font_size_pending'],
            $option_arr_values['font_famaly_pending'],
            $option_arr_values['font_style_pending'],
            $option_arr_values['font_size_past'],
            $option_arr_values['font_famaly_past'],
            $option_arr_values['font_style_past'],
            $option_arr_values['bg_available'],
            $option_arr_values['color_available'],
            $option_arr_values['font_size_available'],
            $option_arr_values['font_famaly_available'],
            $option_arr_values['font_style_available'],
            $option_arr_values['bg_empty'],
            $option_arr_values['bg_selected'],
            $option_arr_values['color_today'],
            $option_arr_values['font_size_today'],
            $option_arr_values['font_famaly_today'],
            $option_arr_values['font_style_today'],
            '#gz-abc-main-container-' . $_GET['cid'],
            __('SELECTDATE'),
            __('DATEPENDING'),
            __('DATERESERVED'),
            __('DATEPASSED'),
        );

        echo str_replace($search, $replace, $css);
    }

    //modified: add new for test cases
    function testSomething()
    {
        Object::loadFiles('Model', array('Option', 'User', 'Calendar', 'DrupalEmailReservation'));
        $OptionModel = new OptionModel();
        $CalendarModel = new CalendarModel();
        $UserModel = new UserModel();

        $calendar = $CalendarModel->get($_GET['id']);
        $owner = $UserModel->get($calendar['user_id']);

        $option_arr = $OptionModel->getAllPairValues(array('calendar_id' => $calendar['id']));

        $_POST['from_date'] = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $_POST['startdate']);
        $_POST['to_date'] = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $_POST['finishdate']);
        $_POST['calendar_id'] = $_GET['cid'];
        
        $group = 'reservation';
        switch ($group) {
            case 'client':
                $message = Util::replaceInquiryFormToken($option_arr['client_inquiry_form'], $replacement);
                $subjetc = $option_arr['client_inquiry_form_subject'];
                $to = $_POST['email'];
                break;
            case 'admin':
                $message = Util::replaceInquiryFormToken($option_arr['admin_inquiry_form'], $replacement);
                $subjetc = $option_arr['admin_inquiry_form_subject'];
                $to = $option_arr['notify_email'];
                break;
            case 'owner':
                $message = Util::replaceInquiryFormToken($option_arr['owner_inquiry_form'], $replacement);
                $subjetc = $option_arr['owner_inquiry_form_subject'];
                $to = $owner['email'];
                break;
            //modified: add new
            case 'reservation':
                $DrupalEmailReservation = new DrupalEmailReservationModel();
                $email_reservation_data = $DrupalEmailReservation->get($calendar['villa_node_id']);
                echo "<pre>";var_dump($email_reservation_data['field_email_reservasi_value']);
                $message = Util::replaceInquiryFormToken($option_arr['owner_inquiry_form'], $replacement);
                $subjetc = $option_arr['owner_inquiry_form_subject'];
                $to = $calendar['villa_reservation_email'];
                break;
        }
        $option_arr['notify_email'] = 'admin@individualbali.com';
        
        echo "group: $group\n";
        echo "notify_email: $option_arr[notify_email]\n";
        echo "to: $to\n";
        
        die();
    }
}
