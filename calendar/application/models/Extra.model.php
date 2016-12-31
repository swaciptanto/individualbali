<?php

require_once MODELS_PATH . 'App.model.php';

class ExtraModel extends AppModel {

    var $primaryKey = 'id';
    var $table = 'gz_abc_extras';
    var $schema = array(
        array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'title', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'description', 'type' => 'text', 'default' => ':NULL'),
        array('name' => 'price', 'type' => 'float', 'default' => ':NULL'),
        array('name' => 'per', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'img', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'type', 'type' => 'enum', 'default' => ':NULL'),
        array('name' => 'status', 'type' => 'enum', 'default' => ':NULL')
    );

}

?>