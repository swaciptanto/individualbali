<?php

require_once CONTROLLERS_PATH . 'App.php';

class GzDiscount extends App {

    var $layout = 'admin';

    function beforeFilter() {

        Object::loadFiles('Model', 'Option');
        $OptionModel = new OptionModel();
        $this->option_arr = $OptionModel->getAllPairValues();
        $this->tpl['option_arr'] = $OptionModel->getAllPairs();
        $this->tpl['option_arr_values'] = $this->option_arr;

        $this->tpl['js_format'] = Util::getJsDateFormta($this->tpl['option_arr_values']['date_format']);
        $this->tpl['iso_format'] = Util::getISODateFormta($this->tpl['option_arr_values']['date_format']);

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
            case 'discount':
                $this->js[] = array('file' => '/ajax-upload/jquery.form.js', 'path' => JS_PATH);
                break;
        }

        $this->js[] = array('file' => 'admin.js', 'path' => JS_PATH);
        $this->js[] = array('file' => 'GzDiscount.js', 'path' => JS_PATH);
    }

    function discount() {

        Object::loadFiles('Model', array('Discount'));
        $DiscountModel = new DiscountModel();

        $opts = array();

        $this->tpl['discounts'] = $DiscountModel->getAll($opts, 'id desc');
    }

    function get_discount_table() {
        $this->isAjax = true;

        Object::loadFiles('Model', array('Discount'));
        $DiscountModel = new DiscountModel();

        $opts = array();

        $this->tpl['discounts'] = $DiscountModel->getAll($opts, 'id desc');
    }

    function add_discount() {
        $this->isAjax = true;

        Object::loadFiles('Model', array('Discount'));
        $DiscountModel = new DiscountModel();

        $_POST['date_range'] = str_replace(' - ', '|', $_POST['date_range']);
        $date = explode('|', $_POST['date_range']);

        if (!empty($date['0']) && !empty($date['1'])) {

            $data['from_date'] = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $date['0']);
            $data['to_date'] = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $date['1']);
        }

        unset($_POST['from_date']);
        unset($_POST['to_date']);

        $DiscountModel->save(array_merge($data, $_POST));

        $opts = array();

        $this->tpl['discounts'] = $DiscountModel->getAll($opts, 'id desc');
    }

    function get_frm_edit_discount() {
        $this->isAjax = true;

        Object::loadFiles('Model', array('Discount'));
        $DiscountModel = new DiscountModel();

        if (!empty($_REQUEST['id'])) {
            $this->tpl['discount'] = $DiscountModel->get($_REQUEST['id']);
        }
    }

    function edit() {
        $this->isAjax = true;

        Object::loadFiles('Model', array('Discount'));
        $DiscountModel = new DiscountModel();

        $data = array();

        $_POST['date_range'] = str_replace(' - ', '|', $_POST['date_range']);
        $date = explode('|', $_POST['date_range']);

        if (!empty($date['0']) && !empty($date['1'])) {

            $data['from_date'] = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $date['0']);
            $data['to_date'] = Util::dateToTimestamp($this->tpl['option_arr_values']['date_format'], $date['1']);
        }

        unset($_POST['from_date']);
        unset($_POST['to_date']);

        $DiscountModel->update(array_merge($data, $_POST));

        $opts = array();

        $this->tpl['discounts'] = $DiscountModel->getAll($opts, 'id desc');
    }

    function delete() {
        $this->isAjax = true;

        $id = $_REQUEST['id'];

        Object::loadFiles('Model', array('Discount'));
        $DiscountModel = new DiscountModel();

        $DiscountModel->deleteFrom($DiscountModel->getTable())
                ->where('id', $id)->execute();

        $opts = array();

        $this->tpl['discounts'] = $DiscountModel->getAll($opts, 'id desc');
    }

}

?>