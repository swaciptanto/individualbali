<?php

require_once CONTROLLERS_PATH . 'App.php';

class GzStatistic extends App {

    var $layout = 'admin';

    function afterFilter() {
        
    }

    function beforeFilter() {

        Object::loadFiles('Model', 'Option');
        $OptionModel = new OptionModel();
        $this->option_arr = $OptionModel->getAllPairValues();
        $this->tpl['option_arr'] = $OptionModel->getAllPairs();
        $this->tpl['option_arr_values'] = $this->option_arr;

        $this->tpl['js_format'] = Util::getJsDateFormta($this->tpl['option_arr_values']['date_format']);
        $this->tpl['iso_format'] = Util::getISODateFormta($this->tpl['option_arr_values']['date_format']);

        date_default_timezone_set($this->tpl['option_arr_values']['timezone']);

        if (!$this->isLoged() && $_REQUEST['action'] != 'login') {
            $_SESSION['err'] = 2;
            Util::redirect(INSTALL_URL . "GzAdmin/login");
        }

        $this->css[] = array('file' => 'admin/gzstyling/bootstrap.min.css', 'path' => CSS_PATH);
        $this->css[] = array('file' => 'admin/gzstyling/font-awesome.min.css', 'path' => CSS_PATH);
        $this->css[] = array('file' => 'admin/gzstyling/ionicons.min.css', 'path' => CSS_PATH);
        $this->css[] = array('file' => 'admin/gzstyling/gzstyle.css', 'path' => CSS_PATH);
        $this->css[] = array('file' => 'jquery-ui.min.css', 'path' => LIBS_PATH . 'jquery/ui/css/smoothness/');
        $this->css[] = array('file' => 'jquery.ui.core.min.css', 'path' => LIBS_PATH . 'jquery/ui/css/smoothness/');
        $this->css[] = array('file' => 'jquery.ui.theme.min.css', 'path' => LIBS_PATH . 'jquery/ui/css/smoothness/');
        $this->css[] = array('file' => 'jquery.ui.tabs.min.css', 'path' => LIBS_PATH . 'jquery/ui/css/smoothness/');
        $this->css[] = array('file' => 'jquery.ui.button.min.css', 'path' => LIBS_PATH . 'jquery/ui/css/smoothness/');
        $this->css[] = array('file' => 'jquery.ui.dialog.min.css', 'path' => LIBS_PATH . 'jquery/ui/css/smoothness/');
        $this->css[] = array('file' => 'jquery.ui.spinner.min.css', 'path' => LIBS_PATH . 'jquery/ui/css/smoothness/');
        $this->css[] = array('file' => 'admin/gzstyling/daterangepicker/daterangepicker-bs3.css', 'path' => CSS_PATH);

        $this->css[] = array('file' => 'admin/admin.css', 'path' => CSS_PATH);
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

        $this->js[] = array('file' => 'gzadmin/plugins/morris/raphael-min.js', 'path' => JS_PATH);
        $this->js[] = array('file' => 'gzadmin/plugins/morris/morris.min.js', 'path' => JS_PATH);
        $this->js[] = array('file' => 'gzadmin/plugins/flot/jquery.flot.min.js', 'path' => JS_PATH);
        $this->js[] = array('file' => 'gzadmin/plugins/flot/jquery.flot.resize.min.js', 'path' => JS_PATH);
        $this->js[] = array('file' => 'gzadmin/plugins/flot/jquery.flot.pie.min.js', 'path' => JS_PATH);
        $this->js[] = array('file' => 'gzadmin/plugins/flot/jquery.flot.categories.min.js', 'path' => JS_PATH);
        $this->js[] = array('file' => 'jquery/jquery-validation-1.13.0/dist/jquery.validate.js', 'path' => LIBS_PATH);
        $this->js[] = array('file' => 'jquery/ui/jquery-ui.min.js', 'path' => LIBS_PATH);
        $this->js[] = array('file' => 'gzadmin/plugins/daterangepicker/daterangepicker.js', 'path' => JS_PATH);
        $this->js[] = array('file' => 'admin.js', 'path' => JS_PATH);
        $this->js[] = array('file' => 'GzStatistic.js', 'path' => JS_PATH);
    }

