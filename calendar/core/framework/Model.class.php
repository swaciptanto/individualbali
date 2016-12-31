<?php
require_once FRAMEWORK_PATH . 'Object.class.php';
include_once FRAMEWORK_PATH . 'FluentStructure.php';
include_once FRAMEWORK_PATH . 'FluentUtils.php';
include_once FRAMEWORK_PATH . 'FluentLiteral.php';
include_once FRAMEWORK_PATH . 'BaseQuery.php';
include_once FRAMEWORK_PATH . 'CommonQuery.php';
include_once FRAMEWORK_PATH . 'SelectQuery.php';
include_once FRAMEWORK_PATH . 'InsertQuery.php';
include_once FRAMEWORK_PATH . 'UpdateQuery.php';
include_once FRAMEWORK_PATH . 'DeleteQuery.php';

class Model extends Object {

    private $pdo, $structure;

    /** @var boolean|callback */
    public $debug;
    public $engine = 'mysql';
    public $host = '';
    public $database = '';
    public $user = '';
    public $pass = '';
    var $primaryKey = null;
    var $prefix = null;

    /**
     * Table name
     *
     * @access public
     * @var string
     */
    var $table = null;

    /**
     * Prefix of table names
     *
     * @access public
     * @var string
     */
    function setDatabase($db) {
        if (!empty($db)) {
            $this->database = $db;
        }
    }

    function setHost($host) {
        if (!empty($host)) {
            $this->host = $host;
        }
    }

    function setUser($user) {
        if (!empty($user)) {
            $this->user = $user;
        }
    }

    function setPass($pass) {
        if (!empty($pass)) {
            $this->pass = $pass;
        }
    }

    function __construct(FluentStructure $structure = null) {

        if (defined('DEFAULT_PREFIX')) {
            $this->prefix = DEFAULT_PREFIX;
        }

        if (defined('DEFAULT_DB')) {
            $this->setDatabase(DEFAULT_DB);
        }
        if (defined('DEFAULT_HOST')) {
            $this->setHost(DEFAULT_HOST);
        }
        if (defined('DEFAULT_USER')) {
            $this->setUser(DEFAULT_USER);
        }
        if (defined('DEFAULT_PASS')) {
            $this->setPass(DEFAULT_PASS);
        }

        $dns = $this->engine . ':dbname=' . $this->database . ";host=" . $this->host;
        
        try {
            $this->pdo = new PDO($dns, $this->user, $this->pass, array(PDO::ATTR_PERSISTENT => true));
        } catch (Exception $e) {
            
        }
        if (!$structure) {
            $structure = new FluentStructure;
        }
        $this->structure = $structure;
    }

    /** Create SELECT query from $table
     * @param string $table  db table name
     * @param integer $primaryKey  return one row by primary key
     * @return \SelectQuery
     */
    public function from($table, $primaryKey = null) {
        $query = new SelectQuery($this, $table);
        if ($primaryKey) {
            $tableTable = $query->getFromTable();
            $tableAlias = $query->getFromAlias();
            $primaryKeyName = $this->structure->getPrimaryKey($tableTable);
            $query = $query->where("$tableAlias.$primaryKeyName", $primaryKey);
        }
        return $query;
    }

    /** Create INSERT INTO query
     *
     * @param string $table
     * @param array $values  you can add one or multi rows array @see docs
     * @return \InsertQuery
     */
    public function insertInto($table, $values = array()) {

        $query = new InsertQuery($this, $table, $values);
// echo   $query->getQuery();
        return $query;
    }

    /** Create UPDATE query
     *
     * @param string $table
     * @param array|string $set
     * @param string $primaryKey
     *
     * @return \UpdateQuery
     */
    public function update($data = array()) {
        foreach ($this->schema as $field) {

            if (isset($data[$field['name']])) {

                if (!is_array($data[$field['name']])) {
                    $save["`" . $field['name'] . "`"] = $data[$field['name']];
                } else {
                    if (isset($data[$field['name']][0])) {
                        $save["`" . $field['name'] . "`"] = $data[$field['name']][0];
                    }
                }
            }
        }

        $query = new UpdateQuery($this, $this->getTable());
        $query->set($save);
        $primaryKeyName = $this->getStructure()->getPrimaryKey($this->getTable());
        if (!empty($data[$primaryKeyName])) {
            $query = $query->where($primaryKeyName, $data[$primaryKeyName]);
        }

        return $query->execute();
    }

