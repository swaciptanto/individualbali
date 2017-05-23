<?php

require_once MODELS_PATH . 'AppDrupal.model.php';

class DrupalTaxonomyTermDataModel extends AppDrupalModel {

    var $primaryKey = 'tid';
    var $table = 'taxonomy_term_data';
    var $schema = [
        ['name' => 'tid', 'type' => 'int', 'default' => ':NULL'],
        ['title' => 'name', 'type' => 'varchar', 'default' => ':NULL'],
    ];

}