    function booking() {
        Object::loadFiles('Model', array('Booking'));
        $BookingModel = new BookingModel();

        $arr = array();
        $this->tpl['month_chart'] = array();

        $where = '';

        if (!empty($_POST['nights_from']) && !empty($_POST['nights_to'])) {

            if ($_POST['nights_from'] > $_POST['nights_to']) {
                $nights_to = $_POST['nights_from'];
                $nights_from = $_POST['nights_to'];
            } else {
                $nights_to = $_POST['nights_to'];
                $nights_from = $_POST['nights_from'];
            }

            $where = "AND nights BETWEEN " . $nights_from . " AND " . $nights_to . " ";
        } elseif (!empty($_POST['nights_from'])) {
            $where = "AND nights > " . $_POST['nights_from'] . " ";
        } elseif (!empty($_POST['nights_to'])) {
            $where = "AND nights < " . $_POST['nights_from'] . " ";
        }

        if (!empty($_POST['date_range'])) {

            $opts = array();

            $date = explode('-', $_POST['date_range']);

            if (!empty($date['0']) && !empty($date['1'])) {

                $from = strtotime(urldecode($date['0']));
                $to = strtotime(urldecode($date['1']));
            }
            $days_range = ($to - $from) / 86400;

            if ($days_range <= 60) {

                for ($i = $from; $i <= $to; $i = strtotime('+1 days', $i)) {

                    $from_timestamp = $i;
                    $to_timestamp = strtotime('+1 days', $i);

                    $sql = "SELECT count(id) as count FROM " . $BookingModel->getTable() . " WHERE date_from BETWEEN " . $from_timestamp . "  AND " . $to_timestamp . " " . $where . " ";

                    $arr = $BookingModel->execute($sql);

                    $this->tpl['month_chart'][date('d', $i)] = $arr[0];
                }
            } else {
                for ($i = $from; $i <= $to; $i = strtotime('+1 month', $i)) {

                    $from_timestamp = $from;
                    $to_timestamp = strtotime('+1 month', $i);

                    $sql = "SELECT count(id) as count FROM " . $BookingModel->getTable() . " WHERE date_from BETWEEN " . $from_timestamp . "  AND " . $to_timestamp . " " . $where . " ";

                    $arr = $BookingModel->execute($sql);

                    $this->tpl['month_chart'][date('M', $i)] = $arr[0];
                }
            }
        } else {

            for ($i = (date('n') - 11); $i <= date('n'); $i++) {

                $from_timestamp = mktime(0, 0, 0, $i, 1, date('Y'));
                $to_timestamp = mktime(0, 0, 0, $i + 1, 0, date('Y'));

                $sql = "SELECT count(id) as count FROM " . $BookingModel->getTable() . " WHERE date_from BETWEEN " . $from_timestamp . "  AND " . $to_timestamp . " " . $where . " ";

                $arr = $BookingModel->execute($sql);

                $this->tpl['month_chart'][date('M', mktime(0, 0, 0, $i, 1, date('Y')))] = $arr[0];
            }
        }
    }

