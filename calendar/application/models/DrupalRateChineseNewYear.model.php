<?php

require_once MODELS_PATH . 'AppDrupal.model.php';

class DrupalRateChineseNewYearModel extends AppDrupalModel {

    var $primaryKey = 'entity_id';
    var $table = 'field_data_field_rate_chinese_new_year';
    var $schema = array(
        array('name' => 'entity_id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'field_rate_chinese_new_year_value', 'type' => 'varchar', 'default' => ':NULL'),
    );

}

?>