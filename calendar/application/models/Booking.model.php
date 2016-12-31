<?php

require_once MODELS_PATH . 'App.model.php';

class BookingModel extends AppModel {

    var $primaryKey = 'id';
    var $table = 'gz_abc_reservations';
    var $schema = array(
        array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'calendar_id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'google_id', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'booking_number', 'type' => 'varchar', 'default' => ':NULL'),
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
        array('name' => 'additional', 'type' => 'text', 'default' => ':NULL'),
        array('name' => 'date_from', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'date_to', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'status', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'promo_code', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'calendars_price', 'type' => 'float', 'default' => ':NULL'),
        array('name' => 'amount', 'type' => 'float', 'default' => ':NULL'),
        array('name' => 'extra_price', 'type' => 'float', 'default' => ':NULL'),
        array('name' => 'discount', 'type' => 'float', 'default' => ':NULL'),
        array('name' => 'total', 'type' => 'float', 'default' => ':NULL'),
        array('name' => 'tax', 'type' => 'float', 'default' => ':NULL'),
        array('name' => 'security', 'type' => 'float', 'default' => ':NULL'),
        array('name' => 'deposit', 'type' => 'float', 'default' => ':NULL'),
        array('name' => 'payment_method', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'adults', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'children', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'cc_type', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'cc_num', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'cc_code', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'cc_exp_month', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'cc_exp_year', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'date', 'type' => 'timestamp', 'default' => ':CURRENT_TIMESTAMP'),
    );

    public function getBookingDetails($id) {
        Object::loadFiles('Model', array('Option', 'Calendar', 'Extra', 'ExtraBooking'));
        $CalendarModel = new CalendarModel();
        $ExtraModel = new ExtraModel();
        $ExtraBookingModel = new ExtraBookingModel();
        $OptionModel = new OptionModel();
        $arr = $this->get($id);

        $booked_calendar = $CalendarModel->getI18n($arr['calendar_id']);
        
        $option_arr = $OptionModel->getAllPairValues(array('calendar_id' => $arr['calendar_id']));

        $arr['nights'] = ceil(($arr['date_to'] - $arr['date_from'])/86400);
        $arr['booked_calendar'] = $booked_calendar;
        $payment_method_arr = __('payment_method_arr');
        $arr['payment_method'] = $payment_method_arr[$arr['payment_method']];
        $language_arr = $_SESSION['lang'];
        $language_id = $language_arr['id'];

        $arr['calendar'] = $booked_calendar['i18n'][$language_id]['title'];
        
        if($arr['deposit'] > 0 ){
            $arr['amount'] = $arr['deposit'];
        }else{
            $arr['amount'] = $arr['total'];
        }

        $booked_extras = $ExtraBookingModel->getExtraBookingWithExtra(array('booking_id' => $id));
        $arr['booked_extras'] = array();
        $arr['extras'] = array();

        foreach ($booked_extras as $extra) {
            $arr['booked_extras'][$extra['extra_id']] = $extra;
            $arr['extras'][] = $extra['title'] . ' - ' . Util::currenctFormat($option_arr['currency'], $extra['price']);
        }
        
        $arr['date_from'] = date($option_arr['date_format'], $arr['date_from']);
        $arr['date_to'] = date($option_arr['date_format'], $arr['date_to']);

        return $arr;
    }
    
