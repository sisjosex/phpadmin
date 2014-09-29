<?php

require_once(BASE_PATH . 'system/model.php');

class Module extends Model
{

    /*
     * Fields format:
     *
     * $this->addField( array( "key" => "id", "name" => "Id", "type" => "text",
                "rules" => array(
                    "required" => true
                ),
                "messages" => array(
                    "required" => "Id is required"
                ),
                "group" => "column1"
            )
        );
     */
    var $fields = array();
    var $field_group = array();
    var $field_name = '';
    var $field_custom_name = '';
    var $field_custom_name_dropdown = '';
    var $field_id = '';

    /*
     * array("column" => 'ASC', "column2" => 'DESC')
     */
    var $sorting = array();
    /*
     * array("start" => 0, "limit" => 10)
     */
    var $pagination = array();
    /*
     * array("key='value' AND ...", "...")
     */
    var $filters = array();

    var $url_add = '';
    var $url_edit = '';
    var $url_save = '';
    var $url_save_all = '';
    var $url_list = '';

    var $table_exists = TRUE;

    var $phisical_table = '';
    var $joins = array();
    var $group_by = array();

    var $dropdowns = array();

    var $base_url = '';

    function __construct($table, $id, $field='name')
    {
        parent::__construct($table, $id, $field);

        $this->field_name = 'name';
        $this->field_custom_name = '';
        $this->field_id = $id;

        $this->url_add  = $table . '/getNew';
        $this->url_edit = $table . '/getOne';
        $this->url_save = $table . '/save';
        $this->url_save_all = $table . '/saveAll';
        $this->url_list = $table . '/getAll';

        $this->phisical_table = $this->table_name;

        /*
        $this->addField(
            array(
                // field autocalculado
                "key" => "monto_total",
                "operation" => "quantity * price",
                "comparator" => ">=" // <=, LIKE, !=

                "name" => "Referred By (Name)",
                "type" => "text",
                "rules" => array(
                    "required" => false
                ),
                            "messages" => array(
                    "required" => "Referred By (Name) is required"
                ),
                "group" => "column1",
                "relation" => new Model()
                "relation_type" => 'INNER' // LEFT, OUTER, RIGHT JOIN
            )
        );

        $this->addGroup('grid', array('name'));
        $this->addGroup('form', array('name'));*/
    }

    function purgeModel() {

        $result = array(
            'fields' => $this->purgeFields($this->fields),
            'field_group' => $this->field_group,
            'table_name' => $this->table_name,
            'table_id' => $this->table_id,
            'field_name' => $this->field_name,
            'pagination' => $this->pagination,
            'base_url' => $this->base_url
        );

        return $result;
    }

    function purgeFields($fields) {

        foreach($fields as $key=>$field) {
            if(isset($fields[$key]['model']) || @$fields[$key]['type'] == 'composite') {

                if(isset($fields[$key]['model'])) {

                    $model = $fields[$key]['model'];
                    $fields[$key]['model'] = $model->purgeModel();

                } else if ($fields[$key]['type'] == 'composite') {

                    $model = $fields[$key]['composite']['external_model'];

                    $fields[$key]['composite']['external_model'] = $model->purgeModel();
                }
            }

            $fields[$key] = $this->purgeField($fields[$key]);
        }

        return $fields;
    }

    function purgeField($field) {

        unset($field['comparator']);

        return $field;
    }

    function addField($field) {

        if( isset($field['id']) ) {

            $this->fields[ $field['id'] ] = $field;

        } else if( isset($field['key']) ) {

            $this->fields[ $field['key'] ] = $field;

        } else if( isset($field['grid_key']) ) {

            $this->fields[ $field['grid_key'] ] = $field;

        } else if( isset($field['form_key']) ) {

            $this->fields[ $field['form_key'] ] = $field;
        }

        if( isset($field['values']) ) {

            $values_array = array();

            foreach($field['values'] as $value) {
                $values_array[$value['id']] = $value['name'];
            }

            $this->dropdowns[$field['key']] = $values_array;
        }
    }

    function addGroup($group, $fields)
    {

        $this->field_group[$group] = $fields;
    }

