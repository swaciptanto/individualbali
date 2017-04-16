<?php
class Object {
    function _getNextOrder($table, $conditions = array()) {
        $sql_conditions = "";
        if (count($conditions) > 0) {
            foreach ($conditions as $key => $value) {
                $sql_conditions .= " AND `$key` = '$value' ";
            }
        }
        $r = mysql_query("SELECT MAX(`order`) AS `max` FROM `" . $table . "` WHERE 1=1 $sql_conditions ");
        $row = mysql_fetch_assoc($r);
        return ($row['max'] == 'NULL') ? 1 : $row['max'] + 1;
    }

    public static function loadFiles($type, $name) {
        $type = strtolower($type);
        if (!in_array($type, array('model', 'component'))) {
            return false;
        }

        switch ($type) {
            case 'model':
                if (is_array($name)) {
                    foreach ($name as $n) {
                        require_once MODELS_PATH . $n . '.model.php';
                    }
                } else {
                    require_once MODELS_PATH . $name . '.model.php';
                }
                break;
            case 'component':
                if (is_array($name)) {
                    foreach ($name as $n) {
                        require_once COMPONENTS_PATH . $n . '.php';
                    }
                } else {

                    require_once COMPONENTS_PATH . $name . '.php';
                }
                break;
        }
        return;
    }

}