    function amount() {
        Object::loadFiles('Model', array('Booking'));
        $BookingModel = new BookingModel();

        $arr = array();
        $this->tpl['month_chart'] = array();

        $where = '';

        if (!empty($_POST['date_range'])) {

            $opts = array();

            $date = explode('-', $_POST['date_range']);

            if (!empty($date['0']) && !empty($date['1'])) {

                $from = strtotime(urldecode($date['0']));
                $to = strtotime(urldecode($date['1']));
            }
            $days_range = ($to - $from) / 86400;

            if ($days_range <= 60) {

                for ($i = $from; $i <= $to; $i = strtotime('+1 days', $i)) {

                    $from_timestamp = $i;
                    $to_timestamp = strtotime('+1 days', $i);

                    $sql = "SELECT sum(total) as total FROM " . $BookingModel->getTable() . " WHERE date_from BETWEEN " . $from_timestamp . "  AND " . $to_timestamp . " " . $where . " ";

                    $arr = $BookingModel->execute($sql);

                    $this->tpl['month_chart'][date('Y-m-d', $i)] = $arr[0];
                }
            } else {
                for ($i = $from; $i <= $to; $i = strtotime('+1 month', $i)) {

                    $from_timestamp = $from;
                    $to_timestamp = strtotime('+1 month', $i);

                    $sql = "SELECT sum(total) as total FROM " . $BookingModel->getTable() . " WHERE date_from BETWEEN " . $from_timestamp . "  AND " . $to_timestamp . " " . $where . " ";

                    $arr = $BookingModel->execute($sql);

                    $this->tpl['month_chart'][date('Y-m-d', $i)] = $arr[0];
                }
            }
        } else {

            for ($i = (date('n') - 11); $i <= date('n'); $i++) {

                $from_timestamp = mktime(0, 0, 0, $i, 1, date('Y'));
                $to_timestamp = mktime(0, 0, 0, $i + 1, 0, date('Y'));

                $sql = "SELECT sum(total) as total FROM " . $BookingModel->getTable() . " WHERE date_from BETWEEN " . $from_timestamp . "  AND " . $to_timestamp . " " . $where . " ";

                $arr = $BookingModel->execute($sql);

                $this->tpl['month_chart'][date('Y-m-d', $from_timestamp)] = $arr[0];
            }
        }
    }

    function people() {
        Object::loadFiles('Model', array('Booking'));
        $BookingModel = new BookingModel();

        $arr = array();
        $this->tpl['month_chart'] = array();

        $where = '';

        if (!empty($_POST['date_range'])) {

            $opts = array();

            $date = explode('-', $_POST['date_range']);

            if (!empty($date['0']) && !empty($date['1'])) {

                $from = strtotime(urldecode($date['0']));
                $to = strtotime(urldecode($date['1']));
            }
            $days_range = ($to - $from) / 86400;

            if ($days_range <= 60) {

                for ($i = $from; $i <= $to; $i = strtotime('+1 days', $i)) {

                    $from_timestamp = $i;
                    $to_timestamp = strtotime('+1 days', $i);

                    $sql = "SELECT sum(adults) as adults, sum(children) as children FROM " . $BookingModel->getTable() . " WHERE date_from BETWEEN " . $from_timestamp . "  AND " . $to_timestamp . " " . $where . " ";

                    $arr = $BookingModel->execute($sql);

                    $this->tpl['month_chart'][date('Y-m-d', $i)] = $arr[0];
                }
            } else {
                for ($i = $from; $i <= $to; $i = strtotime('+1 month', $i)) {

                    $from_timestamp = $from;
                    $to_timestamp = strtotime('+1 month', $i);

                    $sql = "SELECT sum(adults) as adults, sum(children) as children FROM " . $BookingModel->getTable() . " WHERE date_from BETWEEN " . $from_timestamp . "  AND " . $to_timestamp . " " . $where . " ";

                    $arr = $BookingModel->execute($sql);

                    $this->tpl['month_chart'][date('Y-m-d', $i)] = $arr[0];
                }
            }
        } else {

            for ($i = (date('n') - 11); $i <= date('n'); $i++) {

                $from_timestamp = mktime(0, 0, 0, $i, 1, date('Y'));
                $to_timestamp = mktime(0, 0, 0, $i + 1, 0, date('Y'));

                $sql = "SELECT sum(adults) as adults, sum(children) as children FROM " . $BookingModel->getTable() . " WHERE date_from BETWEEN " . $from_timestamp . "  AND " . $to_timestamp . " " . $where . " ";

                $arr = $BookingModel->execute($sql);

                $this->tpl['month_chart'][date('Y-m-d', $from_timestamp)] = $arr[0];
            }
        }
    }

}

?>