    function getListDropdown($default = array()) {

        $columns = array_keys($this->fields);

        $columns[] = $this->table_id;

        $relations = array();

        $columns_query = array();

        $joins = array();

        foreach ($columns as $column) {

            $field = $this->fields[$column];

            if(isset($field['field'])) {
                //$field = $this->fields[$field['field']];
            }

            if (isset($field['operation'])) {

                $columns_query[] = "" . $field['operation'] . " as " . $column;

            } else if (isset($field['model']) && @$field['type'] != 'inline') {

                $external_model = $field['model'];

                if (!isset($relations[$column])) {

                    $relations[$column] = '';

                } else {

                    $relations[$column] ++;
                }

                $columns_query[] = $external_model->phisical_table . $relations[$column] . "." . $external_model->field_id . " as " . $external_model->phisical_table . $relations[$column] . "_id";

                //$columns_query[] = ($external_model->field_custom_name ? $external_model->field_custom_name . ' as ' . $external_model->phisical_table . $relations[$column] : $external_model->phisical_table . $relations[$column] . "." . $external_model->field_name . " as " . $external_model->phisical_table . $relations[$column]);

                $columns_query[] = $external_model->phisical_table . $relations[$column] . "." . $external_model->field_name . " as " . $external_model->phisical_table . $relations[$column];

                $joins[] = (isset($field['relation_type']) ? $field['relation_type'] : 'LEFT') . " JOIN " .
                    $external_model->phisical_table . " " . $external_model->phisical_table . $relations[$column] .
                    " ON (" . $external_model->phisical_table . '.' . $relations[$column] . $external_model->field_id .
                    "=" .
                    $this->phisical_table . "." . $field['key'] . ") ";

            } else {

                if( isset($field['type']) && $field['type'] != 'composite' && $field['type'] != 'inline' && $field['type'] != 'map' && $field['type'] != 'dropdown' ) {

                    $columns_query[] = $this->phisical_table . "." . $column;

                } else if(isset($field['type']) && $field['type'] != 'composite' && $field['type'] != 'inline' && $field['type'] != 'map') {

                    $columns_query[] = $this->phisical_table . "." . $column;
                }
            }
        }


        $sql = "SELECT " .  $this->phisical_table . ".`{$this->table_id}` as id, ".
            ($this->field_custom_name_dropdown ? $this->field_custom_name_dropdown . ' as name' : $this->phisical_table . '.' . $this->field_name . ' as name')
            ." FROM " . $this->phisical_table . "
         " . implode(' ', $joins) . " ";

        if (!empty($this->filters)) {

            $filter_fields = array();
            foreach ($this->filters as $key => $value) {

                $field = isset($this->fields[$key]) ? $this->fields[$key] : FALSE;

                if( is_array($value) ) {

                    $values = array();
                    foreach($value as $valtmp) {
                        $values[] = "'" . $valtmp ."'";
                    }

                    $filter_fields[] = $key . " IN(" . implode(',', $values) . ")";

                } else if ($field && isset($field['comparator']) && $field['comparator']=='IN') {

                    $filter_fields[] = $key . " " . $field['comparator'] . " " . $value . "";

                } else if ($field && isset($field['comparator'])) {

                    $filter_fields[] = $key . " " . $field['comparator'] . " '" . addslashes($value) . "'";

                } else if($field) {

                    $filter_fields[] = $key . " LIKE '%" . addslashes($value) . "%'";

                } else {

                    $filter_fields[] = $key . "'" . addslashes($value) . "'";
                }
            }

            if (!empty($filter_fields)) {

                $sql .= " WHERE " . implode(' AND ', $filter_fields);
            }
        }

        if (!empty($this->sorting)) {

            $sorting_fields = array();
            foreach ($this->sorting as $key => $order) {
                $sorting_fields[] = $key . ' ' . ($order ? $order : 'ASC');
            }

            if (!empty($sorting_fields)) {

                $sql .= " ORDER BY " . implode
                    (',', $filter_fields);
            }
        }

        return $this->fetch_result($sql, $default);
    }

