<?php

require_once MODELS_PATH . 'App.model.php';

class CalendarGalleryModel extends AppModel {

    var $primaryKey = 'id';
    var $table = 'gz_abc_calendars_gallery';
    var $schema = array(
        array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'calendar_id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'thumb', 'type' => 'varchar', 'default' => ''),
        array('name' => 'preview', 'type' => 'varchar', 'default' => ''),
        array('name' => 'original', 'type' => 'varchar', 'default' => ''),
        array('name' => 'medium', 'type' => 'varchar', 'default' => ''),
        array('name' => 'name', 'type' => 'varchar', 'default' => ''),
        array('name' => 'date', 'type' => 'timestamp', 'default' => ':CURRENT_TIMESTAMP')
    );

    function getOne($options = null, $column = null, $limit = null) {

        $query = $this->from($this->getTable() . ' as t1')->where($options);

        if (!empty($column)) {
            if (strpos($column, ' ')) {
                $query->orderBy($column);
            } else {
                $query->orderBy("`" . $column . "`");
            }
        }
        if (!empty($limit)) {
            $query->limit($limit);
        }
        /*
          $query->debug=true;
          echo $query->getQuery();
          print_r($query->getParameters());
          echo '<br />';
         */

        $result = $query->fetchAll();

        if (!empty($result[0])) {
            return $result[0];
        } else {
            return false;
        }
    }

}

?>