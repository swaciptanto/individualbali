<?php

require_once MODELS_PATH . 'App.model.php';

class DiscountModel extends AppModel {

    var $primaryKey = 'id';
    var $table = 'gz_abc_discount';
    var $schema = array(
        array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'calendar_id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'title', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'from_date', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'to_date', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'promo_code', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'discount', 'type' => 'float', 'default' => ':NULL'),
        array('name' => 'type', 'type' => 'enum', 'default' => ':NULL')
    );

    function getDiscount($params) {
        $opts = array();

        $opts['t1.promo_code = :promo_code'] = array(':promo_code' => $params['promo_code']);
        $opts["((:from_date >= t1.from_date AND :from_date < t1.to_date) OR (:to_date >= t1.from_date AND :to_date < t1.to_date) OR (:to_date >= t1.to_date AND :from_date < t1.from_date))"] = array(':from_date' => $params['from_date'], ':to_date' => $params['to_date']);

        $query = $this->from($this->getTable() . ' as t1')->where($opts);

        /*
          $query->debug = true;
          echo $query->getQuery();
          print_r($query->getParameters());
          echo '<br />';
         */

        $result = $query->fetchAll();

        $discount_arr = array();
        foreach ($result as $k => $v) {
            for ($i = $params['from_date']; $i <= $params['to_date']; $i += 86400) {
                if ($i >= $v['from_date'] && $i <= $v['to_date']) {
                    $discount_arr[$i]['dt'] = $i;
                    $discount_arr[$i]['discount'] = $v['discount'];
                    $discount_arr[$i]['type'] = $v['type'];
                }
            }
        }
        return $discount_arr;
    }

}

?>