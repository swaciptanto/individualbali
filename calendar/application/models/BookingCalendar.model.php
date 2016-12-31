<?php

require_once MODELS_PATH . 'App.model.php';

class BookingCalendarModel extends AppModel {

    var $primaryKey = 'id';
    var $table = 'gz_abc_booking_calendars';
    var $schema = array(
        array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'calendar_id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'booking_id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'count', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'date', 'type' => 'timestamp', 'default' => ':CURRENT_TIMESTAMP')
    );

    

}

?>