    function generatePdfInvoice($id){
        if (!empty($id)) {

            Object::loadFiles('Model', array('Invoice', 'Option'));
            $OptionModel = new OptionModel();
            $InvoiceModel = new InvoiceModel();

            $booking = $this->get($id);
            $data = array();
            
            $option_arr = $OptionModel->getAllPairValues(array('calendar_id' => $booking['calendar_id']));

            $data['invoice_number'] = Util::incrementalHash(10);
            $data['booking_id'] = $id;
            $data['title'] = $booking['title'];
            $data['first_name'] = $booking['first_name'];
            $data['second_name'] = $booking['second_name'];
            $data['phone'] = $booking['phone'];
            $data['email'] = $booking['email'];
            $data['company'] = $booking['company'];
            $data['address_1'] = $booking['address_1'];
            $data['address_2'] = $booking['address_2'];
            $data['city'] = $booking['city'];
            $data['state'] = $booking['state'];
            $data['zip'] = $booking['zip'];
            $data['country'] = $booking['country'];
            $data['fax'] = $booking['fax'];
            $data['male'] = $booking['male'];
            $data['date_from'] = date($option_arr['date_format'], $booking['date_from']);
            $data['date_to'] = date($option_arr['date_format'], $booking['date_to']);
            $data['status'] = $booking['status'];
            $data['amount'] = $booking['amount'];
            $data['calendar_price'] = $booking['calendars_price'];
            $data['extra_price'] = $booking['extra_price'];
            $data['discount'] = $booking['discount'];
            $data['total'] = $booking['total'];
            $data['tax'] = $booking['tax'];
            $data['security'] = $booking['security'];
            $data['deposit'] = $booking['deposit'];
            $data['payment_method'] = $booking['payment_method'];
            $data['adults'] = $booking['adults'];
            $data['children'] = $booking['children'];
            $data['invoice_company'] = $option_arr['invoice_company'];
            $data['invoice_name'] = $option_arr['invoice_name'];
            $data['invoice_address'] = $option_arr['invoice_address'];
            $data['invoice_city'] = $option_arr['invoice_city'];
            $data['invoice_state'] = $option_arr['invoice_state'];
            $data['invoice_zip'] = $option_arr['invoice_zip'];
            $data['invoice_fax'] = $option_arr['invoice_fax'];
            $data['invoice_phone'] = $option_arr['invoice_phone'];
            $data['invoice_email'] = $option_arr['invoice_email'];

            $invoice_id = $InvoiceModel->save($data);
            $invoice = $InvoiceModel->generateInvoice($invoice_id);

            include_once (APP_PATH . "helpers/MPDF57/mpdf.php");

            $mpdf = new mPDF();
            $mpdf->WriteHTML($invoice);
            $name = 'booking_' . $id . '_invoice_' . $invoice_id . '.pdf';
            $mpdf->Output(INSTALL_PATH . UPLOAD_PATH . 'invoice/' . $name, 'F');
            $save = array();
        }
    }

    function getBookingForDatestamp($timestamp) {
        $opts['date_to >= ?'] = $timestamp . " AND " . $timestamp . ">=date_from ";

        return $this->getAll($opts);
    }

    function getAllBookingWithCalendar($opts) {
        Object::loadFiles('Model', array('Calendar', 'Field'));

        $CalendarModel = new CalendarModel();
        $FieldModel = new FieldModel();

        $query = $this->from($this->getTable());
        $query->select(null);
        $query->select($this->getTable() . '.*, ' . $CalendarModel->getTable() . '.title');
        $query->leftJoin($CalendarModel->getTable() . ' ON ' . $CalendarModel->getTable() . '.id = calendar_id');
        $query->where($opts);

        $arr = $query->fetchAll();

        $result = array();

        foreach ($arr as $key => $row) {
            $result[$key] = $row;
            $opts = array();

            $opts['table_name'] = $CalendarModel->getTable();
            $opts['in_id'] = $row['calendar_id'];

            $query = $this->from($FieldModel->getTable())->where($opts);

            $i18n_arr = $query->fetchAll();

            foreach ($i18n_arr as $k => $value) {
                $result[$key]['i18n'][$value['language_id']][$value['field_name']] = $value['value'];
            }

            $opts['table_name'] = $CalendarModel->getTable();
            $opts['in_id'] = $row['calendar_id'];

            $query = $this->from($FieldModel->getTable())->where($opts);
            $i18n_arr = $query->fetchAll();

            foreach ($i18n_arr as $k => $value) {
                if ($value['field_name'] == 'title') {
                    $result[$key]['i18n'][$value['language_id']]['title'] = $value['value'];
                } else {
                    $result[$key]['i18n'][$value['language_id']][$value['field_name']] = $value['value'];
                }
            }
        }

        return $result;
    }

}

?>