<?php

require_once CONTROLLERS_PATH . 'App.php';

class GzAdmin extends App {

    var $layout = 'admin';

    function beforeFilter() {
        Object::loadFiles('Model', 'Option');
        $OptionModel = new OptionModel();
        $this->option_arr = $OptionModel->getAllPairValues();
        $this->tpl['option_arr'] = $OptionModel->getAllPairs();
        $this->tpl['option_arr_values'] = $this->option_arr;

        date_default_timezone_set($this->tpl['option_arr_values']['timezone']);

        $this->tpl['js_format'] = Util::getJsDateFormta($this->tpl['option_arr_values']['date_format']);

        if (!$this->isLoged() && @$_REQUEST['action'] != 'login') {

            Util::redirect($_SERVER['PHP_SELF'] . "?controller=GzAdmin&action=login&err=1");
        }

        $this->css[] = array('file' => 'admin/gzstyling/bootstrap.min.css', 'path' => CSS_PATH);
        $this->css[] = array('file' => 'admin/gzstyling/font-awesome.min.css', 'path' => CSS_PATH);
        $this->css[] = array('file' => 'admin/gzstyling/ionicons.min.css', 'path' => CSS_PATH);
        $this->css[] = array('file' => 'admin/gzstyling/gzstyle.css', 'path' => CSS_PATH);
        $this->css[] = array('file' => 'admin/gzstyling/daterangepicker/daterangepicker-bs3.css', 'path' => CSS_PATH);
        $this->css[] = array('file' => 'admin/admin.css', 'path' => CSS_PATH);

        $this->js[] = array('file' => 'jquery/jquery-1.9.1.min.js', 'path' => LIBS_PATH);
        $this->js[] = array('file' => 'gzadmin/bootstrap.min.js', 'path' => JS_PATH);
        $this->js[] = array('file' => 'gzadmin/gzadmin/app.js', 'path' => JS_PATH);
        $this->js[] = array('file' => 'admin.js', 'path' => JS_PATH);

        switch (@$_REQUEST['action']) {
            case 'dashboard':
                $this->js[] = array('file' => 'gzadmin/plugins/morris/raphael-min.js', 'path' => JS_PATH);
                $this->js[] = array('file' => 'gzadmin/plugins/morris/morris.min.js', 'path' => JS_PATH);
                $this->js[] = array('file' => 'gzadmin/plugins/flot/jquery.flot.min.js', 'path' => JS_PATH);
                $this->js[] = array('file' => 'gzadmin/plugins/flot/jquery.flot.resize.min.js', 'path' => JS_PATH);
                $this->js[] = array('file' => 'gzadmin/plugins/flot/jquery.flot.pie.min.js', 'path' => JS_PATH);
                $this->js[] = array('file' => 'gzadmin/plugins/flot/jquery.flot.categories.min.js', 'path' => JS_PATH);

                break;
        }

        $this->js[] = array('file' => 'jquery/jquery-validation-1.13.0/dist/jquery.validate.js', 'path' => LIBS_PATH);
        $this->js[] = array('file' => 'jquery/ui/jquery-ui.min.js', 'path' => LIBS_PATH);
        $this->js[] = array('file' => 'login.js', 'path' => JS_PATH);
        $this->js[] = array('file' => 'gzadmin/plugins/daterangepicker/daterangepicker.js', 'path' => JS_PATH);
        $this->js[] = array('file' => 'GzAdmin.js', 'path' => JS_PATH);
    }

    function index() {
        //modified: change redirect
        //Util::redirect($_SERVER['PHP_SELF'] . "?controller=GzAdmin&action=dashboard");
        Util::redirect($_SERVER['PHP_SELF'] . "?controller=GzCalendar");
    }

    function login() {

        $this->layout = 'login';

        if (isset($_POST['login_user']) && $_POST['login_user'] == '1') {

            Object::loadFiles('Model', 'User');
            $UserModel = new UserModel();

            $opts['email'] = $_POST['email'];
            $opts['password'] = md5($_POST['password']);
            $opts['status'] = 'T';

            $user = $UserModel->getAll($opts);

            if (count($user) != 1) {
                # Login failed
                Util::redirect($_SERVER['PHP_SELF'] . "?controller=GzAdmin&action=login&err=1");
            } else {
                $user = $user[0];

                if (!in_array($user['type'], array(1, 2))) {
                    # Login denied
                    Util::redirect($_SERVER['PHP_SELF'] . "?controller=GzAdmin&action=login&err=2");
                }
                if ($user['status'] != 'T') {
                    # Login forbidden
                    Util::redirect($_SERVER['PHP_SELF'] . "?controller=GzAdmin&action=login&err=3");
                }

                $_SESSION[$this->default_user] = $user;

                $data['id'] = $user['id'];
                $data['last_login'] = date("Y-m-d H:i:s");
                $UserModel->update($data);

                if ($this->isAdmin()) {
                    //modified: change redirect
                    //Util::redirect($_SERVER['PHP_SELF'] . "?controller=GzAdmin&action=dashboard");
                    Util::redirect($_SERVER['PHP_SELF'] . "?controller=GzCalendar");
                //modified: add new logic
                } elseif ($this->isEditor()) {
                    Util::redirect($_SERVER['PHP_SELF'] . "?controller=GzAdmin&action=dashboard");
                }
            }
            return false;
        }
    }

