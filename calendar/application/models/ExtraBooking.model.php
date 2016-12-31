<?php

require_once MODELS_PATH . 'App.model.php';

class ExtraBookingModel extends AppModel {

    var $primaryKey = 'id';
    var $table = 'gz_abc_extras_booking';
    var $schema = array(
        array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'extra_id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'booking_id', 'type' => 'int', 'default' => ':NULL')
    );

    function getExtraBookingWithExtra($opts) {
        Object::loadFiles('Model', array('Extra'));
        $ExtraModel = new ExtraModel();

        $query = $this->from($this->getTable() . ' as t1');
        $query->select(null);
        $query->select('t1.*, t2.title, t2.price');
        $query->where($opts);
        $query->leftJoin($ExtraModel->getTable() . ' as t2 ON t2.id = t1.extra_id');
        $extras = $query->fetchAll();

        return $extras;
    }

}

?>