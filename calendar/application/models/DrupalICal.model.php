<?php

require_once MODELS_PATH . 'AppDrupal.model.php';

class DrupalICalModel extends AppDrupalModel {

    var $primaryKey = 'entity_id';
    var $table = 'field_data_field_ical';
    var $schema = array(
        array('name' => 'entity_id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'field_ical_fid', 'type' => 'int', 'default' => ':NULL'),
    );

}

?>