<?php

require_once CONTROLLERS_PATH . 'App.php';

class GzFront extends App {

    var $layout = 'front';
    var $default_captcha = 'GzCaptcha';

    function beforeFilter() {

        if (isset($_REQUEST['lang'])) {

            Object::loadFiles('Model', array('Languages'));
            $LanguagesModel = new LanguagesModel();

            $default_language = $LanguagesModel->getAll(array('id' => $_REQUEST['lang']), 'order');

            if (!empty($default_language[0])) {
                $this->setLanguage($default_language[0]);
                $this->tpl['select_language'] = $this->getLanguage();
            } else {
                $this->setLanguage($this->tpl['default_language']);
                $this->tpl['select_language'] = $this->getLanguage();
            }
        } else {
            //print_r($this->getLanguage());
            if (!$this->getLanguage() || !is_array($this->getLanguage())) {
                $this->setLanguage($this->tpl['default_language']);
            }
            $this->tpl['select_language'] = $this->getLanguage();
        }

        Object::loadFiles('Model', array('Calendar'));
        $CalendarModel = new CalendarModel();

        if (!empty($_GET['cid'])) {

            if(is_array($_GET['cid'])){
                $this->tpl['calendar'] = $CalendarModel->getI18n($_GET['cid'][0]);
            }else{
                $this->tpl['calendar'] = $CalendarModel->getI18n($_GET['cid']);
            }
        } else {

            $this->tpl['calendar'] = $CalendarModel->getI18n();
        }
        
        $this->tpl['currencies'] = Util::$currencies;
        
        if (!empty($_POST['currencies'])) {

            //  print_r($_POST['currencies']);
            $this->setcurrency($_POST['currencies']);
            $this->tpl['currencies_select'] = $this->getcurrency();
            //  print_r($this->tpl['currencies_select']);
        } else {
            $this->tpl['currencies_select'] = $this->getcurrency();
        }
    }

    /**
     * (non-PHPdoc)
     * @see core/framework/Controller::beforeRender()
     */
    function beforeRender() {
        if ($_REQUEST['action'] == 'inquiry_form') {
            $this->css[] = array('file' => 'front/bootstrap.min.css', 'path' => CSS_PATH);
            $this->css[] = array('file' => 'front/inquiry_form.css', 'path' => CSS_PATH);
            $this->css[] = array('file' => 'front/style.css', 'path' => CSS_PATH);
            $this->css[] = array('file' => 'front/gz-production.css', 'path' => CSS_PATH);
        } elseif($_REQUEST['action'] != 'modal_form') {
            $this->css[] = array('file' => 'front/style.css', 'path' => CSS_PATH);
            $this->css[] = array('file' => 'front/gz-production.css', 'path' => CSS_PATH);
            $this->css[] = array('file' => 'gzadmin/plugins/lada/ladda-themeless.min.css', 'path' => JS_PATH);
            $this->css[] = array('file' => 'gzadmin/plugins/tooltipster/css/tooltipster.css', 'path' => JS_PATH);
            $this->css[] = array('file' => 'gzadmin/plugins/tooltipster/css/themes/tooltipster-light.css', 'path' => JS_PATH);
            $this->css[] = array('file' => 'gzadmin/plugins/lada/prism.css', 'path' => JS_PATH);
        }
        foreach ($_GET['cid'] as $cid) {
            $this->css[] = array('file' => 'index.php?controller=GzFront&action=GzABCCss&cid=' . $cid, 'path' => '');
        }
         
        $this->js[] = array('file' => 'jquery-2.0.2.min.js', 'path' => LIBS_PATH);
        $this->js[] = array('file' => 'jquery-ui.js', 'path' => LIBS_PATH);
        
        if ($_REQUEST['action'] == 'inquiry_form') {
            //$this->js[] = array('file' => 'gzadmin/bootstrap-3.3.5.min.js', 'path' => JS_PATH);
            $this->js[] = array('file' => 'gzadmin/plugins/jquery-validation-1.14.0/jquery.validate-1.14.0.min.js', 'path' => JS_PATH);
            $this->js[] = array('file' => 'gzadmin/plugins/bootstrap-tooltip/jquery-validate.bootstrap-tooltip.min.js', 'path' => JS_PATH);
            /* modified: add 3 js file, since there some action in inquiry_form
             * need to refresh availability calendar
             */
            $this->js[] = array('file' => 'gzadmin/plugins/tooltipster/js/jquery.tooltipster.min.js', 'path' => JS_PATH);
            $this->js[] = array('file' => 'jquery/jquery-validation-1.13.0/dist/jquery.validate.min.js', 'path' => LIBS_PATH);
            $this->js[] = array('file' => 'load.js', 'path' => JS_PATH);
        } elseif($_REQUEST['action'] != 'modal_form'){
            $this->js[] = array('file' => 'jquery/jquery-validation-1.13.0/dist/jquery.validate.min.js', 'path' => LIBS_PATH);
        }
        if ($_REQUEST['action'] == 'index') {
            $this->js[] = array('file' => 'jquery.colorbox-min.js', 'path' => LIBS_PATH);
            $this->js[] = array('file' => 'gzadmin/plugins/lada/spin.min.js', 'path' => JS_PATH);
            $this->js[] = array('file' => 'gzadmin/plugins/lada/ladda.min.js', 'path' => JS_PATH);
            $this->js[] = array('file' => 'gzadmin/plugins/tooltipster/js/jquery.tooltipster.min.js', 'path' => JS_PATH);
            $this->js[] = array('file' => 'load.js', 'path' => JS_PATH);
        }
    }

