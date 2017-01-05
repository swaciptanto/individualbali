<?php

require_once CONTROLLERS_PATH . 'App.php';

class GzInvoice extends App {

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

        //$this->js[] = array('file' => 'ajax-upload/das.js', 'path' => JS_PATH);
        $this->js[] = array('file' => 'ajax-upload/jquery.form.js', 'path' => JS_PATH);

        $this->js[] = array('file' => 'jquery/jquery-validation-1.13.0/dist/jquery.validate.js', 'path' => LIBS_PATH);
        $this->js[] = array('file' => 'jquery/ui/jquery-ui.min.js', 'path' => LIBS_PATH);

        $this->js[] = array('file' => 'gzadmin/plugins/daterangepicker/daterangepicker.js', 'path' => JS_PATH);

        if ($_REQUEST['action'] == 'sendEmail') {
            $this->js[] = array('file' => 'jquery/tinymce/tinymce.min.js', 'path' => LIBS_PATH);
        }

        $this->js[] = array('file' => 'admin.js', 'path' => JS_PATH);
        $this->js[] = array('file' => 'GzInvoice.js', 'path' => JS_PATH);
    }

    function create() {
        Object::loadFiles('Model', array('Booking', 'Invoice'));
        $BookingModel = new BookingModel();
        $InvoiceModel = new InvoiceModel();

        if (!empty($_POST['create_invoice'])) {

            $data = array();

            $data['invoice_number'] = Util::incrementalHash(10);

            $id = $InvoiceModel->save(array_merge($_POST, $data));

            if (!empty($id)) {

                $_SESSION['status'] = 22;
            } else {
                 $_SESSION['status'] = 23;
            }

            Util::redirect(INSTALL_URL . "GzInvoice/index");
        }

        $opts = array();
        $this->tpl['booking_arr'] = $BookingModel->getAll($opts, 'id desc');
    }

    function edit() {

        Object::loadFiles('Model', array('Booking', 'Invoice'));
        $BookingModel = new BookingModel();
        $InvoiceModel = new InvoiceModel();

        if (!empty($_POST['edit_invoice'])) {
            $data = array();

            $id = $InvoiceModel->update(array_merge($data, $_POST));

            if (!empty($id)) {
                $_SESSION['status'] = 26;
            } else {
                $_SESSION['status'] = 27;
            }

            Util::redirect(INSTALL_URL . "GzInvoice/index");
        }

        $id = $_GET['id'];

        $this->tpl['invoice'] = $InvoiceModel->get($id);
        $this->tpl['booking_arr'] = $BookingModel->getAll();
    }

    function index() {

        Object::loadFiles('Model', array('Invoice'));
        $InvoiceModel = new InvoiceModel();

        $opts = array();

        $arr = $InvoiceModel->getAll(array_merge($opts));

        $this->tpl['arr'] = $arr;
    }

    function delete() {
        $this->isAjax = true;

        $id = $_REQUEST['id'];

        Object::loadFiles('Model', array('Invoice'));
        $InvoiceModel = new InvoiceModel();

        $InvoiceModel->deleteFrom($InvoiceModel->getTable())
                ->where('id', $id)->execute();

        $opts = array();

        $arr = $InvoiceModel->getAll(array_merge($opts));

        $this->tpl['arr'] = $arr;
    }

    function deleteSelected() {
        $this->isAjax = true;

        Object::loadFiles('Model', array('Invoice'));
        $InvoiceModel = new InvoiceModel();

        if (!empty($_POST['mark'])) {

            $InvoiceModel->deleteFrom($InvoiceModel->getTable())
                    ->where('id', $_POST['mark'])->execute();
        }

        $arr = $InvoiceModel->getAll();

        $this->tpl['arr'] = $arr;
    }

    function getBookingDetails() {
        $this->isAjax = true;

        $booking = array();

        if (!empty($_POST['id'])) {
            Object::loadFiles('Model', array('Booking'));
            $BookingModel = new BookingModel();

            $booking = $BookingModel->get($_POST['id']);
        }

        header("Content-Type: application/json", true);
        echo json_encode($booking);
    }

    function printInvoice() {
        
    }

    function sendEmail() {
     
        if (!empty($_POST['send_mail']) && !empty($_POST['subject']) && !empty($_POST['message']) && !empty($_POST['invoice_id'])) {
  
            Object::loadFiles('Model', array('Booking', 'Invoice', 'Option'));
            $InvoiceModel = new InvoiceModel();
            $BookingModel = new BookingModel();
            $OptionModel = new OptionModel();

            $invoice = $InvoiceModel->get($_POST['invoice_id']);
            $booking_details = $BookingModel->getBookingDetails($invoice['booking_id']);
            $option_arr = $OptionModel->getAllPairValues(array('calendar_id' => $booking_details['calendar_id']));

            require_once APP_PATH . '/helpers/PHPMailer_5.2.4/class.phpmailer.php';
            $subjetc = $_POST['subject'];
            $message = $_POST['message'];
            
            try {
                $mail = new PHPMailer(true); //New instance, with exceptions enabled
                //$mail->IsSendmail();  // tell the class to use Sendmail
                $mail->AddReplyTo($option_arr['notify_email'], "Admin");
                $mail->From = $option_arr['notify_email'];
                $mail->FromName = $option_arr['notify_email'];
                $mail->AddAddress($booking_details['email']);
                $mail->Subject = $subjetc;
                $mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
                $mail->WordWrap = 80; // set word wrap
                $mail->MsgHTML($message);

                if (!empty($invoice)) {
                    $invoice_id = $invoice['id'];
                    $booking_id = $invoice['booking_id'];

                    if (is_file(INSTALL_PATH . UPLOAD_PATH . 'invoice/' . 'booking_' . $booking_id . '_invoice_' . $invoice_id . '.pdf')) {
                        $mail->AddAttachment(INSTALL_PATH . UPLOAD_PATH . 'invoice/' . 'booking_' . $booking_id . '_invoice_' . $invoice_id . '.pdf'); // attachment
                    }
                }
                $mail->IsHTML(true); // send as HTML
                $mail->Send();
            } catch (phpmailerException $e) {
                echo $e->errorMessage();
            }
           
            $_SESSION['status'] = 28;
            Util::redirect(INSTALL_URL . "GzInvoice/index");
        }
    }

    function viewInvoice() {
        $this->layout = 'empty';

        $invoice = '';

        if (!empty($_GET['id'])) {
            Object::loadFiles('Model', array('Invoice'));
            $InvoiceModel = new InvoiceModel();

            $invoice = $InvoiceModel->generateInvoice($_GET['id']);
        }

        echo $invoice;
    }

}

?>