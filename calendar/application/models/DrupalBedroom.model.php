<?php

require_once MODELS_PATH . 'AppDrupal.model.php';

class DrupalBedroomModel extends AppDrupalModel {

    var $primaryKey = 'entity_id';
    var $table = 'field_data_bedroom';
    var $schema = [
        ['name' => 'entity_id', 'type' => 'int', 'default' => ':NULL'],
        ['name' => 'bedroom_tid', 'type' => 'int', 'default' => ':NULL'],
    ];

}