    function captcha($renew = null) {
        $this->isAjax = true;

        Object::loadFiles('component', 'Captcha');
        $Captcha = new Captcha('application/web/fonts/Fishfingers.ttf', 'GzScripts', $this->default_captcha, 6);
        $Captcha->setFileName('application/web/img/captcha/45-degree-fabric.png');
        $renew = isset($_GET['renew']) ? $_GET['renew'] : null;
        $Captcha->create($renew);
    }

    /**
     * Write given $content to file
     *
     * @param string $content
     * @param string $filename If omitted use 'payment.log'
     * @access public
     * @return void
     * @static
     */
    function log($content, $filename = null) {
        if (TEST_MODE) {
            $filename = is_null($filename) ? 'payment.log' : $filename;
            @file_put_contents($filename, $content . "\n", FILE_APPEND | FILE_TEXT);
        }
    }

    function load() {
        $this->layout = 'empty';
    }

    function load_inquiry_form() {
        $this->layout = 'empty';
    }
    
    function load_modal_form() {
        $this->layout = 'empty';
    }
    
    function getReservation($m, $day, $y, $cid) {
        Object::loadFiles('Model', array('Booking'));
        $BookingModel = new BookingModel();

        $reservationsInfo = array();
        $view_month = 36;
        $now = mktime(0, 0, 0, $m, $day, $y);
        $from = $now - (60 * 24 * 60 * 60);
        $to = $now + ($view_month * 60 * 24 * 60 * 60);

        $sql = "SELECT MIN(`date_from`) AS `min`, MAX(`date_to`) AS `max` FROM " . $BookingModel->getTable() . " WHERE (date_from BETWEEN " . $from . "  AND " . $to . " OR date_to BETWEEN " . $from . "  AND " . $to . ") AND calendar_id = " . $cid . " AND date_from IS NOT NULL AND date_to IS NOT NULL";
        $arr = $BookingModel->execute($sql); // min and max reseravation datefor this period

        $opts = array();
        //    $opts['(date_from BETWEEN :from AND :to OR date_to BETWEEN :from AND :to) AND calendar_id = :calendar_id'] = array(':from' => $from, ':to' => $to, ':calendar_id' => $cid);
        $opts['calendar_id = :cid AND status != :status'] = array(':cid' => $cid, ':status' => "cancelled");
        
        $_arr = $BookingModel->getAll($opts); // ALL reservations for this period
        
        

        if (!empty($arr[0]['min']) && !empty($arr[0]['max'])) {

            for ($i = $arr[0]['min'] - (60 * 24 * 60 * 60); $i <= $arr[0]['max']; $i = strtotime("+1 day", $i)) {
                if (count($_arr) > 0) {
                    foreach ($_arr as $k => $v) {
                        
                        $from_date = date('Y-m-d', $v['date_from']);
                        $to_date = date('Y-m-d', $v['date_to']);
                        $date = date('Y-m-d', $i);

                        if (strtotime($from_date) < strtotime($date) && strtotime($to_date) > strtotime($date)) {

                            $reservationsInfo[$i] = date('Y-m-d', $i);
                        }
                    }
                }
            }
        }

        Object::loadFiles('Model', array('Blocking'));
        $BlockingModel = new BlockingModel();

        $now = mktime(0, 0, 0, $m, $day, $y);
        $from = $now - (60 * 24 * 60 * 60);
        $to = $now + ($view_month * 60 * 24 * 60 * 60);

        $sql = "SELECT MIN(`from_date`) AS `min`, MAX(`to_date`) AS `max` FROM " . $BlockingModel->getTable() . " WHERE from_date BETWEEN " . $from . "  AND " . $to . " OR to_date BETWEEN " . $from . "  AND " . $to . " AND calendar_id = " . $cid . " ";
        $arr = $BlockingModel->execute($sql); // min and max reseravation datefor this period

        $opts = array();
        //  $opts['(from_date BETWEEN :from AND :to OR to_date BETWEEN :from AND :to) AND calendar_id = :calendar_id'] = array(':from' => $from, ':to' => $to, ':calendar_id' => $this->calendar_id);
        $opts['calendar_id'] = $cid;
        $_arr = $BlockingModel->getAll($opts); // ALL reservations for this period

        if (is_array($arr) && !empty($arr[0])) {
            if (count($_arr) > 0) {
                for ($i = $arr[0]['min'] - (60 * 24 * 60 * 60); $i <= ($arr[0]['max'] + 60 * 24 * 60 * 60); $i  = strtotime("+1 day", $i)) {
                    foreach ($_arr as $k => $v) {
                        
                        $from_date = date('Y-m-d', $v['from_date']);
                        $to_date = date('Y-m-d', $v['to_date']);
                        $date = date('Y-m-d', $i);

                        if (strtotime($from_date) < strtotime($date) && strtotime($to_date) > strtotime($date)) {
                        
                            $reservationsInfo[$i] = date('Y-m-d', $i);
                        }
                    }
                }
            }
        }

        //print_r($reservationsInfo);
        return $reservationsInfo;
    }

