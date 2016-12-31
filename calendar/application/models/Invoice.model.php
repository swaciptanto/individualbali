<?php

require_once MODELS_PATH . 'App.model.php';

class InvoiceModel extends AppModel {

    var $primaryKey = 'id';
    var $table = 'gz_abc_invoice';
    var $schema = array(
        array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'invoice_number', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'booking_id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'title', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'first_name', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'second_name', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'phone', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'email', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'company', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'address_1', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'address_2', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'state', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'city', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'zip', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'country', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'fax', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'male', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'date_from', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'date_to', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'status', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'currency', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'calendar_price', 'type' => 'float', 'default' => ':NULL'),
        array('name' => 'amount', 'type' => 'float', 'default' => ':NULL'),
        array('name' => 'extra_price', 'type' => 'float', 'default' => ':NULL'),
        array('name' => 'discount', 'type' => 'float', 'default' => ':NULL'),
        array('name' => 'total', 'type' => 'float', 'default' => ':NULL'),
        array('name' => 'tax', 'type' => 'float', 'default' => ':NULL'),
        array('name' => 'security', 'type' => 'float', 'default' => ':NULL'),
        array('name' => 'deposit', 'type' => 'float', 'default' => ':NULL'),
        array('name' => 'calendars_number', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'payment_method', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'transaction_id', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'nights', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'adults', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'children', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'invoice_company', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'invoice_name', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'invoice_address', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'invoice_city', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'invoice_state', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'invoice_zip', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'invoice_fax', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'invoice_phone', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'invoice_email', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'date', 'type' => 'timestamp', 'default' => ':CURRENT_TIMESTAMP'),
    );

    public function generateInvoice($id) {
        Object::loadFiles('Model', array('Option', 'Booking', 'Invoice'));

        $BookingModel = new BookingModel();
        $InvoiceModel = new InvoiceModel();
        $OptionModel = new OptionModel();

        $invoice = $InvoiceModel->get($id);
        $booking_details = $BookingModel->getBookingDetails($invoice['booking_id']);

        $option_arr = $OptionModel->getAllPairValues(array('calendar_id' => $booking_details['calendar_id']));

        $replacement = array();

        $replacement['booking_number'] = @$booking_details['booking_number'];
        $replacement['booking_id'] = $invoice['booking_id'];
        $replacement['title'] = $invoice['title'];
        $replacement['first_name'] = $invoice['first_name'];
        $replacement['second_name'] = $invoice['second_name'];
        $replacement['phone'] = $invoice['phone'];
        $replacement['email'] = $invoice['email'];
        $replacement['company'] = $invoice['company'];
        $replacement['address_1'] = $invoice['address_1'];
        $replacement['address_2'] = $invoice['address_2'];
        $replacement['city'] = $invoice['city'];
        $replacement['state'] = $invoice['state'];
        $replacement['zip'] = $invoice['zip'];
        $replacement['country'] = $invoice['country'];
        $replacement['fax'] = $invoice['fax'];
        $replacement['male'] = $invoice['male'];
        $replacement['adults'] = @$booking_details['adults'];
        $replacement['children'] = @$booking_details['children'];
        $replacement['nights'] = @$booking_details['nights'];
        $replacement['date_from'] = $booking_details['date_from'];
        $replacement['date_to'] = $booking_details['date_to'];
        $replacement['calendars'] = $booking_details['calendar'];
        $replacement['additional'] = $booking_details['additional'];
        if (!empty($booking_details['extras'])) {
            $replacement['extras'] = implode(',', $booking_details['extras']);
        } else {
            $replacement['extras'] = '';
        }
        $payment_method_arr = __('payment_method_arr');
        $replacement['payment_method'] = $payment_method_arr[$invoice['payment_method']];
        $replacement['deposit'] = Util::currenctFormat($option_arr['currency'], $invoice['deposit']);
        $replacement['tax'] = Util::currenctFormat($option_arr['currency'], $invoice['tax']);
        $replacement['total'] = Util::currenctFormat($option_arr['currency'], $invoice['total']);
        $replacement['calendar_price'] = Util::currenctFormat($option_arr['currency'], $invoice['calendar_price']);
        $replacement['extra_price'] = Util::currenctFormat($option_arr['currency'], $invoice['extra_price']);
        $replacement['discount'] = Util::currenctFormat($option_arr['currency'], $invoice['discount']);
        $replacement['invoice_number'] = $invoice['invoice_number'];
        $replacement['invoice_company'] = $invoice['invoice_company'];
        $replacement['invoice_name'] = $invoice['invoice_name'];
        $replacement['invoice_address'] = $invoice['invoice_address'];
        $replacement['invoice_city'] = $invoice['invoice_city'];
        $replacement['invoice_state'] = $invoice['invoice_state'];
        $replacement['invoice_zip'] = $invoice['invoice_zip'];
        $replacement['invoice_fax'] = $invoice['invoice_fax'];
        $replacement['invoice_phone'] = $invoice['invoice_phone'];
        $replacement['invoice_email'] = $invoice['invoice_email'];
        $replacement['date'] = date($option_arr['date_format'], strtotime($invoice['date']));
        $status_arr = __('status_arr');
        $replacement['status'] = $status_arr[$invoice['status']];

        return $result = Util::replaceInvoiceToken($option_arr['invoice'], $replacement);
    }

    public function getAll($options = null, $column = null, $limit = null) {
        Object::loadFiles('Model', array('Booking'));

        $BookingModel = new BookingModel();

        $query = $this->from($this->getTable() . ' as t1');
        $query->select(null);
        $query->select('t1.*, t2.booking_number');
        $query->where($opts);
        $query->leftJoin($BookingModel->getTable() . ' as t2 ON t2.id = t1.booking_id');
       return $query->fetchAll();
    }
}

?>