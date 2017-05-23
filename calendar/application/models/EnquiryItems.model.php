<?php

require_once MODELS_PATH . 'App.model.php';

class EnquiryItemsModel extends AppModel {
    var $primaryKey = 'id';
    var $table = 'enquiry_items';
    var $schema = array(
        array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'enquiry_id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'date_booking', 'type' => 'timestamp', 'default' => ':CURRENT_TIMESTAMP'),
        array('name' => 'price', 'type' => 'float', 'default' => '0'),
    );
}