    function inquiry_form() {
        header("content-type: application/javascript");
        
        Object::loadFiles('Model', 'Option');
        $OptionModel = new OptionModel();

        foreach ($_GET['cid'] as $cid) {
            $opts = array();
            $cal_id = $cid;
            $opts['calendar_id'] = $cid;
            $this->tpl['option_arr_values'][$cid] = $OptionModel->getAllPairValues($opts);
        }
        
        $m = date('m');
        $day = date('d');
        $y = date('Y');
        $this->tpl['reservation_info'] = array();
        $reservation_info = $this->getReservation($m, $day, $y, $cal_id);

        foreach ($reservation_info as $key => $value) {
            $this->tpl['reservation_info'][date($this->tpl['option_arr_values']['date_format'], $key)] = date($this->tpl['option_arr_values']['date_format'], $key);
        }
    }

    function modal_form() {
        header("content-type: application/javascript");
        
        Object::loadFiles('Model', 'Option');
        $OptionModel = new OptionModel();

        foreach ($_GET['cid'] as $cid) {
            $opts = array();
            $cal_id = $cid;
            $opts['calendar_id'] = $cid;
            $this->tpl['option_arr_values'][$cid] = $OptionModel->getAllPairValues($opts);
        }
        
        $m = date('m');
        $day = date('d');
        $y = date('Y');
        $this->tpl['reservation_info'] = array();
        $reservation_info = $this->getReservation($m, $day, $y, $cal_id);

        foreach ($reservation_info as $key => $value) {
            $this->tpl['reservation_info'][date($this->tpl['option_arr_values']['date_format'], $key)] = date($this->tpl['option_arr_values']['date_format'], $key);
        }
    }
    
    function index() {
        header("content-type: application/javascript");

        require APP_PATH . 'helpers/ABCalendar/ABCalendar.php';

        $d = date('j');
        $m = date('n');
        $y = date('Y');

        $this->tpl['abcalendar'] = array();
        Object::loadFiles('Model', 'Option');
        $OptionModel = new OptionModel();

        foreach ($_GET['cid'] as $cid) {
            $opts = array();
            $opts['calendar_id'] = $cid;
             $cal_id = $cid;
            $this->tpl['option_arr_values'][$cid] = $OptionModel->getAllPairValues($opts);
            $this->tpl['abcalendar'][$cid] = new ABCalendar($m, $d, $y, $cid, $_GET['view_month'], $this->tpl['option_arr_values'][$cid], $this->tpl['select_language']);
        }
        
        $m = date('m');
        $day = date('d');
        $y = date('Y');
        $this->tpl['reservation_info'] = array();
        $reservation_info = $this->getReservation($m, $day, $y, $cal_id);

        foreach ($reservation_info as $key => $value) {
            $this->tpl['reservation_info'][date($this->tpl['option_arr_values']['date_format'], $key)] = date($this->tpl['option_arr_values']['date_format'], $key);
        }
    }
    
