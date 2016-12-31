<?php

require_once MODELS_PATH . 'AppDrupal.model.php';

class DrupalEmailReservationModel extends AppDrupalModel {

    var $primaryKey = 'entity_id';
    var $table = 'field_data_field_email_reservasi';
    var $schema = array(
        array('name' => 'entity_id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'field_email_reservasi_value', 'type' => 'varchar', 'default' => ':NULL'),
    );

}

?>