<?php

require_once MODELS_PATH . 'AppDrupal.model.php';

class DrupalRatePeakModel extends AppDrupalModel {

    var $primaryKey = 'entity_id';
    var $table = 'field_data_field_rate_peak';
    var $schema = array(
        array('name' => 'entity_id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'field_rate_peak_value', 'type' => 'varchar', 'default' => ':NULL'),
    );

}

?>