    function getList($count_only = FALSE, $row_only = FALSE)
    {
        $columns = $this->field_group['grid'];
        //$columns = array_keys($this->fields);

        $columns[] = $this->table_id;

        $relations = array();

        $columns_query = array();

        $joins = array();

        if ( !$count_only ) {
            $joins = $this->joins;
        }

        //$joins = array();

        if ( !$count_only ) {

            foreach ($columns as $column) {

                $field = @$this->fields[$column];

                if(isset($field['field'])) {
                    $field = $this->fields[$field['field']];
                }

                if (isset($field['operation'])) {

                    $columns_query[] = "" . $field['operation'] . " as " . $column;

                } else if (isset($field['model']) && @$field['type'] != 'inline') {

                    $external_model = $field['model'];

                    if (!isset($relations[$column])) {

                        $relations[$column] = '';

                    } else {

                        $relations[$column] ++;
                    }

                    $columns_query[] = $external_model->phisical_table . $relations[$column] . "." . $external_model->field_id . " as " . $external_model->phisical_table . $relations[$column] . "_id";

                    $columns_query[] = ($external_model->field_custom_name ? $external_model->field_custom_name . ' as ' . $external_model->phisical_table . $relations[$column] : $external_model->phisical_table . $relations[$column] . "." . $external_model->field_name . " as " . $external_model->phisical_table . $relations[$column]);

                    //$columns_query[] = $external_model->phisical_table . $relations[$column] . "." . $external_model->field_name . " as " . $external_model->phisical_table . $relations[$column];

                    $joins[] = (isset($field['relation_type']) ? $field['relation_type'] : 'LEFT') . " JOIN " .
                        $external_model->phisical_table . " " . $external_model->phisical_table . $relations[$column] .
                        " ON (" . $external_model->phisical_table . '.'. $relations[$column] . $external_model->field_id .
                        "=" .
                        $this->phisical_table . "." . $field['key'] . ") ";

                } else {

                    if( isset($field['type']) && $field['type'] != 'composite' && $field['type'] != 'inline' && $field['type'] != 'map' && $field['type'] != 'dropdown' ) {

                        $columns_query[] = $this->phisical_table . "." . $column;

                    } else if(isset($field['type']) && $field['type'] != 'composite' && $field['type'] != 'inline' && $field['type'] != 'map') {

                        $columns_query[] = $this->phisical_table . "." . $column;
                    }
                }
            }

        } else {

            $columns_query[] = " COUNT(" . $this->phisical_table . "." . $this->table_id . ") as total ";
        }

        $sql = "SELECT " . implode(',', $columns_query) . " FROM " . $this->phisical_table . "
         " . implode(' ', $joins) . " ";

        if (!empty($this->filters)) {

            $filter_fields = array();
            foreach ($this->filters as $key => $value) {

                $field = isset($this->fields[$key]) ? $this->fields[$key] : FALSE;

                if( is_array($value) ) {

                    $values = array();
                    foreach($value as $valtmp) {
                        $values[] = "'" . $valtmp ."'";
                    }

                    $filter_fields[] = $key . " IN(" . implode(',', $values) . ")";

                } else if (trim($value) && $field && isset($field['comparator']) && $field['comparator']=='IN') {

                    $filter_fields[] = $key . " " . $field['comparator'] . " " . $value . "";

                } else if (trim($value) && $field && isset($field['comparator'])) {

                    $filter_fields[] = $key . " " . $field['comparator'] . " '" . addslashes($value) . "'";

                } else if(trim($value) && $field) {

                    $filter_fields[] = $key . " LIKE '%" . addslashes($value) . "%'";

                } else if(trim($value) ) {

                    $filter_fields[] = $key . "'" . addslashes($value) . "'";
                }
            }

            if (!empty($filter_fields)) {

                $sql .= " WHERE " . implode(' AND ', $filter_fields);
            }
        }

        $filter_fields2 = array();
        if ( isset($_REQUEST['filters']) ) {

            foreach($_REQUEST['filters'] as $key => $val) {

                $filter_fields2[] = $key . " LIKE '%" . addslashes($val) . "%'";
            }
        }

        if( !empty($filter_fields) && !empty($filter_fields2) ) {

            $sql .= " AND " . implode(' AND ', $filter_fields2);

        } else if(!empty($filter_fields2)) {

            $sql .= " WHERE " . implode(' AND ', $filter_fields2);
        }

        if (!empty($this->sorting)) {

            $sorting_fields = array();
            foreach ($this->sorting as $key => $order) {
                $sorting_fields[] = $key . ' ' . ($order ? $order : 'ASC');
            }

            if (!empty($sorting_fields)) {

                $sql .= " ORDER BY " . implode
                    (',', $sorting_fields);
            }
        }

        if((!empty($this->group_by) && !$count_only) || (!empty($this->group_by) && $count_only && empty($this->joins))) {
            $sql .= " GROUP BY " . implode($this->group_by);
        }

        if ( isset($_REQUEST['per_page']) && isset($_REQUEST['current_page']) ) {

            $this->pagination['start'] = ($_REQUEST['current_page'] - 1) * $_REQUEST['per_page'];
            $this->pagination['limit'] = $_REQUEST['per_page'];
        }

        if ((isset($this->pagination['start']) && isset($this->pagination['limit'])) && !$count_only) {

            $sql .= " LIMIT " . $this->pagination['start'] . ", " . $this->pagination['limit'];
        }

        if ($count_only) {

            $row = $this->fetch_row($sql);

            return $row['total'];
        }

        if(!$row_only) {

            $result = $this->fetch_result($sql);

        } else {

            return $this->format_row( $this->fetch_row($sql) );
        }

        $result_list = array();
        if($result)
        foreach($result as $row) {
            $result_list['r_' . $row[$this->field_id]] = $this->format_row($row);
        }

        return $result_list;
    }