    /** Create DELETE query
     *
     * @param string $table
     * @param string $primaryKey  delete only row by primary key
     * @return \DeleteQuery
     */
    public function delete($table, $primaryKey = null) {
        $query = new DeleteQuery($this, $table);
        if ($primaryKey) {
            $primaryKeyName = $this->getStructure()->getPrimaryKey($table);
            $query = $query->where($primaryKeyName, $primaryKey);
        }
        return $query;
    }

    /** Create DELETE FROM query
     *
     * @param string $table
     * @param string $primaryKey
     * @return \DeleteQuery
     */
    public function deleteFrom($table, $primaryKey = null) {
        $args = func_get_args();
        return call_user_func_array(array($this, 'delete'), $args);
    }

    /** @return \PDO
     */
    public function getPdo() {
        return $this->pdo;
    }

    /** @return \FluentStructure
     */
    public function getStructure() {
        return $this->structure;
    }

    public function getTable() {
        return $this->prefix . $this->table;
    }

    public function getAll($options = null, $column = null, $limit = null) {

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

        return $query->fetchAll();
    }

    public function getI18nAll($options = null, $column = null) {
        $this->loadFiles('model', array('Field'));
        $FieldModel = new FieldModel();

        $query = $this->from($this->getTable())->where($options);

        if (!empty($column)) {

            $query->orderBy("`" . $column . "`");
        }

        $arr = $query->fetchAll();
/////////////////////////////////////////////////////
        $result = array();
        if (!empty($arr)) {
            foreach ($arr as $key => $row) {
                $result[$key] = $row;

                $opts['table_name'] = $this->getTable();
                $opts['in_id'] = $row['id'];

                $query = $this->from($FieldModel->getTable())->where($opts);
                $i18n_arr = $query->fetchAll();

                foreach ($i18n_arr as $k => $value) {
                    $result[$key]['i18n'][$value['language_id']][$value['field_name']] = $value['value'];
                }
            }
        }
        /* $query->debug=true;
          echo $query->getQuery();
          print_r($query->getParameters());
          echo '<br />'; */
        return $result;
    }

    function getI18n($id = null) {
        $this->loadFiles('model', array('Field'));
        $FieldModel = new FieldModel();
        $options['id'] = $id;
        $query = $this->from($this->getTable())->where($options);

        if (!empty($column)) {

            $query->orderBy("`" . $column . "`");
        }

        $arr = $query->fetchAll();
        $row = $arr[0];
/////////////////////////////////////////////////////
        $result = array();
        $result = $row;

        $opts['table_name'] = $this->getTable();
        $opts['in_id'] = $row['id'];

        $query = $this->from($FieldModel->getTable())->where($opts);
        $i18n_arr = $query->fetchAll();

        foreach ($i18n_arr as $k => $value) {
            $result['i18n'][$value['language_id']][$value['field_name']] = $value['value'];
        }

        /* $query->debug=true;
          echo $query->getQuery();
          print_r($query->getParameters());
          echo '<br />'; */
        return $result;
    }

    public function get($id = null) {

        $primaryKeyName = $this->getStructure()->getPrimaryKey($this->getTable());
        if (!empty($id)) {

            return $this->from($this->getTable())->where($primaryKeyName, $id)->fetch();
        }
    }

    public function save($data) {
        $save = array();

        foreach ($this->schema as $field) {

            if (isset($data[$field['name']])) {

                if (!is_array($data[$field['name']])) {
                    $save["`" . $field['name'] . "`"] = $data[$field['name']];
                } else {
                    if (isset($data[$field['name']][0])) {
                        $save["`" . $field['name'] . "`"] = $data[$field['name']][0];
                    }
                }
            }
        }
        if (count($save) > 0) {

            $query = $this->insertInto($this->getTable(), $save);
            return $lastInsert = $query->execute();
        }
        return false;
    }

    function getColumnType($column) {
        foreach ($this->schema as $col) {
            if ($col['name'] == $column) {
                return $col['type'];
            }
        }
        return false;
    }

    function escape($value, $column = null, $type = null) {
        if (is_null($type) && !is_null($column)) {
            $type = $this->getColumnType($column);
        }

        switch ($type) {
            case 'null':
                return $value;
                break;
            case 'int':
            case 'smallint':
            case 'tinyint':
            case 'mediumint':
            case 'bigint':
                return intval($value);
                break;
            case 'float':
            case 'decimal':
            case 'double':
            case 'real':
                return floatval($value);
            default : return $value;
                break;
        }
    }

    function execute($sql) {
        $pdo = $this->getPdo();

        $stmt = $pdo->prepare($sql);

        $stmt->execute();
        return $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getCount($opts) {
        $arr = $this->getAll($opts);

        return count($arr);
    }

}