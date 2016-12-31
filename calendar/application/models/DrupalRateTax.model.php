<?php

require_once MODELS_PATH . 'AppDrupal.model.php';

class DrupalRateTaxModel extends AppDrupalModel {

    var $primaryKey = 'entity_id';
    var $table = 'field_data_field_tax';
    var $schema = array(
        array('name' => 'entity_id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'field_tax_value', 'type' => 'float', 'default' => ':NULL'),
    );

}

?>