    function send_inquiry_form(){
        $this->isAjax = true;
        
        $this->sendInquiryFormEmail('client');
        $this->sendInquiryFormEmail('admin');
        //modified: change to reservation email
        //$this->sendInquiryFormEmail('owner');
        $this->sendInquiryFormEmail('reservation');
        
        echo __('inquiry_form_success_result');
    }

    function calendars() {
        $this->isAjax = true;

        require APP_PATH . 'helpers/ABCalendar/ABCalendar.php';

        $d = date('j');
        $m = date('n');
        $y = date('Y');

        $this->tpl['abcalendar'] = array();

        Object::loadFiles('Model', 'Option');
        $OptionModel = new OptionModel();

        foreach ($_GET['cid'] as $cid) {
            $opts = array();
            $opts['calendar_id'] = $cid;
            $this->tpl['option_arr_values'][$cid] = $OptionModel->getAllPairValues($opts);
            $this->tpl['abcalendar'][$cid] = new ABCalendar($m, $d, $y, $cid, $_GET['view_month'], $this->tpl['option_arr_values'][$cid], $this->tpl['select_language']);
        }
    }

    function booking_details() {
        $this->isAjax = true;
        unset($_SESSION['err']);

        Object::loadFiles('Model', 'Option');
        $OptionModel = new OptionModel();

        $opts = array();
        $opts['calendar_id'] = $_GET['cid'];
        $this->tpl['option_arr_values'] = $OptionModel->getAllPairValues($opts);

        Object::loadFiles('Model', array('Extra'));
        $ExtraModel = new ExtraModel();

        $params['from_date'] = $_POST['start_date'];
        $params['to_date'] = $_POST['end_date'];

        $this->tpl['prices'] = $this->calclateBookingPrice(array_merge($params, $_POST));

        $nights = ($params['to_date'] - $params['from_date']) / 86400;

        if ($this->tpl['option_arr_values']['based_on'] != 'night') {
            $nights++;
        }

        $this->tpl['nights'] = $nights;

        $opts = array();

        if (!(@$this->tpl['option_arr_values']['show_captcha'] != 3 || (!empty($_SESSION[$this->default_product][$this->default_captcha]) && (strtoupper(@$_POST['captcha']) == $_SESSION[$this->default_product][$this->default_captcha])))) {

            $_SESSION['err']['captcha'] = __('wrong ceptcha');
            $this->tpl['extras'] = $ExtraModel->getAll(array(), 'id desc');
        } else {
            if (!empty($_POST['extra_id'])) {
                foreach ($_POST['extra_id'] as $id) {
                    $extra_id[] = $id;
                }

                $opts['id'] = $extra_id;

                $this->tpl['extras'] = $ExtraModel->getAll($opts, 'id desc');
            } else {
                $this->tpl['extras'] = array();
            }
        }
    }

