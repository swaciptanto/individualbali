<?php

require_once MODELS_PATH . 'AppDrupal.model.php';

class DrupalNodeRevisionModel extends AppDrupalModel {

    var $primaryKey = 'nid';
    var $table = 'node_revision';
    var $schema = array(
        array('name' => 'nid', 'type' => 'int', 'default' => ':NULL'),
        array('title' => 'field_email_to_villa_owner_value', 'type' => 'varchar', 'default' => ':NULL'),
    );

}

?>