<?php

require_once MODELS_PATH . 'App.model.php';

class EnquiryModel extends AppModel {
    var $primaryKey = 'id';
    var $table = 'enquiry';
    var $schema = [
        ['name' => 'id', 'type' => 'int', 'default' => ':NULL'],
        ['name' => 'enquiry_number', 'type' => 'varchar', 'default' => ':NULL'],
        ['name' => 'villa_id', 'type' => 'int', 'default' => ':NULL'],
        ['name' => 'villa_name', 'type' => 'varchar', 'default' => ':NULL'],
        ['name' => 'villa_info', 'type' => 'int', 'default' => ':NULL'],
        ['name' => 'customer_name', 'type' => 'varchar', 'default' => ':NULL'],
        ['name' => 'customer_email', 'type' => 'varchar', 'default' => ':NULL'],
        ['name' => 'customer_phone', 'type' => 'varchar', 'default' => ':NULL'],
        ['name' => 'date_check_in', 'type' => 'timestamp', 'default' => ':CURRENT_TIMESTAMP'],
        ['name' => 'date_check_out', 'type' => 'timestamp', 'default' => ':CURRENT_TIMESTAMP'],
        ['name' => 'price_total', 'type' => 'float', 'default' => '0'],
        ['name' => 'tax', 'type' => 'decimal', 'default' => '0.00'],
        ['name' => 'rate_usd_to_idr', 'type' => 'float', 'default' => '0'],
    ];
}
