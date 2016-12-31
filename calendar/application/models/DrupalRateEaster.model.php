<?php

require_once MODELS_PATH . 'AppDrupal.model.php';

class DrupalRateEasterModel extends AppDrupalModel {

    var $primaryKey = 'entity_id';
    var $table = 'field_data_field_rate_easter';
    var $schema = array(
        array('name' => 'entity_id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'field_rate_easter_value', 'type' => 'varchar', 'default' => ':NULL'),
    );

}

?>