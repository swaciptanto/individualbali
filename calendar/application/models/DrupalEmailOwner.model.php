<?php

require_once MODELS_PATH . 'AppDrupal.model.php';

class DrupalEmailOwnerModel extends AppDrupalModel {

    var $primaryKey = 'entity_id';
    var $table = 'field_data_field_email_to_villa_owner';
    var $schema = array(
        array('name' => 'entity_id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'field_email_to_villa_owner_value', 'type' => 'varchar', 'default' => ':NULL'),
    );

}

?>