    function checkout() {
        $this->isAjax = true;

        if ($_POST['create_booking']) {

            Object::loadFiles('Model', 'Option');
            $OptionModel = new OptionModel();

            $opts = array();
            $opts['calendar_id'] = $_GET['cid'];
            $this->tpl['option_arr_values'] = $OptionModel->getAllPairValues($opts);

            Object::loadFiles('Model', array('Booking', 'ExtraBooking', 'Calendar'));
            $BookingModel = new BookingModel();
            $CalendarModel = new CalendarModel();
            $ExtraBookingModel = new ExtraBookingModel();

            $calendar = $CalendarModel->get($_GET['cid']);

            $data = array();

            $data['date_from'] = $_POST['start_date'];
            $data['date_to'] = $_POST['end_date'];

            $data['status'] = "pending";

            unset($_POST['date_from']);
            unset($_POST['date_to']);

            $params = array();
            $params['from_date'] = $data['date_from'];
            $params['to_date'] = $data['date_to'];

            $prices = $this->calclateBookingPrice(array_merge($params, $_POST));

            $data['calendars_price'] = $prices['calendars_price'];
            $data['amount'] = ($prices['deposit'] > 0) ? $prices['deposit'] : $prices['total'];
            $data['amount'] = number_format($data['amount'], 2, '.', '');
            $data['extra_price'] = $prices['extra_price'];
            $data['discount'] = $prices['discount'];
            $data['total'] = $prices['total'];
            $data['tax'] = $prices['tax'];
            $data['security'] = $prices['security'];
            $data['deposit'] = $prices['deposit'];
            $data['discount'] = $prices['discount'];
            $nights = ($params['to_date'] - $params['from_date']) / 86400;
            $data['currency'] = $this->tpl['option_arr_values']['currency'];

            if ($this->tpl['option_arr_values']['based_on'] != 'night') {
                $nights++;
            }
            $data['nights'] = $nights;
            $data['booking_number'] = Util::incrementalHash(10);

            $id = $BookingModel->save(array_merge($_POST, $data));

            if (!empty($id)) {

                if (!empty($_POST['extra_id'])) {
                    foreach ($_POST['extra_id'] as $extra_id) {
                        $data = array();
                        $data['extra_id'] = $extra_id;
                        $data['booking_id'] = $id;

                        $ExtraBookingModel->save($data);
                    }
                }

                $BookingModel->generatePdfInvoice($id);

                $this->sendBookingEmails($id, 'create', 'client');
                $this->sendBookingEmails($id, 'create', 'admin');
                $this->sendBookingEmails($id, 'create', 'owner');

                $option_arr_values = $this->tpl['option_arr_values'];
                $calendarId = $calendar['googleId'];
                
                if(!empty($calendarId)){

                    require APP_PATH . 'helpers/google-api-php-client-master/src/Google/autoload.php';

                    define('APPLICATION_NAME', 'Google Calendar API Quickstart');
                    define('CREDENTIALS_PATH', INSTALL_PATH . '.credentials/calendar-api-quickstart.json');
                    define('CLIENT_SECRET_PATH', APP_PATH . 'helpers/google-api-php-client-master/client_secret.json');
                    define('SCOPES', implode(' ', array(
                        Google_Service_Calendar::CALENDAR)
                    ));

                    $client = $this->getClient();
                    $service = new Google_Service_Calendar($client);

                    $event = new Google_Service_Calendar_Event(array(
                        'summary' => $_POST['email'] . ' ' . __('amount') . ' ' . Util::currenctFormat($option_arr_values['currency'], $data['amount']),
                        'location' => $_POST['address_1'],
                        'description' => __('client_name') . ': ' . $_POST['email'] . ', ' . __('address') . $_POST['address_1'],
                        'start' => array(
                            'dateTime' => date('Y-m-d', $data['date_from']) . 'T09:00:00-00:00',
                            'timeZone' => 'America/Los_Angeles',
                        ),
                        'end' => array(
                            'dateTime' => date('Y-m-d', $data['date_to']) . 'T09:00:00-00:00',
                            'timeZone' => 'America/Los_Angeles',
                        ),
                        'recurrence' => array(
                            'RRULE:FREQ=DAILY;COUNT=2'
                        )
                    ));

                    $event = $service->events->insert($calendarId, $event);

                    $data = array();
                    $data['google_id'] = $event->id;
                    $data['id'] = $id;
                    $BookingModel->update($data);
                }
                $status = 10;
            } else {
                $status = 11;
            }

            if ($_POST['payment_method'] == 'authorize') {
                require_once APP_PATH . 'helpers/sdk-php-master/autoload.php';
            }

            $this->tpl['booking_details'] = $BookingModel->getBookingDetails($id);
        }
    }

    function paypal_confirm() {

        define("DEBUG", 1);
        define("USE_SANDBOX", 0);
        define("LOG_FILE", "./ipn.log");

        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost = array();
        foreach ($raw_post_array as $keyval) {
            $keyval = explode('=', $keyval);
            if (count($keyval) == 2)
                $myPost[$keyval[0]] = urldecode($keyval[1]);
        }

        $req = 'cmd=_notify-validate';
        if (function_exists('get_magic_quotes_gpc')) {
            $get_magic_quotes_exists = true;
        }
        foreach ($myPost as $key => $value) {
            if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
                $value = urlencode(stripslashes($value));
            } else {
                $value = urlencode($value);
            }
            $req .= "&$key = $value";
        }

