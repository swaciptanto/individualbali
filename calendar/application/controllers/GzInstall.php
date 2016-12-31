<?php

require_once CONTROLLERS_PATH . 'App.php';

class GzInstall extends App {

    var $layout = 'admin';

    function beforeFilter() {
        Object::loadFiles('Model', 'Option');
        $OptionModel = new OptionModel();
        $this->option_arr = $OptionModel->getAllPairValues();
        $this->tpl['option_arr'] = $OptionModel->getAllPairs();
        $this->tpl['option_arr_values'] = $this->option_arr;

        date_default_timezone_set($this->tpl['option_arr_values']['timezone']);

        $this->tpl['js_format'] = Util::getJsDateFormta($this->tpl['option_arr_values']['date_format']);

        if (!$this->isLoged() && $_REQUEST['action'] != 'login') {
            $_SESSION['err'] = 2;
            Util::redirect(INSTALL_URL . "GzAdmin/login");
        }

        $this->css[] = array('file' => 'admin/gzstyling/bootstrap.min.css', 'path' => CSS_PATH);
        $this->css[] = array('file' => 'admin/gzstyling/font-awesome.min.css', 'path' => CSS_PATH);
        $this->css[] = array('file' => 'admin/gzstyling/ionicons.min.css', 'path' => CSS_PATH);
        $this->css[] = array('file' => 'admin/gzstyling/gzstyle.css', 'path' => CSS_PATH);
        $this->css[] = array('file' => 'gzadmin/plugins/select2/select2.css', 'path' => JS_PATH);
        $this->css[] = array('file' => 'admin/admin.css', 'path' => CSS_PATH);

        $this->js[] = array('file' => 'jquery/jquery-1.9.1.min.js', 'path' => LIBS_PATH);
        $this->js[] = array('file' => 'gzadmin/bootstrap.min.js', 'path' => JS_PATH);
        $this->js[] = array('file' => 'gzadmin/gzadmin/app.js', 'path' => JS_PATH);
        $this->js[] = array('file' => 'gzadmin/plugins/select2/select2.min.js', 'path' => JS_PATH);
        $this->js[] = array('file' => 'jquery/jquery-validation-1.13.0/dist/jquery.validate.js', 'path' => LIBS_PATH);
        $this->js[] = array('file' => 'jquery/ui/jquery-ui.min.js', 'path' => LIBS_PATH);
        $this->js[] = array('file' => 'admin.js', 'path' => JS_PATH);
        $this->js[] = array('file' => 'GzInstall.js', 'path' => JS_PATH);
    }

    function index() {
        Object::loadFiles('Model', array('Calendar'));
        $CalendarModel = new CalendarModel();

        $this->tpl['calendars'] = $CalendarModel->getI18nAll();
    }

    function getCode() {
        $this->isAjax = true;
    }
    
    function inquiry_form(){
        $this->isAjax = true;
    }

}
?>

