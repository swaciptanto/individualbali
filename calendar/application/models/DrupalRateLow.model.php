<?php

require_once MODELS_PATH . 'AppDrupal.model.php';

class DrupalRateLowModel extends AppDrupalModel {

    var $primaryKey = 'entity_id';
    var $table = 'field_data_field_rate_low';
    var $schema = array(
        array('name' => 'entity_id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'field_rate_low_value', 'type' => 'varchar', 'default' => ':NULL'),
    );

}

?>