        if (USE_SANDBOX == true) {
            $paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
        } else {
            $paypal_url = "https://www.paypal.com/cgi-bin/webscr";
        }

        $ch = curl_init($paypal_url);
        if ($ch == FALSE) {
            return FALSE;
        }

        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        if (DEBUG == true) {
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
        }
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
        $res = curl_exec($ch);

        if (curl_errno($ch) != 0) { // cURL error
            if (DEBUG == true) {
                error_log(date('[Y-m-d H:i e] ') . "Can't connect to PayPal to validate IPN message: " . curl_error($ch) . PHP_EOL, 3, LOG_FILE);
            }
            curl_close($ch);
            exit;
        } else {
            if (DEBUG == true) {
                error_log(date('[Y-m-d H:i e] ') . "HTTP request of validation request:" . curl_getinfo($ch, CURLINFO_HEADER_OUT) . " for IPN payload: $req" . PHP_EOL, 3, LOG_FILE);
                error_log(date('[Y-m-d H:i e] ') . "HTTP response of validation request: $res" . PHP_EOL, 3, LOG_FILE);
                list($headers, $res) = explode("\r\n\r\n", $res, 2);
            }
            curl_close($ch);
        }

        $item_name = $_POST['item_name'];
        $item_number = $_POST['item_number'];
        $payment_status = $_POST['payment_status'];
        $payment_amount = $_POST['mc_gross'];
        $payment_currency = $_POST['mc_currency'];
        $txn_id = $_POST['txn_id'];
        $receiver_email = $_POST['receiver_email'];
        $payer_email = $_POST['payer_email'];
        $payer_custom = $_POST['custom'];

        Object::loadFiles('Model', array('Booking'));
        $BookingModel = new BookingModel();

        $opts = array();
        $opts['id'] = $payer_custom;
        $opts['status'] = 'confirmed';

