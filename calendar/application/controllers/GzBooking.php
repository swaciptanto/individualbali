<?php

require_once CONTROLLERS_PATH . 'App.php';

class GzBooking extends App {

    var $layout = 'admin';

    function beforeFilter() {

        Object::loadFiles('Model', 'Option');
        $OptionModel = new OptionModel();
        $this->option_arr = $OptionModel->getAllPairValues();
        $this->tpl['option_arr'] = $OptionModel->getAllPairs();
        $this->tpl['option_arr_values'] = $this->option_arr;

        $this->tpl['js_format'] = Util::getJsDateFormat($this->tpl['option_arr_values']['date_format']);
        $this->tpl['iso_format'] = Util::getISODateFormat($this->tpl['option_arr_values']['date_format']);

        date_default_timezone_set($this->tpl['option_arr_values']['timezone']);

        if (!$this->isLoged() && $_REQUEST['action'] != 'login') {
            $_SESSION['err'] = 2;
            Util::redirect(INSTALL_URL . "GzAdmin/login");
        }

        $this->css[] = array('file' => 'admin/gzstyling/bootstrap.min.css', 'path' => CSS_PATH);
        $this->css[] = array('file' => 'admin/gzstyling/font-awesome.min.css', 'path' => CSS_PATH);
        $this->css[] = array('file' => 'admin/gzstyling/ionicons.min.css', 'path' => CSS_PATH);
        $this->css[] = array('file' => 'admin/gzstyling/gzstyle.css', 'path' => CSS_PATH);
        $this->css[] = array('file' => 'admin/gzstyling/daterangepicker/daterangepicker-bs3.css', 'path' => CSS_PATH);
        $this->css[] = array('file' => 'ui-custom.css', 'path' => CSS_PATH);
        $this->css[] = array('file' => 'admin/admin.css', 'path' => CSS_PATH);
        $this->css[] = array('file' => 'admin/gzstyling/datepicker/datepicker.css', 'path' => CSS_PATH);

        $this->js[] = array('file' => 'jquery/jquery-1.9.1.min.js', 'path' => LIBS_PATH);
        $this->js[] = array('file' => 'gzadmin/bootstrap.min.js', 'path' => JS_PATH);
        $this->js[] = array('file' => 'gzadmin/plugins/datatables/jquery.dataTables.js', 'path' => JS_PATH);
        $this->js[] = array('file' => 'gzadmin/plugins/datatables/dataTables.bootstrap.js', 'path' => JS_PATH);
        $this->js[] = array('file' => 'gzadmin/gzadmin/app.js', 'path' => JS_PATH);
        $this->js[] = array('file' => 'jquery.ui.core.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
        $this->js[] = array('file' => 'jquery.ui.widget.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
        $this->js[] = array('file' => 'jquery.ui.button.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
        $this->js[] = array('file' => 'jquery.ui.position.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
        $this->js[] = array('file' => 'jquery.ui.dialog.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
       
        $this->js[] = array('file' => 'ajax-upload/jquery.form.js', 'path' => JS_PATH);
        $this->js[] = array('file' => 'jquery/jquery-validation-1.13.0/dist/jquery.validate.js', 'path' => LIBS_PATH);
        $this->js[] = array('file' => 'jquery/ui/jquery-ui.min.js', 'path' => LIBS_PATH);
        $this->js[] = array('file' => 'gzadmin/plugins/daterangepicker/daterangepicker.js', 'path' => JS_PATH);
        $this->js[] = array('file' => 'gzadmin/plugins/datepicker/bootstrap-datepicker.js', 'path' => JS_PATH);
        if ($_REQUEST['action'] == 'send') {
            $this->js[] = array('file' => 'jquery/tinymce/tinymce.min.js', 'path' => LIBS_PATH);
        }
        $this->js[] = array('file' => 'GzBooking.js', 'path' => JS_PATH);
    }

    function create() {
        Object::loadFiles('Model', array('Booking', 'BookingCalendar', 'Extra', 'ExtraBooking', 'Calendar'));
        $BookingModel = new BookingModel();
        $BookingCalendarModel = new BookingCalendarModel();
        $ExtraModel = new ExtraModel();
        $ExtraBookingModel = new ExtraBookingModel();
        $CalendarModel = new CalendarModel();

        if (!empty($_POST['create_booking'])) {
            
            $calendar = $CalendarModel->getI18n($_POST['calendar_id']);

            $data = array();
            $_POST['date_range'] = str_replace(' - ', '|', $_POST['date_range']);
            $date = explode('|', $_POST['date_range']);

            if (!empty($date['0']) && !empty($date['1'])) {

                $params = array();

                unset($date['from_date']);
                unset($date['to_date']);

                $data['date_from'] = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $date['0']);
                $data['date_to'] = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $date['1']);
                $data['nights'] = ceil(($data['date_to'] - $data['date_from']) / 86400);
                if ($this->tpl['option_arr_values']['based_on'] != 'night') {
                    $data['nights'] ++;
                }
            }

            $data['booking_number'] = Util::incrementalHash(10);

            $data['amount'] = ($_POST['deposit'] > 0) ? $_POST['deposit'] : $_POST['total'];
            $data['amount'] = number_format($data['amount'], 2, '.', '');

            $id = $BookingModel->save(array_merge($_POST, $data));

            if (!empty($id)) {
                $calendarId = $calendar['googleId'];

                require APP_PATH . 'helpers/google-api-php-client-master/src/Google/autoload.php';

                define('APPLICATION_NAME', 'Google Calendar API Quickstart');
                define('CREDENTIALS_PATH', INSTALL_PATH . '.credentials/calendar-api-quickstart.json');
                define('CLIENT_SECRET_PATH', APP_PATH . 'helpers/google-api-php-client-master/client_secret.json');
                define('SCOPES', implode(' ', array(
                    Google_Service_Calendar::CALENDAR)
                ));

                try {
                    $client = $this->getClient();
                    $service = new Google_Service_Calendar($client);
                } catch (Exception $exc) {
                    //echo $exc->getTraceAsString();
                }

                $event = new Google_Service_Calendar_Event(array(
                    'summary' => $calendar['i18n'][$this->tpl['default_language']['id']]['title'] . ' ' . ($_POST['adults'] + $_POST['children']) . ' Guest',
                    'location' => $_POST['address_1'],
                    'description' => $calendar['i18n'][$this->tpl['default_language']['id']]['title'] . ' ' . ($_POST['adults'] + $_POST['children']) . ' Guest',
                    'start' => array(
                        'dateTime' => date('Y-m-d', $data['date_from']) . 'T09:00:00-00:00',
                        'timeZone' => 'America/Los_Angeles',
                    ),
                    'end' => array(
                        'dateTime' => date('Y-m-d', $data['date_to']) . 'T09:00:00-00:00',
                        'timeZone' => 'America/Los_Angeles',
                    )
                ));

                try {
                    $event = $service->events->insert($calendarId, $event);
                } catch (Exception $exc) {
                    //echo $exc->getTraceAsString();
                }

                //https://secure-distribution-xml.booking.com/xml/bookings.processBooking?affiliate_id=309841&test_mode=1&begin_date=2016-07-05&end_date=2016-07-06&block_id=9825103_8806_1,9825103_80154322_0&block_qty=1,2&guest_city=London&guest_country=gb&guest_street=12%20Howling%20Lane&guest_zip=W1B%203HH&guest_email=[email%20address%20where%20you%20will%20receive%20the%20confirmation]&guest_name=Albert,Alex,Dave&booker_firstname=Bobby&booker_lastname=Tables&guest_qty=1,2,2&guest_telephone=0207125600&hotel_id=98251&show_test=1&incremental_prices=360,1200&cc_cardholder=A.Yourname&cc_cvc=123&cc_expiration_date=2015-02-01&cc_number=1234567890123456&cc_type=3

                $data = array();
                $data['google_id'] = $event->id;
                $data['id'] = $id;
                $BookingModel->update($data);
                
                if (!empty($_POST['extra_id'])) {
                    foreach ($_POST['extra_id'] as $extra_id) {
                        $data = array();
                        $data['extra_id'] = $extra_id;
                        $data['booking_id'] = $id;

                        $ExtraBookingModel->save($data);
                    }
                }
                $_SESSION['status'] = 10;
            } else {
                $_SESSION['status'] = 11;
            }

            Util::redirect(INSTALL_URL . "GzBooking/index");
        }

        $opts = array();
        $this->tpl['extras'] = $ExtraModel->getAll($opts, 'id desc');
        $this->tpl['calendars'] = $CalendarModel->getI18nAll();
    }

    function edit() {

        Object::loadFiles('Model', array('Booking', 'Calendar', 'Extra', 'ExtraBooking'));
        $BookingModel = new BookingModel();
        $CalendarModel = new CalendarModel();
        $ExtraModel = new ExtraModel();
        $ExtraBookingModel = new ExtraBookingModel();

        if (!empty($_POST['edit_booking'])) {
            $data = array();
            
            $calendar = $CalendarModel->getI18n($_POST['calendar_id']);

            $_POST['date_range'] = str_replace(' - ', '|', $_POST['date_range']);
            $date = explode('|', $_POST['date_range']);

            if (!empty($date['0']) && !empty($date['1'])) {

                $params = array();

                unset($date['from_date']);
                unset($date['to_date']);

                $data['date_from'] = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $date['0']);
                $data['date_to'] = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $date['1']);

                $data['nights'] = ceil(($data['date_to'] - $data['date_from']) / 86400);
                if ($this->tpl['option_arr_values']['based_on'] != 'night') {
                    $data['nights'] ++;
                }
            }

            $data['amount'] = ($_POST['deposit'] > 0) ? $_POST['deposit'] : $_POST['total'];
            $data['amount'] = number_format($data['amount'], 2, '.', '');


            $id = $BookingModel->update(array_merge($data, $_POST));

            $ExtraBookingModel->deleteFrom($ExtraBookingModel->getTable())
                    ->where('booking_id', $_POST['id'])->execute();

            if (!empty($_POST['extra_id'])) {
                foreach ($_POST['extra_id'] as $extra_id) {
                    $data = array();
                    $data['extra_id'] = $extra_id;
                    $data['booking_id'] = $_POST['id'];

                    $ExtraBookingModel->save($data);
                }
            }
            
            $calendarId = $calendar['googleId'];
            
            if(!empty($calendarId)){

                require APP_PATH . 'helpers/google-api-php-client-master/src/Google/autoload.php';

                define('APPLICATION_NAME', 'Google Calendar API Quickstart');
                define('CREDENTIALS_PATH', INSTALL_PATH . '.credentials/calendar-api-quickstart.json');
                define('CLIENT_SECRET_PATH', APP_PATH . 'helpers/google-api-php-client-master/client_secret.json');
                define('SCOPES', implode(' ', array(
                    Google_Service_Calendar::CALENDAR)
                ));

                try {
                    $client = $this->getClient();
                    $service = new Google_Service_Calendar($client);

                    $service->events->delete($calendarId, $booking['google_id']);

                    $event = new Google_Service_Calendar_Event(array(
                        'summary' => $calendar['i18n'][$this->tpl['default_language']['id']]['title'] . ' ' . ($_POST['adults'] + $_POST['children']) . ' Guest',
                        'location' => $_POST['address_1'],
                        'description' => $calendar['i18n'][$this->tpl['default_language']['id']]['title'] . ' ' . ($_POST['adults'] + $_POST['children']) . ' Guest',
                        'start' => array(
                            'dateTime' => date('Y-m-d', $data['date_from']) . 'T09:00:00-00:00',
                            'timeZone' => 'America/Los_Angeles',
                        ),
                        'end' => array(
                            'dateTime' => date('Y-m-d', $data['date_to']) . 'T09:00:00-00:00',
                            'timeZone' => 'America/Los_Angeles',
                        )
                    ));

                    $event = $service->events->insert($calendarId, $event);
                } catch (Exception $exc) {
                    //echo $exc->getTraceAsString();
                }

                $data = array();
                $data['google_id'] = $event->id;
                $data['id'] = $_POST['id'];
                $BookingModel->update($data);
            }
            $BookingModel->generatePdfInvoice($_REQUEST['id']);

            if (!empty($id)) {
                $_SESSION['status'] = 14;
            } else {
                $_SESSION['status'] = 15;
            }
            Util::redirect(INSTALL_URL . "GzBooking/index");
        }
        $id = $_GET['id'];
        $arr = $BookingModel->get($id);
        $this->tpl['booking'] = $arr;
        $opts = array();
        $this->tpl['extras'] = $ExtraModel->getAll($opts, 'id desc');
        $booked_extras = $ExtraBookingModel->getAll(array('booking_id' => $id));
        $this->tpl['booked_extras'] = array();
        $this->tpl['calendars'] = $CalendarModel->getI18nAll();

        foreach ($booked_extras as $extra) {
            $this->tpl['booked_extras'][$extra['extra_id']] = $extra;
        }
    }

    function index() {
        Object::loadFiles('Model', array('Booking', 'Calendar'));
        $BookingModel = new BookingModel();
        $CalendarModel = new CalendarModel();

        $opts = array();

        if (!empty($_POST['from_start_time']) && !empty($_POST['to_start_time'])) {

            $start = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $_POST['from_start_time']);
            $end = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $_POST['to_start_time']);

            $opts['date_from BETWEEN :start AND :end'] = array(':start' => $start, ':end' => $end);
        } elseif (!empty($_POST['from_start_time']) && empty($_POST['to_start_time'])) {
            $start = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $_POST['from_start_time']);