    function format_row($row) {

        if(!$row) {
            return FALSE;
        }

        foreach($row as $key => &$val) {

            $val = $this->formatField($key, $val, 'grid');
        }

        return $row;
    }

    function loadDepends() {
        $key = $_REQUEST['key'];
        $id = $_REQUEST[$this->phisical_table]['id'];
        $data = $_REQUEST[$this->phisical_table];

        $depending = $this->fields[$key]['key_depending'];

        $response = array(
            'status' => 'success'
        );

        $firstTime = true;
        foreach($depending as $column) {

            $field = $this->fields[$column];

            if( $field['type'] == 'composite' || $field['type'] == 'dropdown' || isset($field['depends_of']) ) {

                if($firstTime || isset($field['depends_of']) ) {

                    $result = $this->getCustomDropDown('depends', $field, $data, $response);
                    $firstTime = false;

                } else {

                    $result = array();
                }


                if($result && ($field['type'] == 'composite' || $field['type'] == 'dropdown') )  {

                    $response[$this->phisical_table][$column] = '';

                    $response[$this->phisical_table]['dropdown'][$column] = $result;

                } else {

                    $response[$this->phisical_table][$column] = $result;
                }


            }
        }

        return $response;
    }

    function getCustomDropDown($action, $field, $data, $response) {

        return FALSE;
    }

