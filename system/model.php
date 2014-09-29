<?php

require_once(BASE_PATH . 'config/constants.php');
require_once(BASE_PATH . 'config/database.php');
require_once(BASE_PATH . 'language/en.php');

class Model {

    var $table = '';
    var $table_id = '';
    var $table_field = '';
    var $resource = null;
    var $database = 'default';


    /*var $fields = array();
    var $form_fields = array();
    var $grid_fields = array();*/

    public static $instance = null;

    /**
     *
     * Constructor of generic model
    */
    function __construct($table, $id, $field='name') {

        self::$instance = $this;

        $this->table_name = $table;
        $this->table_id = $id;
        $this->table_field = $field;

        $this->phisical_table = $this->table_name;

    }

    /**
     * Singleton constructor
     * @return static
     */
    public static function getInstance () {
        if (is_null(self::$instance)) { self::$instance = new static(); }
        return self::$instance;
    }

    /**
     *
     * Insert in format array('columns' => 'value', ...)
     *
     */
    function insert($values, $table='') {

        $sql = "INSERT INTO `".($table ? $table : $this->phisical_table)."`";

        $columns = array();
        $column_values = array();
        foreach($values as $key => $value) {
            $columns[] = '`' . ($key) . '`';
            $column_values[] = "'" . addslashes($value) . "'";
        }

        $sql .= "(" . implode(',', $columns) . ") VALUES(" . implode(',', $column_values). ")";

        $this->query($sql);

        return mysqli_insert_id($this->resource);
    }

    /**
     *
     * Update query
     *
     */
    function update($values, $where = array(), $table='') {

        $sql = "UPDATE `".($table ? $table : $this->phisical_table)."` SET ";

        $column_values = array();
        $where_values = array();

        foreach($values as $key => $value) {

            $column_values[] = '`' . ($key) . "` = '" . addslashes($value) . "'";
        }

        $sql .= implode(',', $column_values);

        if(!empty($where)) {

            foreach($where as $key => $value) {

                $where_values[] = '`' . ($key) . "` = '" . addslashes($value) . "'";
            }

            $sql .= ' WHERE ' . implode(',', $where_values);
        }

        $this->query($sql);
    }

    /**
     *
     * Update query
     *
     */
    function delete_where($search, $table='') {

        $sql = "DELETE FROM `".($table ? $table : $this->phisical_table)."` WHERE ";

        $column_values = array();
        foreach($search as $key => $value) {

            $column_values[] = '`' . ($key) . "` = '" . addslashes($value) . "'";
        }

        $sql .= implode(' AND ', $column_values);

        $this->query($sql);
    }

    function delete($id, $table='') {

        $this->delete_where(array($this->table_id => $id), $table);
    }

    function get_where($search, $table='') {

        $sql = "SELECT * FROM `".($table ? $table : $this->phisical_table)."` WHERE ";

        if( is_array($search) ) {

            $column_values = array();
            foreach($search as $key => $value) {

                $column_values[] = '`' . ($key) . "` = '" . addslashes($value) . "'";
            }

            $sql .= implode(' AND ', $column_values);

        } else {

            $sql .= $search;
        }


        return $this->fetch_result($sql);
    }

    function get($id, $table='') {

        $sql = "SELECT * FROM `".($table ? $table : $this->phisical_table)."` WHERE {$this->table_id}='$id'";

        return $this->fetch_row($sql);
    }

    /**
     *
     * Fetch result
     *
     */
    function fetch_result($sql, $default=FALSE) {

        $result = $this->query($sql);

        $result_array = $default ? $default : FALSE;

        while( $row = mysqli_fetch_assoc($result) ) {

            $result_array[] = $row;
        }

        return $result_array;
    }

    /**
     *
     * Fetch result
     *
     */
    function fetch_row($sql) {

        $result = $this->query($sql);

        if($row = mysqli_fetch_assoc($result)) {

            return $row;
        }

        return FALSE;
    }

    /**
     *
     * Runs basic query
     *
     */
    function query($sql) {

        global $database;

        if($this->resource == null) {

            $this->resource = mysqli_connect(
                $database[$this->database]['host'],
                $database[$this->database]['user'],
                $database[$this->database]['pass'],
                $database[$this->database]['db'],
                $database[$this->database]['port']
            ) or die(mysqli_error($this->resource));

            mysqli_set_charset($this->resource, 'UTF-8');
            mysqli_query($this->resource, "SET NAMES 'utf8'");
        }

        $result = mysqli_query( $this->resource, $sql) or die( $sql. ' ' . mysqli_error($this->resource) );

        return $result;
    }
}