        $booking = $BookingModel->get($payer_custom);
        if (strpos($res, 'VERIFIED') !== false) {

            if ($booking) {

                $BookingModel->update($opts);
                $this->sendBookingEmails($payer_custom, 'confirmation', 'client');
                $this->sendBookingEmails($payer_custom, 'confirmation', 'admin');
                $this->sendBookingEmails($payer_custom, 'confirmation', 'owner');
            }
            if (DEBUG == true) {
                error_log(date('[Y-m-d H:i e] ') . "Verified IPN: $req " . PHP_EOL, 3, LOG_FILE);
            }
        }
        Util::redirect($this->tpl['option_arr_values']['payment_redirect']);
    }

    function confirm_2checkout() {
        Object::loadFiles('Model', array('Booking'));
        $BookingModel = new BookingModel();

        $booking = $BookingModel->get($_REQUEST['merchant_order_id']);

        $hashSecretWord = $this->tpl['option_arr_values']['checkout_SecretWord']; //2Checkout Secret Word
        $hashSid = $this->tpl['option_arr_values']['checkout_acc']; //2Checkout account number
        $hashTotal = $booking['amount']; //Sale total to validate against
        $hashOrder = $_REQUEST['order_number']; //2Checkout Order Number
        $StringToHash = strtoupper(md5($hashSecretWord . $hashSid . $hashOrder . $hashTotal));

        if ($StringToHash != $_REQUEST['key']) {
            $result = 'Fail - Hash Mismatch';
        } else {

            $opts = array();
            $opts['id'] = $_REQUEST['merchant_order_id'];
            $opts['status'] = 'confirmed';

            if ($booking) {

                $BookingModel->update($opts);
                $this->sendBookingEmails($payer_custom, 'confirmation', 'client');
                $this->sendBookingEmails($payer_custom, 'confirmation', 'admin');
                $this->sendBookingEmails($payer_custom, 'confirmation', 'owner');
            }
        }
        Util::redirect($this->tpl['option_arr_values']['payment_redirect']);
    }

    function authorize_confirm() {
        $ResponseCode = trim($_POST["x_response_code"]);
        $ResponseReasonText = trim($_POST["x_response_reason_text"]);
        $ResponseReasonCode = trim($_POST["x_response_reason_code"]);
        $AVS = trim($_POST["x_avs_code"]);
        $TransID = trim($_POST["x_trans_id"]);
        $AuthCode = trim($_POST["x_auth_code"]);
        $Amount = trim($_POST["x_amount"]);
        $sequence = trim($_POST["x_fp_sequence"]);

// Test to see if this is a test transaction.
        if ($TransID === 0 && $ResponseCode === 1) {
// If so, print it to the screen, so we know that the transaction will not be processed because your account is in Test Mode.
        }
// Test to see if the transaction resulted in Approvavl, Decline or Error
        if ($ResponseCode === 1) {
            Object::loadFiles('Model', array('Booking'));
            $BookingModel = new BookingModel();

            $opts = array();
            $opts['id'] = $sequence;
            $opts['status'] = 'confirmed';

            $booking = $BookingModel->get($sequence);

            if ($booking) {

                $BookingModel->update($opts);
                $this->sendBookingEmails($sequence, 'confirmation', 'client');
                $this->sendBookingEmails($sequence, 'confirmation', 'admin');
                $this->sendBookingEmails($sequence, 'confirmation', 'owner');
            }
        } else if ($ResponseCode === 2) {
//This transaction has been declined.
        } else if ($ResponseCode === 3) {
//There was an error processing this transaction.
        }

        Util::redirect($this->tpl['option_arr_values']['payment_redirect']);

        if ($TransID === 0) {
            echo 'Not Applicable.';
        } else {
            echo $TransID;
        }

        if ($AuthCode === "000000") {
            echo 'Not Applicable.';
        } else {
            echo $AuthCode;
        }

// Turn the AVS code into the corresponding text string.
        switch ($AVS) {
            case "A":
                echo "Address (Street) matches, ZIP does not.";
                break;
            case "B":
                echo "Address Information Not Provided for AVS Check.";
                break;
            case "C":
                echo "Street address and Postal Code not verified for international transaction due to incompatible formats. (Acquirer sent both street address and Postal Code.)";
                break;
            case "D":
                echo "International Transaction:  Street address and Postal Code match.";
                break;
            case "E":
                echo "AVS Error.";
                break;
            case "G":
                echo "Non U.S. Card Issuing Bank.";
                break;
            case "N":
                echo "No Match on Address (Street) or ZIP.";
                break;
            case "P":
                echo "AVS not applicable for this transaction.";
                break;
            case "R":
                echo "Retry. System unavailable or timed out.";
                break;
            case "S":
                echo "Service not supported by issuer.";
                break;
            case "U":
                echo "Address information is unavailable.";
                break;
            case "W":
                echo "9 digit ZIP matches, Address (Street) does not.";
                break;
            case "X":
                echo "Address (Street) and 9 digit ZIP match.";
                break;
            case "Y":
                echo "Address (Street) and 5 digit ZIP match.";
                break;
            case "Z":
                echo "5 digit ZIP matches, Address (Street) does not.";
                break;
            default:
                echo "The address verification system returned an unknown value.";
                break;
        }
    }
    
    function calculateInquiryFormPrice() {
        $this->isAjax = true;
        
        $_POST['from_date'] = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $_POST['startdate']);
        $_POST['to_date'] = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $_POST['finishdate']);
        
        if($_POST['to_date'] < $_POST['from_date']){
            $c = $_POST['from_date'];
            $_POST['from_date'] = $_POST['to_date'];
            $_POST['to_date'] = $c;
        }
        
        $_POST['calendar_id'] = $_GET['cid'];
        $price = $this->calclateBookingPrice($_POST);
        
        $nights = ceil(($_POST['to_date'] - $_POST['from_date']) / 86400);
        $price['formated_total'] = $price['formated_total'] . ' / ' . $nights . ' nights';
        //modified: add new
        $price['formated_total_with_tax'] = '<small>inc tax</small> ' . $price['formated_total_with_tax'];
        header("Content-Type: application/json", true);
        echo json_encode($price);
    }

    function calculatePrice() {
        $this->isAjax = true;

        $price = $this->calclateBookingPrice($_POST);

        header("Content-Type: application/json", true);
        echo json_encode($price);
    }

    /*
      function getNumberNights() {
      $this->isAjax = true;

      if (!empty($_POST['date_from']) && !empty($_POST['date_to'])) {

      $date_from = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $_POST['date_from']);
      $date_to = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $_POST['date_to']);

      $nights = ($date_to - $date_from) / 86400;

      if ($this->tpl['option_arr_values']['based_on'] != 'night') {
      $nights++;
      }
      echo ceil($nights);
      } else {
      echo '0';
      }
      }
     */

    function calendar() {
        $this->isAjax = true;

        Object::loadFiles('Model', 'Option');
        $OptionModel = new OptionModel();

        $opts = array();
        $opts['calendar_id'] = $_GET['cid'];
        $this->tpl['option_arr_values'] = $OptionModel->getAllPairValues($opts);

        require APP_PATH . 'helpers/ABCalendar/ABCalendar.php';

        $d = date('j');
        if (!empty($_POST['month'])) {
            $m = $_POST['month'];
        } else {
            $m = date('m');
        }
        if (!empty($_POST['year'])) {
            $y = $_POST['year'];
        } else {
            $y = date('Y');
        }

        $this->tpl['abcalendar'] = new ABCalendar($m, $d, $y, $_GET['cid'], $_GET['view_month'], $this->tpl['option_arr_values'], $this->tpl['select_language']);
    }

    function extra_form() {
        $this->isAjax = true;

        Object::loadFiles('Model', 'Option');
        $OptionModel = new OptionModel();

        $opts = array();
        $opts['calendar_id'] = $_GET['cid'];
        $this->tpl['option_arr_values'] = $OptionModel->getAllPairValues($opts);

        Object::loadFiles('Model', array('Extra'));
        $ExtraModel = new ExtraModel();

        $this->tpl['extras'] = $ExtraModel->getAll(null, 'id desc');

        if (!empty($_POST['end_date']) && !empty($_POST['start_date'])) {

            if ($_POST['start_date'] > $_POST['end_date']) {
                $change = $_POST['start_date'];
                $_POST['start_date'] = $_POST['end_date'];
                $_POST['end_date'] = $change;
            }
            $date_from = $_POST['start_date'];
            $date_to = $_POST['end_date'];

            $nights = ($date_to - $date_from) / 86400;

            if ($this->tpl['option_arr_values']['based_on'] != 'night') {
                $nights++;
                $this->tpl['nights'] = ceil($nights) . ' ' . __('days');
            } else {
                $this->tpl['nights'] = ceil($nights) . ' ' . __('nights');
            }
        } else {
            echo '0';
            $this->tpl['nights'] = 0 . ' ' . __('nights');
        }

        $_POST['calendar_id'] = $_GET['cid'];
        $_POST['from_date'] = $_POST['start_date'];
        $_POST['to_date'] = $_POST['end_date'];

        $this->tpl['prices'] = $this->calclateBookingPrice($_POST);
    }

    function GzABCCss() {
        $this->layout = 'empty';
        $this->getCss();
    }
    
    function get_json_date(){
        $this->isAjax = true;
        
        $res = array();
        
        $res['start_date'] = date($this->tpl['option_arr_values']['date_format'], $_POST['start_date']);
        $res['end_date'] = date($this->tpl['option_arr_values']['date_format'], $_POST['end_date']);
        
        header("Content-Type: application/json", true);
        echo json_encode($res);
    }
    
    function changeCurrancy(){
         $this->isAjax = true;
         
         $this->setcurrency($_POST['currencies']);
         $this->tpl['currencies_select'] = $this->getcurrency();
    }
    
//    function GzABJS()
//    {
//        $this->layout = 'empty';
//        $this->getABJs();
//    }
//    
//    function getABJs()
//    {
//        header("content-type: application/javascript");
//        require LIBS_PATH . 'jquery-1.11.1.min.js';
//        require LIBS_PATH . 'jquery-ui.js';
//        require JS_PATH . 'gzadmin/plugins/jquery-validation-1.14.0/jquery.validate-1.14.0.min.js';
//        require JS_PATH . 'gzadmin/plugins/bootstrap-tooltip/jquery-validate.bootstrap-tooltip.min.js';
//        require JS_PATH . 'gzadmin/bootstrap.js';
//        require JS_PATH . 'gzadmin/plugins/tooltipster/js/jquery.tooltipster.min.js';
//        require LIBS_PATH . 'jquery/jquery-validation-1.13.0/dist/jquery.validate.min.js';
//        require JS_PATH . 'jquery.colorbox-min.js';
//        require JS_PATH . 'gzadmin/plugins/lada/spin.min.js';
//        require JS_PATH . 'gzadmin/plugins/lada/ladda.min.js';
//        require JS_PATH . 'load.js';
//    }
}