    function getNew()
    {
        $columns = $this->field_group['form'];

        $data = array();

        foreach ($columns as $column) {

            $field = isset($this->fields[$column]) ? $this->fields[$column] : FALSE;

            if(!$field) {
                continue;
            }

            $field_id = isset($field['id']) ? $field['id'] : $field['key'];

            $data[$this->table_name][$field_id] = '';

            if( isset($field['value']) ) {

                $data[$this->table_name][$field_id] = $field['value'];

            } else if(@$field['type'] == 'dropdown' || @$field['type'] == 'multiple' || @$field['type'] == 'composite') {

                if( isset($field['values']) ) {

                    $data[$this->table_name]['dropdown'][$field_id] = $field['values'];

                } else {

                    if( $field['type'] == 'dropdown' || $field['type'] == 'multiple' ) {

                        $custom = $this->getCustomDropDown('new', $field, $data, $data);

                        if( $custom ) {

                            $data[$this->table_name]['dropdown'][$field_id] = $custom === true ? array() : $custom;

                        } else {

                            $model = $field['model'];

                            $data[$this->table_name]['dropdown'][$field_id] = $model->getListDropdown( @$field['default'] );
                        }

                    } else if( $field['type'] == 'composite' ) {

                        $composite = $field['composite'];

                        $external_model = $composite['external_model'];

                        $custom = $this->getCustomDropDown('new', $field, $data, $data);

                        if( $custom ) {

                            $data[$this->table_name]['dropdown'][$field_id] = $custom === true ? array() : $custom;

                        } else {

                            $result = $this->getCompositeDropdown($field, $data);

                            if(!$result) {

                                $field_name = $external_model->field_custom_name_dropdown ? $external_model->field_custom_name_dropdown . ' as name ' : $external_model->phisical_table . '.' . $external_model->table_field . " as name ";

                                $joins = isset($composite['joins']) && !empty($composite['joins']) ? implode(' ', $composite['joins']) : '';

                                $sql = "SELECT
                                            `{$external_model->phisical_table}`.`{$external_model->table_id}` as id,
                                            $field_name,
                                            IF(`{$this->phisical_table}`.`{$this->table_id}`, 'selected', '') as selected
                                            FROM `{$external_model->phisical_table}`
                                            $joins
                                            LEFT JOIN
                                            `{$composite['relation_table']}` ON(`{$composite['relation_table']}`.`{$composite['external_column']}` = `{$external_model->phisical_table}`.`{$external_model->table_id}` AND   `{$composite['relation_table']}`.`{$composite['relation_column']}` = '0')
                                            LEFT JOIN `{$this->phisical_table}` ON (`{$this->phisical_table}`.`{$this->table_id}` = `{$composite['relation_table']}`.`{$composite['relation_column']}` )
                                            WHERE `{$external_model->phisical_table}`.status != 'deleted'
                                            ";

                                if(!empty($external_model->sorting)) {
                                    $sorting = array();
                                    foreach($external_model->sorting as $key => $val) {
                                        $sorting[] = "$key $val";
                                    }
                                    if(!empty($sorting)) {
                                        $sql .= "ORDER BY " . implode(',', $sorting);
                                    }
                                }

                                $result = $this->fetch_result( $sql, @$field['default'] );

                                $data[$this->table_name]['dropdown'][$field_id] = $result;

                            } else {

                                $data[$this->table_name]['dropdown'][$field_id] = $result;
                            }
                        }
                    }
                }

            } else if(@$field['type'] == 'map') {

                $data[$this->table_name][$field_id] = array(
                    'latitude' => $field['settings']['center']['latitude'],
                    'longitude' => $field['settings']['center']['longitude']
                );

            } else {

                $data[$this->table_name][$field_id] = '';
            }
        }

        return $data;
    }

