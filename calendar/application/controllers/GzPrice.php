<?php

require_once CONTROLLERS_PATH . 'App.php';

class GzPrice extends App {

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

        if (!($this->isLoged() && $this->isAdmin()) && $_REQUEST['action'] != 'login') {
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

        $this->js[] = array('file' => 'jquery/jquery-validation-1.13.0/dist/jquery.validate.js', 'path' => LIBS_PATH);
        $this->js[] = array('file' => 'jquery/ui/jquery-ui.min.js', 'path' => LIBS_PATH);

        $this->js[] = array('file' => 'gzadmin/plugins/daterangepicker/daterangepicker.js', 'path' => JS_PATH);

        switch (@$_REQUEST['action']) {
            case 'price':
                $this->js[] = array('file' => '/ajax-upload/jquery.form.js', 'path' => JS_PATH);
                break;
        }

        $this->js[] = array('file' => 'admin.js', 'path' => JS_PATH);
        $this->js[] = array('file' => 'GzPrice.js', 'path' => JS_PATH);
    }

    function index() {
        
    }

    function price() {

        Object::loadFiles('Model', array('Calendar', 'Price'));
        $CalendarModel = new CalendarModel();
        $PriceModel = new PriceModel();

        $this->tpl['calendars'] = $CalendarModel->getI18nAll();

        if (!empty($_GET['id'])) {
            $this->tpl['default_calendar'] = $CalendarModel->getI18n($_GET['id']);
            $opts['calendar_id'] = $_GET['id'];
        } elseif (!empty($this->tpl['calendars'][0])) {
            $opts['calendar_id'] = $this->tpl['calendars'][0]['id'];
            $this->tpl['default_calendar'] = $this->tpl['calendars'][0];
        }
        $this->tpl['price_plan'] = $PriceModel->getAll($opts, 'id');
    }

