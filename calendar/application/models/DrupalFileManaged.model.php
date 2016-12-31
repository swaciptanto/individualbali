<?php

require_once MODELS_PATH . 'AppDrupal.model.php';

class DrupalFileManagedModel extends AppDrupalModel {

    var $primaryKey = 'fid';
    var $table = 'file_managed';
    var $schema = array(
        array('name' => 'fid', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'filename', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'uri', 'type' => 'varchar', 'default' => ':NULL'),
    );

}

?>