    function getEdit() {

        $columns = $this->field_group['form'];

        $id = $_REQUEST[$this->table_id];

        $data = $this->get($id);

        if($data) {

            $response = array();

            foreach($columns as $column) {

                $value = isset($data[$column]) ? $data[$column] : '';

                $value = $this->formatField($column, $value, 'get', $data);

                $response[$this->table_name][$column] = $value;

                if( isset($this->fields[$column]) ) {

                    $field = $this->fields[$column];

                    $field_id = isset($field['id']) ? $field['id'] : $field['key'];

                    if( @$field['type'] == 'dropdown' || @$field['type'] == 'multiple' || @$field['type'] == 'composite' || @$field['type'] == 'inline' ) {

                        if( isset($field['values']) ) {

                            $response[$this->table_name]['dropdown'][$column] = $field['values'];

                        } else {

                            if( $field['type'] == 'dropdown' || $field['type'] == 'multiple' ) {

                                $custom = $this->getCustomDropDown('edit', $field, $data, $data);

                                if( $custom ) {

                                    $response[$this->table_name]['dropdown'][$field_id] = $custom === true ? array() : $custom;

                                } else {

                                    $model = $field['model'];

                                    $response[$this->table_name]['dropdown'][$column] = $model->getListDropdown( @$field['default'] );
                                }

                            } else if( $field['type'] == 'composite' ) {

                                $composite = $field['composite'];

                                $external_model = $composite['external_model'];

                                $custom = $this->getCustomDropDown('edit', $field, $data, $data);

                                if( $custom ) {

                                    $response[$this->table_name]['dropdown'][$field_id] = $custom === true ? array() : $custom;

                                } else {

                                    $custom = $this->getCompositeDropdown($field, $data);

                                    if(!$custom) {

                                        $field_name = $external_model->field_custom_name_dropdown ? $external_model->field_custom_name_dropdown . ' as name ' : $external_model->phisical_table . '.' . $external_model->table_field . " as name ";

                                        /*`{$external_model->phisical_table}`.`{$external_model->table_field}` as name,*/
                                        /*`{$composite['relation_table']}` ON(`{$composite['relation_table']}`.`{$external_model->phisical_table}_{$external_model->table_id}` = `{$external_model->phisical_table}`.`{$external_model->table_id}` AND `{$composite['relation_table']}`.`{$this->phisical_table}_{$this->table_id}` = '$id')*/

                                        $joins = isset($composite['joins']) && !empty($composite['joins']) ? implode(' ', $composite['joins']) : '';

                                        $sql = "
                                            SELECT
                                                `{$external_model->phisical_table}`.`{$external_model->table_id}` as id,
                                                $field_name,
                                                IF(`{$this->phisical_table}`.`{$this->table_id}`, 'selected', '') as selected
                                                FROM `{$external_model->phisical_table}`
                                                $joins
                                                LEFT JOIN
                                                `{$composite['relation_table']}` ON(`{$composite['relation_table']}`.`{$composite['external_column']}` = `{$external_model->phisical_table}`.`{$external_model->table_id}` AND   `{$composite['relation_table']}`.`{$composite['relation_column']}` = '$id')
                                                LEFT JOIN `{$this->phisical_table}` ON (`{$this->phisical_table}`.`{$this->table_id}` = `{$composite['relation_table']}`.`{$composite['relation_column']}` )
                                                WHERE `{$external_model->phisical_table}`.status != 'deleted'
                                                ";

                                        if(!empty($external_model->sorting)) {
                                            $sorting = array();
                                            foreach($external_model->sorting as $key => $val) {
                                                $sorting[] = "$key $val";
                                            }
                                            $sql .= "ORDER BY " . implode(',', $sorting);
                                        }

                                        $response[$this->table_name]['dropdown'][$field_id] = $this->fetch_result( $sql, @$field['default'] );

                                    } else {

                                        $response[$this->table_name]['dropdown'][$field_id] = $custom;
                                    }
                                }

                            } else if( $field['type'] == 'inline' ) {

                                $external_model = $field['model'];

                                $external_model->filters = array($external_model->phisical_table . '.' . $this->table_name.'_id=' => $id);

                                $result = $external_model->getList();

                                $response[$this->table_name]['inline'][$column] = $result ? $result : array();
                            }
                        }

                    } else if(@$field['type'] == 'map') {

                        $response[$this->table_name][$column] = array(
                            'latitude' => $data[$field['settings']['lat_key']],
                            'longitude' => $data[$field['settings']['lon_key']]
                        );

                    }
                }
            }

            return $response;
        }

        return FALSE;
    }

    function save($data = FALSE) {

        $data = !$data ? $_REQUEST[$this->table_name] : $data;

        $columns = $this->field_group['form'];

        $id = trim( @$data[$this->table_id] );
        unset($data[$this->table_id]);

        $response = array();

        if( $id ) { // update

            $result = $this->response_before_edit($id, $data);

            if($result){
                return $result;
            }

            $data = $this->callback_before_edit($id, $data);
            $data = $this->callback_before_save($id, $data);

            $external_fields = array();

            foreach($columns as $key) {

                $value = @$data[$key];

                if( isset($data[$key]) ) {
                    $data[$key] = $this->formatField($key, $value, 'add', $data);
                }

                if($this->fields[$key]['type'] == 'composite' || $this->fields[$key]['type'] == 'inline') {

                    $external_fields[$key] = $value;
                    unset($data[$key]);
                }
            }

            if($this->table_exists)
            $this->update($data, array(
                $this->table_id => $id
            ));

            $this->saveExternalRelations($id, $external_fields);

            $this->callback_after_edit($id, $data);

            $this->filters = array($this->table_name.'.'.$this->table_id.'=' => $id);
            $response = array('status' => 'success', $this->table_name => $this->getList(FALSE, TRUE));

        } else { // insert


            $result = $this->response_before_add($data);

            $data = $this->callback_before_save(FALSE, $data);

            if($result){
                return $result;
            }

            $data = $this->callback_before_add($data);

            $external_fields = array();

            foreach($columns as $key) {

                $value = @$data[$key];

                if( isset($data[$key]) ) {
                    $data[$key] = $this->formatField($key, $value, 'add', $data);
                }

                if(@$this->fields[$key]['type'] == 'composite' || @$this->fields[$key]['type'] == 'inline') {

                    $external_fields[$key] = $value;
                    unset($data[$key]);
                }
            }

            if($this->table_exists)
            $id = $this->insert($data);

            $this->saveExternalRelations($id, $external_fields);

            $this->callback_after_add($id, $data);


            $this->filters = array($this->table_name.'.'.$this->table_id.'=' => $id);
            $response = array('status' => 'success', $this->table_name => $this->getList(FALSE, TRUE));
        }

        return $response;
    }