            $opts['date_from >= :start'] = array(':start' => $start);
        } elseif (empty($_POST['from_start_time']) && !empty($_POST['to_start_time'])) {
            $end = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $_POST['to_start_time']);

            $opts['date_from <= :end'] = array(':end' => $end);
        }

        if (!empty($_POST['from_end_time']) && !empty($_POST['to_end_time'])) {

            $start = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $_POST['from_end_time']);
            $end = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $_POST['to_end_time']);

            $opts['date_to BETWEEN :start AND :end'] = array(':start' => $start, ':end' => $end);
        } elseif (!empty($_POST['from_end_time']) && empty($_POST['to_end_time'])) {

            $start = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $_POST['from_end_time']);

            $opts['date_to >= :start'] = array(':start' => $start);
        } elseif (empty($_POST['from_end_time']) && !empty($_POST['to_end_time'])) {

            $end = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $_POST['to_end_time']);

            $opts['date_to <= :end'] = array(':end' => $end);
        }
        
        if (!empty($_POST['calendar_id'])) {

            $opts['calendar_id = :calendar_id'] = array(':calendar_id' => $_POST['calendar_id']);
        }

        if (!empty($_POST['status'])) {

            $opts['status = :status'] = array(':status' => $_POST['status']);
        }

        if (!empty($_POST['first_name'])) {

            $opts['first_name LIKE :first_name'] = array(':first_name' => "%" . $_POST['first_name'] . "%");
        }
        if (!empty($_POST['second_name'])) {

            $opts['second_name LIKE :second_name'] = array(':second_name' => "%" . $_POST['second_name'] . "%");
        }
        if (!empty($_POST['email'])) {

            $opts['email LIKE :email'] = array(':email' => "%" . $_POST['email'] . "%");
        }
        if (!empty($_POST['adults'])) {

            $opts['adults = :adults'] = array(':adults' => $_POST['adults']);
        }
        if (!empty($_POST['children'])) {

            $opts['children = :children'] = array(':children' => $_POST['children']);
        }

        $arr = $BookingModel->getAll($opts);

        $this->tpl['arr'] = $arr;

        $this->tpl['calendars'] = $CalendarModel->getI18nAll();
    }

    function delete() {
        $this->isAjax = true;

        $id = $_REQUEST['id'];

        Object::loadFiles('Model', array('Booking', 'BookingCalendar', 'ExtraBooking', 'Calendar'));
        $BookingModel = new BookingModel();
        $BookingCalendarModel = new BookingCalendarModel();
        $ExtraBookingModel = new ExtraBookingModel();
        $CalendarModel = new CalendarModel();
        
        $booking = $BookingModel->get($id);
        $calendar = $CalendarModel->get($booking['calendar_id']);

        $BookingCalendarModel->deleteFrom($BookingCalendarModel->getTable())
                ->where('booking_id', $id)->execute();

        $ExtraBookingModel->deleteFrom($ExtraBookingModel->getTable())
                ->where('booking_id', $id)->execute();

        $BookingModel->deleteFrom($BookingModel->getTable())
                ->where('id', $id)->execute();

        $calendarId = $calendar['googleId'];
        
        if(!empty($calendarId)){

            try {
                require APP_PATH . 'helpers/google-api-php-client-master/src/Google/autoload.php';

                define('APPLICATION_NAME', 'Google Calendar API Quickstart');
                define('CREDENTIALS_PATH', INSTALL_PATH . '.credentials/calendar-api-quickstart.json');
                define('CLIENT_SECRET_PATH', APP_PATH . 'helpers/google-api-php-client-master/client_secret.json');
                define('SCOPES', implode(' ', array(
                    Google_Service_Calendar::CALENDAR)
                ));

                $client = $this->getClient();
                $service = new Google_Service_Calendar($client);

                $service->events->delete($calendarId, $booking['google_id']);
            } catch (Exception $exc) {
                //echo $exc->getTraceAsString();
            }
        }
        $this->index();
    }

    function deleteSelected() {
        $this->isAjax = true;

        Object::loadFiles('Model', array('Booking', 'BookingCalendar', 'ExtraBooking', 'Calendar'));
        $BookingModel = new BookingModel();
        $BookingCalendarModel = new BookingCalendarModel();
        $ExtraBookingModel = new ExtraBookingModel();
        $CalendarModel = new CalendarModel();
        
        try {
            require APP_PATH . 'helpers/google-api-php-client-master/src/Google/autoload.php';

            define('APPLICATION_NAME', 'Google Calendar API Quickstart');
            define('CREDENTIALS_PATH', INSTALL_PATH . '.credentials/calendar-api-quickstart.json');
            define('CLIENT_SECRET_PATH', APP_PATH . 'helpers/google-api-php-client-master/client_secret.json');
            define('SCOPES', implode(' ', array(
                Google_Service_Calendar::CALENDAR)
            ));
        } catch (Exception $exc) {
            //echo $exc->getTraceAsString();
        }
        
        foreach ($_POST['mark'] as $key => $value) {

            $booking = $BookingModel->get($value);

            $calendar = $CalendarModel->get($booking['calendar_id']);
            
            if(!empty($calendar['googleId'])){
                
                $client = $this->getClient();
                $service = new Google_Service_Calendar($client);
                $calendarId = $calendar['googleId'];

                try {
                    $service->events->delete($calendarId, $booking['google_id']);
                } catch (Exception $exc) {
                    //echo $exc->getTraceAsString();
                }
            }
        }

        if (!empty($_POST['mark'])) {
            $BookingModel->deleteFrom($BookingModel->getTable())
                    ->where('id', $_POST['mark'])->execute();
            $BookingCalendarModel = new BookingCalendarModel();

            $BookingCalendarModel->deleteFrom($BookingCalendarModel->getTable())
                    ->where('booking_id', $_POST['mark'])->execute();

            $ExtraBookingModel->deleteFrom($ExtraBookingModel->getTable())
                    ->where('booking_id', $_POST['mark'])->execute();
        }

        $arr = $BookingModel->getAll();

        $this->tpl['arr'] = $arr;
    }

    function addBookingCalendar() {
        $this->isAjax = true;

        Object::loadFiles('Model', array('Calendar'));
        $CalendarModel = new CalendarModel();

        $opts = array();

        $post = Util::parse_query_str($_POST['form']);

        if (!empty($post['date_range'])) {
            $post['date_range'] = urldecode($post['date_range']);

            $post['date_range'] = str_replace(' - ', '|', $post['date_range']);
            $date = explode('|', $post['date_range']);

            if (!empty($date['0']) && !empty($date['1'])) {

                $from_date = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $date['0']);
                $to_date = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $date['1']);
            }
            $not_availability_calendar = $this->getAvailabilityTypeArray(array('date_from' => $from_date, 'date_to' => $to_date, 'adults' => $post['adults'], 'children' => $post['children']));

            $type_arr = array();
            foreach ($not_availability_calendar as $v) {

                $type_arr[] = $v['type_id'];
            }
            if (!empty($type_arr)) {
                $opts['id NOT IN ?'] = "('" . implode("','", $type_arr) . "')";
            }
            $opts['adults >= ?'] = $post['adults'];
            $opts['children >= ?'] = $post['children'];

            $this->tpl['calendars'] = $CalendarModel->getI18nAll($opts);
        }
    }

    function addBookingCalendars() {
        $this->isAjax = true;

        Object::loadFiles('Model', array('Calendar'));
        $CalendarModel = new CalendarModel();

        $post = Util::parse_query_str($_POST['form']);

        if (!empty($post['date_range'])) {
            $post['date_range'] = str_replace(' - ', '|', urldecode($post['date_range']));
            $date = explode('|', $post['date_range']);
            if (!empty($date['0']) && !empty($date['1'])) {

                $from_date = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $date['0']);
                $to_date = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $date['1']);
            }

            $not_availability_calendar = $this->getNotAvailabilityCalendarArray(array('date_from' => $from_date, 'date_to' => $to_date, 'type_id' => $_POST['id']));

            $calendar_id = array();
            foreach ($not_availability_calendar as $v) {

                $calendar_id[] = $v['id'];
            }

            if (!empty($calendar_id)) {
                $opts['id NOT IN ?'] = "('" . implode("', '", $calendar_id) . "')";
            }
            $this->tpl['calendars'] = $CalendarModel->getI18nAll(array('calendar_id' => $_REQUEST['id']));
        }

        $calendar_id_arr = explode('&', $_REQUEST['calendar_id']);

        $this->tpl['room_id_arr'] = array();

        if (count($calendar_id_arr) > 0) {
            foreach ($calendar_id_arr as $k => $v) {
                $calendar_id = explode('=', $v);
                if (!empty($calendar_id[1])) {
                    $this->tpl['calendar_id_arr'][] = $calendar_id[1];
                }
            }
        }
    }

    function getCalendarTable() {
        $this->isAjax = true;

        Object::loadFiles('Model', array('Calendar'));
        $CalendarModel = new CalendarModel();
        $opts = array();
        $this->tpl['calendars'] = array();
        $calendar_id_arr = array();
        $id_arr = array();

        if (!empty($_POST['calendar_ids'])) {
            $opts['t1.id'] = $_POST['calendars_ids'];

            $this->tpl['calendars'] = $CalendarModel->getAllCalendars($opts);
        }
    }

    function calculatePrice() {
        $this->isAjax = true;

        $post = $_POST;

        $price = array('calendars_price' => 0, 'extra_price' => 0, 'discount' => 0, 'total' => 0, 'tax' => 0, 'security' => 0, 'deposit' => 0);

        if (!empty($post['date_range'])) {

            $post['date_range'] = str_replace(' - ', '|', urldecode($post['date_range']));
            $date = explode('|', $post['date_range']);

            if (!empty($date['0']) && !empty($date['1'])) {

                $params = array();

                unset($post['from_date']);
                unset($post['to_date']);

                $params['from_date'] = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $date['0']);
                $params['to_date'] = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $date['1']);


                $price = $this->calculateBookingPrice(array_merge($params, $post));
            }
        }
        header("Content-Type: application/json", true);
        echo json_encode($price);
    }

    function delete_calendar() {
        $this->isAjax = true;

        Object::loadFiles('Model', array('Calendar'));
        $CalendarModel = new CalendarModel();

        $opts = array();

        $calendar_id_arr = array();
        $id_arr = array();

        $opts['t1.id = ?'] = $_POST['calendar_id'];
        $opts['t1.id != ?'] = $_REQUEST['deleted_calendar_id'];

        $this->tpl['calendars'] = $CalendarModel->getAllCalendar($opts);
    }

    function availability() {
        require_once APP_PATH . 'helpers/Calendar/GzCalendarClass.php';

        $this->tpl['calendar'] = new GzCalendarClass();
    }

    function get_summary() {
        $this->isAjax = true;
        require_once APP_PATH . 'helpers/Calendar/GzCalendarClass.php';

        $this->tpl['calendar'] = new GzCalendarClass();
    }

    function getBooking() {
        $this->isAjax = true;

        Object::loadFiles('Model', array('Booking'));
        $BookingModel = new BookingModel();

        $this->tpl['arr'] = array();

        if (!empty($_POST['timestamp'])) {
            $this->tpl['arr'] = $BookingModel->getBookingForDatestamp($_POST['timestamp']);
        }
    }

    function send() {

        Object::loadFiles('Model', array('Option', 'Booking', 'Invoice'));
        $OptionModel = new OptionModel();
        $BookingModel = new BookingModel();
        $InvoiceModel = new InvoiceModel();

        $opts = array();
        $opts['booking_id'] = $_GET['id'];
        $invoice = $InvoiceModel->getAll($opts, 'id desc');

        $booking_details = $BookingModel->getBookingDetails($_GET['id']);
        
        $opts = array();
        $opts['calendar_id'] = $booking_details['calendar_id'];
        $option_arr = $OptionModel->getAllPairValues($opts);

        if (!empty($_POST['send_email'])) {
            
            $id = $_GET['id']; 

            require_once APP_PATH . '/helpers/PHPMailer_5.2.4/class.phpmailer.php';

            try {
                $mail = new PHPMailer(true); //New instance, with exceptions enabled
                $mail->CharSet = "UTF-8";
                //$mail->IsSendmail();  // tell the class to use Sendmail
                $mail->AddReplyTo($option_arr['notify_email'], "Admin");
                $mail->From = $option_arr['notify_email'];
                $mail->FromName = $option_arr['notify_email'];
                $mail->AddAddress($booking_details['email']);
                $mail->Subject = $_POST['subject'];
                $mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
                $mail->WordWrap = 80; // set word wrap
                $mail->MsgHTML($_POST['message']);
               
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

            $_SESSION['status'] = '28';

            Util::redirect(INSTALL_URL . "GzBooking/index");
        }

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

        switch ($booking_details['status']) {
            case 'pending':
                $client_message = Util::replaceToken($option_arr['client_create_email_booking'], $replacement);
                $client_subjetc = $option_arr['client_create_subject_booking'];
                $client_to = $booking_details['email'];

                $admin_message = Util::replaceToken($option_arr['admin_create_email_booking'], $replacement);
                $admin_subjetc = $option_arr['admin_create_subject_booking'];
                $admin_to = $option_arr['notify_email'];

                break;
            case 'confirmed':
                $client_message = Util::replaceToken($option_arr['client_confirmation_email_booking'], $replacement);
                $client_subjetc = $option_arr['client_confirmation_subject_booking'];
                $client_to = $booking_details['email'];

                $admin_message = Util::replaceToken($option_arr['admin_confirmation_email_booking'], $replacement);
                $admin_subjetc = $option_arr['admin_confirmation_subject_booking'];
                $admin_to = $option_arr['notify_email'];

                break;
            case 'cancelled':
                $client_message = Util::replaceToken($option_arr['client_cancellation_email_booking'], $replacement);
                $client_subjetc = $option_arr['client_cancellation_subject_booking'];
                $client_to = $booking_details['email'];

                $admin_message = Util::replaceToken($option_arr['admin_cancellation_email_booking'], $replacement);
                $admin_subjetc = $option_arr['admin_cancellation_subject_booking'];
                $admin_to = $option_arr['notify_email'];

                break;
        }

        $this->tpl['message'] = $client_message;
        $this->tpl['subjetc'] = $client_subjetc;
    }

    function checkCalendarAvailability() {
        $this->isAjax = true;

        Object::loadFiles('Model', array('Calendar'));
        $CalendarModel = new CalendarModel();

        $post = $_POST;

        $this->tpl['calendars'] = array();

        if (!empty($post['date_range'])) {

            $post['date_range'] = str_replace(' - ', '|', urldecode($post['date_range']));
            $date = explode('|', $post['date_range']);

            if (!empty($date['0']) && !empty($date['1'])) {

                $from_date = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $date['0']);
                $to_date = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $date['1']);
            }

            $not_availability_calendar = $this->getNotAvailabilityCalendarArray(array('date_from' => $from_date, 'date_to' => $to_date, 'id' => @$_POST['id']));

            $calendar_id = array();
            $opts = array();
            foreach ($not_availability_calendar as $v) {
                $calendar_id[] = $v['id'];
            }

            if (!empty($calendar_id)) {
                $opts[$CalendarModel->getTable() . '.id NOT IN (:id)'] = array(':id' => implode("', '", $calendar_id));
            }
            $this->tpl['calendars'] = $CalendarModel->getI18nAll($opts);
        }
    }

    function export() {

        $this->isAjax = true;

        Object::loadFiles('Model', array('Booking'));
        $BookingModel = new BookingModel();

        $output = "";

        $query = $BookingModel->from($BookingModel->getTable());

        $bookings = $query->fetchAll();

        foreach ($bookings[0] as $k => $v) {
            $output .= '"' . $k . '",';
        }
        $output .="\n";

        foreach ($bookings as $key => $value) {
            foreach ($value as $k => $v) {
                $output .='"' . $v . '",';
            }
            $output .="\n";
        }

        $filename = "booking_" . time() . ".csv";

        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename=' . $filename);

        echo $output;
        exit;
    }

    function import() {

        if (!empty($_POST['import'])) {

            if (!empty($_FILES['csv_file'])) {

                $filename = time() . '_' . $_FILES['csv_file']['name'];

                $path = INSTALL_PATH . UPLOAD_PATH . 'csv/' . $filename;

                $this->tpl['booking_arr'] = array();

                if (move_uploaded_file($_FILES['csv_file']['tmp_name'], $path)) {

                    $row = 0;
                    if (($handle = fopen($path, "r")) !== FALSE) {
                        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                            $num = count($data);
                            if (!empty($num) && $num > 1 && !empty($data)) {
                                if ($data[0] != 'id') {
                                    $row++;

                                    $this->tpl['booking_arr'][$row] = array();

                                    for ($c = 0; $c < $num; $c++) {
                                        $this->tpl['booking_arr'][$row][] = $data[$c];
                                    }
                                } else {
                                    continue;
                                }
                            }
                        }
                        fclose($handle);
                    }
                    $this->tpl['row_count'] = $row;
                }
            }
        } elseif (!empty($_POST['save'])) {

            if (!empty($_POST['id'])) {

                Object::loadFiles('Model', array('Booking'));
                $BookingModel = new BookingModel();

                foreach ($_POST['id'] as $k => $v) {
                    $data = array();

                    $data['id'] = $v;
                    $data['calendar_id'] = $_POST['calendar_id'][$k];
                    $data['booking_number'] = $_POST['booking_number'][$k];
                    $data['title'] = $_POST['title'][$k];
                    $data['first_name'] = $_POST['first_name'][$k];
                    $data['second_name'] = $_POST['second_name'][$k];
                    $data['phone'] = $_POST['phone'][$k];
                    $data['email'] = $_POST['email'][$k];
                    $data['company'] = $_POST['company'][$k];
                    $data['address_1'] = $_POST['address_1'][$k];
                    $data['address_2'] = $_POST['address_2'][$k];
                    $data['state'] = $_POST['state'][$k];
                    $data['city'] = $_POST['city'][$k];
                    $data['zip'] = $_POST['zip'][$k];
                    $data['country'] = $_POST['country'][$k];
                    $data['fax'] = $_POST['fax'][$k];
                    $data['male'] = $_POST['male'][$k];
                    $data['additional'] = $_POST['additional'][$k];
                    $data['date_from'] = $_POST['date_from'][$k];
                    $data['date_to'] = $_POST['date_to'][$k];
                    $data['status'] = $_POST['status'][$k];
                    $data['promo_code'] = $_POST['promo_code'][$k];
                    $data['calendars_price'] = $_POST['calendars_price'][$k];
                    $data['amount'] = $_POST['amount'][$k];
                    $data['extra_price'] = $_POST['extra_price'][$k];
                    $data['discount'] = $_POST['discount'][$k];
                    $data['total'] = $_POST['total'][$k];
                    $data['tax'] = $_POST['tax'][$k];
                    $data['security'] = $_POST['security'][$k];
                    $data['payment_method'] = $_POST['payment_method'][$k];
                    $data['adults'] = $_POST['adults'][$k];
                    $data['children'] = $_POST['children'][$k];
                    $data['cc_type'] = $_POST['cc_type'][$k];
                    $data['cc_num'] = $_POST['cc_num'][$k];
                    $data['cc_code'] = $_POST['cc_code'][$k];
                    $data['cc_exp_month'] = $_POST['cc_exp_month'][$k];
                    $data['cc_exp_year'] = $_POST['cc_exp_year'][$k];
                    $data['date'] = $_POST['date'][$k];

                    $BookingModel->save($data);
                }
                $status = 30;
                $_SESSION['status'] = $status;

                Util::redirect(INSTALL_URL . "GzBooking/index");
            }
        }
    }

    function googleTest() {
        $this->isAjax = true;

        Object::loadFiles('Model', array('Calendar', 'Option'));
        $CalendarModel = new CalendarModel();
        $OptionModel = new OptionModel();

        $calendar = $CalendarModel->getI18n(1);
        $calendarId = $calendar['googleId'];

        require APP_PATH . 'helpers/google-api-php-client-master/src/Google/autoload.php';

        define('APPLICATION_NAME', 'Google Calendar API Quickstart');
        define('CREDENTIALS_PATH', INSTALL_PATH . '.credentials/calendar-api-quickstart.json');
        define('CLIENT_SECRET_PATH', APP_PATH . 'helpers/google-api-php-client-master/client_secret.json');
        define('SCOPES', implode(' ', array(
            Google_Service_Calendar::CALENDAR)
        ));

        try {
            $client = $this->getClient();
            $service = new Google_Service_Calendar($client);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }

        $event = new Google_Service_Calendar_Event(array(
            'summary' => ' test',
            'location' => 'test',
            'description' => ' test',
            'start' => array(
                'dateTime' => date('Y-m-d', time()) . 'T09:00:00-00:00',
                'timeZone' => 'America/Los_Angeles',
            ),
            'end' => array(
                'dateTime' => date('Y-m-d', (time() + 5 * 86400)) . 'T09:00:00-00:00',
                'timeZone' => 'America/Los_Angeles',
            )
        ));

        try {
            $event = $service->events->insert($calendarId, $event);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }

        echo '1';
    }
}

?>