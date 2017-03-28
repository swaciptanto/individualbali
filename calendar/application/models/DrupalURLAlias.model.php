<?php

require_once MODELS_PATH . 'AppDrupal.model.php';

class DrupalURLAliasModel extends AppDrupalModel {

    var $primaryKey = 'source';
    var $table = 'url_alias';
    var $schema = array(
        array('name' => 'source', 'type' => 'varchar', 'default' => ':NULL'),
        array('name' => 'alias', 'type' => 'varchar', 'default' => ':NULL'),
    );

}