    function saveExternalRelations($id, $relations) {

        foreach($relations as $key => $values) {

            $field = $this->fields[$key];

            if( $field['type'] == 'composite' ) {

                $composite = $field['composite'];

                $external_model = $composite['external_model'];
                $external_id = $composite['external_column'];

                $sql = "SELECT * FROM `{$composite['relation_table']}` WHERE `{$composite['relation_column']}`='$id'";
                $current_rows = $this->fetch_result($sql);

                if( $current_rows ) {

                    $existing_ids = array();
                    $existing_keys = array();
                    foreach($current_rows as $row) {
                        $existing_ids[] = $row[$external_id];

                        $existing_keys[$row[$external_id]] = $row['id'];
                    }

                    if( !empty($values) && is_array($values) && count($values) ) {

                        $diff = array_diff($existing_ids, $values);

                        $values_array = array();
                        foreach($diff as $val) {
                            $values_array[] = "'" . $val."'";
                        }

                        if( !empty($diff) ) {
                            $sql = "DELETE FROM `{$composite['relation_table']}` WHERE `{$composite['relation_column']}`='$id' AND $external_id IN (" . implode(',', $values_array) . ")";
                            $this->query($sql);
                        }

                        $sql = "INSERT INTO `{$composite['relation_table']}`(`{$composite['relation_column']}`, {$composite['external_column']}) VALUES";



                        $values_array = array();
                        foreach($values as $val) {
                            if( !isset($existing_keys[$val]) ) {
                                $values_array[] = "('$id','" . $val."')";
                            }
                        }

                        $sql .= implode(',', $values_array);

                        if(!empty($values_array)) {
                            $this->query($sql);
                        }

                    } else {

                        $sql = "DELETE FROM `{$composite['relation_table']}` WHERE `{$composite['relation_column']}`='$id' ";
                        $this->query($sql);
                    }

                } else if( !empty($values) && is_array($values) && count($values) ) {

                    $sql = "INSERT INTO `{$composite['relation_table']}`(`{$composite['relation_column']}`, {$composite['external_column']}) VALUES";

                    $values_array = array();
                    foreach($values as $key=>$val) {
                        if($val)
                            $values_array[] = "('$id','" . $val . "')";
                    }

                    if(!empty($values_array)) {
                        $sql .= implode(',', $values_array);
                        $this->query($sql);
                    }
                }

            } else if($field['type'] == 'inline') {

                $model = $field['model'];
                $parent_id = $id;
                $parentModel = $this;

                $this->saveInlineRelations($parent_id, $parentModel, $model, $values);
            }
        }
    }

    function saveInlineRelations($parent_id, $parentModel, $model, $values) {

        $external_fields = array();

        if(!empty($values))
        foreach($values as $row) {

            $columns = $model->field_group['form'];

            if(isset($row['deleted'])) {

                if(!$row['id']) {

                    continue;

                } else {

                    $model->delete_where( array('id' => $row['id']) );
                }
            }

            if($row['id']) {

                continue;
            }

            $row[$parentModel->table_name . '_id'] = $parent_id;

            $id = $model->save($row);
        }
    }