    function dashboard() {
        Object::loadFiles('Model', array('Booking', 'Calendar'));
        $BookingModel = new BookingModel();
        $CalendarModel = new CalendarModel();

        $opts = array();

        $arr = $BookingModel->getAll($opts, 'id desc', '7');

        $this->tpl['arr'] = $arr;
        $this->tpl['chart'] = array();

        for ($i = (date('n') - 11); $i <= date('n'); $i++) {

            $from_timestamp = mktime(0, 0, 0, $i, 1, date('Y'));
            $to_timestamp = mktime(0, 0, 0, $i + 1, 0, date('Y'));

            $sql = "SELECT count(id) as count FROM " . $BookingModel->getTable() . " WHERE date_from BETWEEN " . $from_timestamp . "  AND " . $to_timestamp . "  ";

            $arr = $BookingModel->execute($sql);

            $this->tpl['chart']['booking'][date('M', mktime(0, 0, 0, $i, 1, date('Y')))] = $arr[0];
        }
        for ($i = (date('n') - 11); $i <= date('n'); $i++) {

            $from_timestamp = mktime(0, 0, 0, $i, 1, date('Y'));
            $to_timestamp = mktime(0, 0, 0, $i + 1, 0, date('Y'));

            $sql = "SELECT sum(adults) as adults, sum(children) as children FROM " . $BookingModel->getTable() . " WHERE date_from BETWEEN " . $from_timestamp . "  AND " . $to_timestamp . " ";

            $arr = $BookingModel->execute($sql);

            $this->tpl['chart']['people'][date('Y-m-d', $from_timestamp)] = $arr[0];
        }

        $sql = "SELECT count(date_to) as departure FROM " . $BookingModel->getTable() . " WHERE date_to = '" . strtotime(date('Y-m-d', time()). '00:00:00') . "' ";
        $arr = $BookingModel->execute($sql);
        $this->tpl['departure'] = $arr[0];

        $sql = "SELECT count(date_from) as arrival FROM " . $BookingModel->getTable() . " WHERE date_from = '" . strtotime(date('Y-m-d', time()). '00:00:00') . "' ";
        $arr = $BookingModel->execute($sql);
        $this->tpl['arrival'] = $arr[0];

        $sql = "SELECT count(id) as today_reservation FROM " . $BookingModel->getTable() . " WHERE date > '" . date("Y-m-d") . ' 00:00:00.000000' . "' AND date < '" . date("Y-m-d", time() + 86400) . ' 00:00:00.000000' . "' ";
        $arr = $BookingModel->execute($sql);
        $this->tpl['today_reservation'] = $arr[0];

        $not_availability_calendar = $this->getNotAvailabilityCalendarArray(array('date_from' => time(), 'date_to' => time()));

        $calendar_id = array();
        foreach ($not_availability_calendar as $v) {

            $calendar_id[] = $v['id'];
        }
        $opts = array();
        if (!empty($calendar_id)) {
            $opts["`t1`.`id` NOT IN ('" . implode("', '", $calendar_id) . "') AND 1 = ?"] = "1";
        }
        $calendars_arr = array();
        $calendars_arr = $CalendarModel->getAll($opts);

        if (!empty($calendars_arr)) {
            $this->tpl['availability_calendars'] = count($calendars_arr);
        } else {
            $this->tpl['availability_calendars'] = 0;
        }

        require_once APP_PATH . 'helpers/Calendar/GzCalendarClass.php';

        $this->tpl['calendar'] = new GzCalendarClass();
    }

    function logout() {
        if ($this->isLoged()) {
            unset($_SESSION[$this->default_user]);
            Util::redirect($_SERVER['PHP_SELF'] . "?controller=GzAdmin&action=login");
        } else {
            Util::redirect($_SERVER['PHP_SELF'] . "?controller=GzAdmin&action=login");
        }
    }

    function getMultyCalendarCSS() {
        $this->layout = 'empty';
        $this->replaceMultyCalendarCSS();
    }

    function update_db() {
        $this->layout = 'install';

        Object::loadFiles('Model', array('App'));
        $AppModel = new AppModel();

        $string = file_get_contents('application/config/update_db2.sql');
        preg_match_all('/DROP\s+TABLE(\s+IF\s+EXISTS)?\s+`(\w+)`/i', $string, $match);

        if (count($match[0]) > 0) {
            $arr = array();
            foreach ($match[2] as $k => $table) {

                $sql = "SHOW TABLES FROM `" . $AppModel->database . "` LIKE '" . $AppModel->prefix . $table . "'";

                $arr = $AppModel->execute($sql);

                if (!empty($arr)) {
                    $_SESSION['message'] = "Database already has an updated";
                }
            }
        }

        if (!empty($_POST['update_db'])) {
            $file = 'application/config/update_db2.sql';

            $prefix = $AppModel->prefix;

            $string = file_get_contents($file);
            $string = preg_replace(
                    array('/INSERT\s+INTO\s+`/', '/DROP\s+TABLE\s+IF\s+EXISTS\s+`/', '/CREATE\s+TABLE\s+IF\s+NOT\s+EXISTS\s+`/', '/DROP\s+TABLE\s+`/', '/CREATE\s+TABLE\s+`/'), array('INSERT INTO `' . $prefix, 'DROP TABLE IF EXISTS `' . $prefix, 'CREATE TABLE IF NOT EXISTS `' . $prefix, 'DROP TABLE `' . $prefix, 'CREATE TABLE `' . $prefix), $string);

            $arr = preg_split('/;(\s+)?\n/', $string);
            foreach ($arr as $v) {
                $v = trim($v);
                if (!empty($v)) {
                    $AppModel->execute($v);
                }
            }

            $_SESSION['status'] = "Database has been updated";
        }
    }

}
?>

