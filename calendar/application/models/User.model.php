<?php

require_once MODELS_PATH . 'App.model.php';

class UserModel extends AppModel {

    var $primaryKey = 'id';
    var $table = 'gz_abc_users';
    var $schema = array(
        array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'email', 'type' => 'varchar', 'default' => ''),
        array('name' => 'first', 'type' => 'varchar', 'default' => ''),
        array('name' => 'last', 'type' => 'varchar', 'default' => ''),
        array('name' => 'avatar', 'type' => 'varchar', 'default' => ''),
        array('name' => 'password', 'type' => 'varchar', 'default' => ''),
        array('name' => 'type', 'type' => 'int', 'default' => '1'),
        array('name' => 'status', 'type' => 'enum', 'default' => 'T'),
        array('name' => 'last_login', 'type' => 'timestamp', 'default' => ':CURRENT_TIMESTAMP')
    );

}

?>