    function upload() {

        $field = FALSE;

        $ds = DIRECTORY_SEPARATOR;

        if( !empty($_FILES) ) {

            foreach($_FILES as $key => $value) {
                if(isset($this->fields[$key])) {
                    $field = $this->fields[$key];
                    break;
                }
            }

            if($field) {

                $field = $this->callback_before_upload($field);

                $key = $field['key'];

                $storeFolder = 'uploads';

                if ($field['type'] == 'upload') {

                    $tempFile = $_FILES[$key]['tmp_name'];

                    $targetPath = $field['settings']['path'];

                    $targetFile =  $targetPath. $_FILES[$key]['name'];

                    $newFileName = file_newname($targetPath, $_FILES[$key]['name']);

                    $targetFile = $targetPath . $newFileName;

                    @mkdir( $targetPath, 0777, true );

                    move_uploaded_file($tempFile,$targetFile);

                    $response = $newFileName;

                    return $response;

                }

            } else if( isset($_REQUEST['field']) ) {

                $field = $this->fields[$_REQUEST['field']];

                $field = $this->callback_before_upload($field);

                // Allowed extentions.
                $allowedExts = array("gif", "jpeg", "jpg", "png");

                // Get filename.
                $temp = explode(".", $_FILES["file"]["name"]);

                // Get extension.
                $extension = end($temp);

                // An image check is being done in the editor but it is best to
                // check that again on the server side.
                if ((($_FILES["file"]["type"] == "image/gif")
                        || ($_FILES["file"]["type"] == "image/jpeg")
                        || ($_FILES["file"]["type"] == "image/jpg")
                        || ($_FILES["file"]["type"] == "image/pjpeg")
                        || ($_FILES["file"]["type"] == "image/x-png")
                        || ($_FILES["file"]["type"] == "image/png"))
                    && in_array($extension, $allowedExts)) {
                    // Generate new random name.
                    $name = sha1(microtime()) . "." . $extension;

                    $targetPath = $field['settings']['path'];

                    $targetFile =  $targetPath. $name;

                    @mkdir( $targetPath, 0777, true );

                    // Save file in the uploads folder.
                    move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile);

                    // Generate response.
                    $response = new StdClass;
                    $response->link = $field['settings']['download'] . $name;

                    return $response;
                }
            }

        } else {

            $field = $this->fields[$_REQUEST['field']];

            $field = $this->callback_before_upload($field);

            $value = @trim($_REQUEST['value']);
            if( $field && $value ) {

                $result  = array();

                $obj['name'] = @$field['settings']['partial'] . $value;
                if( file_exists($field['settings']['path'].$ds.$value) ) {
                    $obj['size'] = filesize($field['settings']['path'].$ds.$value);
                } else {
                    $obj['size'] = 0;
                }
                $result[] = $obj;

                return $result;
            }
        }
    }

    function delete($id='', $table='') {

        $id = @$_REQUEST[$this->table_id] ? $_REQUEST[$this->table_id] : $id;

        if(!isset($this->fields['status'])) {

            parent::delete($id);

        } else {

            $this->update(
                array('status' => 'deleted'),
                array($this->table_id => $id)
            );
        }
    }

    function getCompositeDropdown($field, $data) { return FALSE; }

    function callback_before_save($id, $data) {return $data;}

    function callback_after_edit($id, $data) {return $data;}
    function callback_after_add($id, $data) {return $data;}

    function callback_before_edit($id, $data) {return $data;}
    function callback_before_add($data) {return $data;}

    function response_before_edit($id, $data) {return FALSE;}
    function response_before_add($data) {return FALSE;}

    function callback_before_upload($field, $data='') {return $field;}

    function formatField($key, $value, $type) {

        if($type == 'grid') {

            if( trim($key) && isset($this->dropdowns[$key]) ) {
                return $this->dropdowns[$key][$value];
            }
        } else if(@$this->fields[$key]['type'] == 'editor' && ($type == 'get' || $type == 'add')) {
            // if magic quotes enabled on server side
            return str_replace('\"', '"', $value);
        }

        return $value;
    }
}