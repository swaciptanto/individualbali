<?php

require_once MODELS_PATH . 'App.model.php';

class PriceModel extends AppModel {

    var $primaryKey = 'id';
    var $table = 'gz_abc_price';
    var $schema = array(
        array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'calendar_id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'title', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'from_date', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'to_date', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'monday', 'type' => 'float', 'default' => ':NULL'),
        array('name' => 'tuesday', 'type' => 'float', 'default' => ':NULL'),
        array('name' => 'wednesday', 'type' => 'float', 'default' => ':NULL'),
        array('name' => 'thursday', 'type' => 'float', 'default' => ':NULL'),
        array('name' => 'friday', 'type' => 'float', 'default' => ':NULL'),
        array('name' => 'saturday', 'type' => 'float', 'default' => ':NULL'),
        array('name' => 'sunday', 'type' => 'float', 'default' => ':NULL'),
        array('name' => 'is_default', 'type' => 'enum', 'default' => ':NULL'),
        array('name' => 'adults', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'children', 'type' => 'int', 'default' => ':NULL'),
        //modified: add new column
        array('name' => 'rate', 'type' => 'varchar', 'default' => ':NULL'),
    );

    function getCalendarPrices($params, $id) {

        $opts = array();
        $opts['t1.calendar_id = :calendar_id '] = array(':calendar_id' => $id);
        $opts[" ((:from_date BETWEEN t1.from_date AND t1.to_date) OR (:to_date BETWEEN t1.from_date AND t1.to_date) OR (t1.from_date BETWEEN :from_date AND :to_date AND t1.to_date BETWEEN :from_date AND :to_date))"] = array(':from_date' => $params['from_date'], ':to_date' => $params['to_date']);

        $query = $this->from($this->getTable() . ' as t1')->where($opts);

        /*
          $query->debug = true;
          echo $query->getQuery();
          print_r($query->getParameters());
          echo '<br />';
         */

        $result = $query->fetchAll();

        return $result;
    }

    function getPrices($params, $id) {

        $opts = array();
        $opts['t1.calendar_id = :calendar_id'] = array(':calendar_id' => $id);
        $opts["t1.is_default != 'T' AND t1.adults=:adults AND t1.children=:children AND ( (:from_date >= t1.from_date AND :from_date <= t1.to_date) OR (:to_date >= t1.from_date AND :to_date <= t1.to_date) OR (:to_date >= t1.from_date AND :from_date <= t1.to_date))"] = array(':adults' => $params['adults'], ':children' => $params['children'], ':from_date' => $params['from_date'], ':to_date' => $params['to_date']);

        $query = $this->from($this->getTable() . ' as t1')->where($opts);

        /*
          $query->debug = true;
          echo $query->getQuery();
          print_r($query->getParameters());
          echo '<br />';
         */

        $result = $query->fetchAll();

        return $result;
    }

    function getDefaultPrices($params, $id) {
        $opts['calendar_id'] = $id;

        $opts = array();

        $opts['t1.calendar_id = :calendar_id'] = array(':calendar_id' => $id);
        $opts["t1.is_default = 'T' AND ( (:from_date >= t1.from_date AND :from_date <= t1.to_date) OR (:to_date >= t1.from_date AND :to_date <= t1.to_date) OR (:to_date >= t1.from_date AND :from_date <= t1.to_date))"] = array(':adults' => $params['adults'], ':children' => $params['children'], ':from_date' => $params['from_date'], ':to_date' => $params['to_date']);

        $query = $this->from($this->getTable() . ' as t1')->where($opts);

        /*
          $query->debug = true;
          echo $query->getQuery();
          print_r($query->getParameters());
          echo '<br />';
         */

        $result = $query->fetchAll();

        return $result;
    }

}

?>