    function add_price_plan() {
        $this->isAjax = true;

        Object::loadFiles('Model', array('Calendar', 'Price'));
        $CalendarModel = new CalendarModel();
        $PriceModel = new PriceModel();

        $data = array();

        $_POST['date_range'] = str_replace(' - ', '|', $_POST['date_range']);
        $date = explode('|', $_POST['date_range']);

        if (!empty($date['0']) && !empty($date['1'])) {

            $data['from_date'] = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $date['0']);
            $data['to_date'] = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $date['1']);
        }

        unset($_POST['from_date']);
        unset($_POST['to_date']);
        
        //modified: applied rate (from drupal villa rate) to all weekdays
        $data['monday'] = $_POST['rate'];
        $data['tuesday'] = $_POST['rate'];
        $data['wednesday'] = $_POST['rate'];
        $data['thursday'] = $_POST['rate'];
        $data['friday'] = $_POST['rate'];
        $data['saturday'] = $_POST['rate'];
        $data['sunday'] = $_POST['rate'];

        $PriceModel->save(array_merge($_POST, $data));

        $this->tpl['calendars'] = $CalendarModel->getI18nAll();

        $opts = array();

        if (!empty($_REQUEST['calendar_id'])) {

            $opts['calendar_id'] = $_REQUEST['calendar_id'];
            $this->tpl['default_calendar'] = $CalendarModel->getI18n($_REQUEST['calendar_id']);
        }
        $this->tpl['price_plan'] = $PriceModel->getAll($opts, 'id desc');
    }

    function delete() {
        $this->isAjax = true;

        $id = $_REQUEST['id'];

        Object::loadFiles('Model', array('Calendar', 'Price'));
        $CalendarModel = new CalendarModel();
        $PriceModel = new PriceModel();

        $PriceModel->deleteFrom($PriceModel->getTable())
                ->where('id', $id)->execute();

        $opts = array();

        if (!empty($_REQUEST['calendar_id'])) {

            $opts['calendar_id'] = $_REQUEST['calendar_id'];
            $this->tpl['default_calendar'] = $CalendarModel->getI18n($_REQUEST['calendar_id']);
        }

        $this->tpl['calendars'] = $CalendarModel->getI18nAll();
        $this->tpl['price_plan'] = $PriceModel->getAll($opts, 'id desc');
    }

    //modified: add new
    function get_frm_add_price_plan() {
        $this->isAjax = true;
        
        Object::loadFiles(
                'Model',
                array(
                    'Calendar',
                    'Price',
                    'DrupalRateLow',
                    'DrupalRateHigh',
                    'DrupalRatePeak',
                    'DrupalRateEaster',
                    'DrupalRateChineseNewYear',
                    )
                );
        $CalendarModel = new CalendarModel();
        $DrupalRateLowModel = new DrupalRateLowModel();
        $DrupalRateHighModel = new DrupalRateHighModel();
        $DrupalRatePeakModel = new DrupalRatePeakModel();
        $DrupalRateEasterModel = new DrupalRateEasterModel();
        $DrupalRateChineseNewYearModel = new DrupalRateChineseNewYearModel();

        if (!empty($_REQUEST['calendar_id'])) {
            $this->tpl['default_calendar'] = $CalendarModel->getI18n($_REQUEST['calendar_id']);
            
            $calendar_data = $CalendarModel->getI18n($_REQUEST['calendar_id']);
            if ((int)$calendar_data['villa_node_id'] > 0) {
                $this->tpl['rate_prices'] = array(
                    (float)$DrupalRateLowModel->get($calendar_data['villa_node_id'])['field_rate_low_value'] => 'Low',
                    (float)$DrupalRateHighModel->get($calendar_data['villa_node_id'])['field_rate_high_value'] => 'High',
                    (float)$DrupalRatePeakModel->get($calendar_data['villa_node_id'])['field_rate_peak_value'] => 'Peak',
                    (float)$DrupalRateEasterModel->get($calendar_data['villa_node_id'])['field_rate_easter_value'] => 'Easter',
                    (float)$DrupalRateChineseNewYearModel->get($calendar_data['villa_node_id'])['field_rate_chinese_new_year_value'] => 'Chinese New Year',
                );
            } else {
                $this->tpl['rate_prices'] = array();
            }
        }
    }
    
    function get_frm_edit_price_plan() {
        $this->isAjax = true;

        //modified: add new model
        Object::loadFiles('Model', array('Calendar', 'Price', 'DrupalRateLow', 'DrupalRateHigh', 'DrupalRatePeak', 'DrupalRateEaster', 'DrupalRateChineseNewYear'));
        $CalendarModel = new CalendarModel();
        $PriceModel = new PriceModel();
        $DrupalRateLowModel = new DrupalRateLowModel();
        $DrupalRateHighModel = new DrupalRateHighModel();
        $DrupalRatePeakModel = new DrupalRatePeakModel();
        $DrupalRateEasterModel = new DrupalRateEasterModel();
        $DrupalRateChineseNewYearModel = new DrupalRateChineseNewYearModel();

        if (!empty($_REQUEST['id'])) {
            $this->tpl['price_plan'] = $PriceModel->get($_REQUEST['id']);
            //modified: add get rate price from drupal data villa
            $calendar_data = $CalendarModel->getI18n($_REQUEST['calendar_id']);
            $this->tpl['default_calendar'] = $calendar_data;
            if ((int)$calendar_data['villa_node_id'] > 0) {
                $this->tpl['rate_prices'] = array(
                    (float)$DrupalRateLowModel->get($calendar_data['villa_node_id'])['field_rate_low_value'] => 'Low',
                    (float)$DrupalRateHighModel->get($calendar_data['villa_node_id'])['field_rate_high_value'] => 'High',
                    (float)$DrupalRatePeakModel->get($calendar_data['villa_node_id'])['field_rate_peak_value'] => 'Peak',
                    (float)$DrupalRateEasterModel->get($calendar_data['villa_node_id'])['field_rate_easter_value'] => 'Easter',
                    (float)$DrupalRateChineseNewYearModel->get($calendar_data['villa_node_id'])['field_rate_chinese_new_year_value'] => 'Chinese New Year',
                );
            } else {
                $this->tpl['rate_prices'] = array();
            }
        }
    }

    function edit() {
        $this->isAjax = true;

        Object::loadFiles('Model', array('Calendar', 'Price'));
        $CalendarModel = new CalendarModel();
        $PriceModel = new PriceModel();

        $data = array();

        $_POST['date_range'] = str_replace(' - ', '|', $_POST['date_range']);
        $date = explode('|', $_POST['date_range']);
        
        //modified: applied rate (from drupal villa rate) to all weekdays
        $data['monday'] = $_POST['rate'];
        $data['tuesday'] = $_POST['rate'];
        $data['wednesday'] = $_POST['rate'];
        $data['thursday'] = $_POST['rate'];
        $data['friday'] = $_POST['rate'];
        $data['saturday'] = $_POST['rate'];
        $data['sunday'] = $_POST['rate'];

        if (!empty($date['0']) && !empty($date['1'])) {

            $data['from_date'] = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $date['0']);
            $data['to_date'] = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $date['1']);
        }

        unset($_POST['from_date']);
        unset($_POST['to_date']);

        $PriceModel->update(array_merge($_POST, $data));

        $opts = array();

        if (!empty($_REQUEST['calendar_id'])) {

            $opts['calendar_id'] = $_REQUEST['calendar_id'];
            $this->tpl['default_calendar'] = $CalendarModel->getI18n($_REQUEST['calendar_id']);
        }

        $this->tpl['calendars'] = $CalendarModel->getI18nAll();
        $this->tpl['price_plan'] = $PriceModel->getAll($opts, 'id desc');        
    }

    function get_price_plan_table() {
        $this->isAjax = true;

        Object::loadFiles('Model', array('Calendar', 'Price'));
        $CalendarModel = new CalendarModel();
        $PriceModel = new PriceModel();

        $opts = array();

        if (!empty($_REQUEST['calendar_id'])) {

            $opts['calendar_id'] = $_REQUEST['calendar_id'];
            $this->tpl['default_calendar'] = $CalendarModel->getI18n($_REQUEST['calendar_id']);
        }

        $this->tpl['calendars'] = $CalendarModel->getI18nAll();
        $this->tpl['price_plan'] = $PriceModel->getAll($opts, 'id desc');
    }

}
