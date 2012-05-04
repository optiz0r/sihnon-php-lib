<?php

abstract class SihnonFramework_DatabaseObject {

    const ORDER_ASC = 'ASC';
    const ORDER_DESC = 'DESC';
    
    protected static $table;
    
    protected static function table() {
        return static::$table;
    }
    
    protected function setDatabaseProperties(array $properties) {
        foreach($properties as $property => $value) {
            if (property_exists(get_called_class(), '_db_' . $property)) {
                $this->{'_db_' . $property} = $value;
            } else {
                /*throw new Sihnon_Exception_InvalidProperty($property);*/
            }
        }
    }
    
    public static function fromDatabaseRow($row) {
        $object = new static();
        $object->setDatabaseProperties($row);
        
        return $object;
    }
    
	/**
     * Load a DatabaseObject given its ID
     *
     * @param int $id
     * @return SihnonFramework_DatabaseObject
     */
    public static function fromId($id) {
        return static::from('id', $id);
    }
    
    /**
     * Load a DatabaseObject given a field name and value
     * 
     * @param string $fields Name of the field(s) to match on
     * @param mixed $values Value of the field(s) to match on
     */
    public static function from($fields, $values) {
        $database = SihnonFramework_Main::instance()->database();
        
        if ( ! is_array($fields)) {
            $fields = array($fields);
        } 
        if ( ! is_array($values)) {
            $values = array($values);
        }
        
        $field_count = count($fields);
        if ($field_count == 0 || $field_count != count($values)) {
            throw new SihnonFramework_Exception_InvalidConditions();
        }
        
        $conditions = implode('AND ', array_map(
            function($f) {
                return "`{$f}`=:{$f} ";
            },
            $fields
        ));

        $params = array();
        for ($i = 0; $i < $field_count; ++$i) {
            $params[] = array(
                'name' => $fields[$i],
                'value' => $values[$i],
                'type' => PDO::PARAM_STR
            );
        }
        
        $object = self::fromDatabaseRow($database->selectOne("SELECT * FROM `".static::table()."` WHERE {$conditions}", $params));
        
        return $object;        
    }
    
    /**
     * Load a list of all objects
     * 
	 * @return SihnonFramework_DatabaseObject
     */
    public static function all($view = null, $additional_conditions = null, $additional_params = null, $order_by = 'id', $order_dir = self::ORDER_ASC) {
        $database = SihnonFramework_Main::instance()->database();
        
        if ($view === null) {
            $view = static::table();
        }
        
        $class = new ReflectionClass(get_called_class());
        $properties = $class->getproperties();
        
        $fields = array();
        foreach ($properties as $property) {
            $matches = array();
            if (preg_match('/^_db_(.*)/', $property->name, $matches)) {
                $fields[] = $matches[1];
            }
        }
        
        $field_list = join(', ', array_map(function($v) { return "`{$v}`"; }, $fields));
        
        $params = array();
        if ($additional_params) {
            $params = array_merge($params, $additional_params);
        }
        
        $conditions = '';
        if ($additional_conditions) {
            $conditions = "AND ({$additional_conditions}) ";
        }
        
        $objects = array();
        $sql = "SELECT {$field_list} FROM `{$view}` WHERE `id` > 0 {$conditions} ORDER BY `{$order_by}` {$order_dir}";
        foreach ($database->selectList($sql, $params) as $row) {
            $objects[] = static::fromDatabaseRow($row);
        }
    
        return $objects;
    }
    
    public static function allFor($fields, $values, $view = null, $additional_conditions = null, $additional_params = null, $order_by = 'id', $order_dir = self::ORDER_DESC) {
        if ( ! is_array($fields)) {
            $fields = array($fields);
        } 
        if ( ! is_array($values)) {
            $values = array($values);
        }
        
        $field_count = count($fields);
        if ($field_count == 0 || $field_count != count($values)) {
            throw new SihnonFramework_Exception_InvalidConditions();
        }
        
        $conditions = implode('AND ', array_map(
            function($f) {
                return "`{$f}`=:{$f} ";
            },
            $fields
        ));

        if ($additional_conditions) {
            $conditions .= "AND ({$additional_conditions}) ";
        }
        
        $params = array();
        for ($i = 0; $i < $field_count; ++$i) {
            $params[] = array(
                'name' => $fields[$i],
                'value' => $values[$i],
                'type' => PDO::PARAM_STR
            );
        }
        
        if ($additional_params) {
            $params = array_merge($params, $additional_params);
        }

        return static::all($view, $conditions, $params, $order_by, $order_dir);
    }
    
    public static function exists($field, $value, $view = null) {
        $database = Sihnon_Main::instance()->database();
        
        $result = $database->selectOne('SELECT COUNT(*) AS `count` FROM `'.static::table()."` WHERE `{$field}`=:{$field} LIMIT 1", array(
                array('name' => $field, 'value' => $value, 'type' => PDO::PARAM_STR),
            )
        );
        
        return $result['count'];
    }
    
    protected function create() {
        $database = SihnonFramework_Main::instance()->database();
        
        $class = new ReflectionClass(get_called_class());
        $properties = $class->getproperties();
        
        $fields = array();
        $params = array();
        foreach ($properties as $property) {
            $matches = array();
            if (preg_match('/^_db_(.*)/', $property->name, $matches)) {
                $fields[] = $matches[1];

                $params[] = array(
                    'name' => $matches[1],
                    'value' => ($matches[1] == 'id') ? 'NULL' : $this->{"_db_{$matches[1]}"},
                    'type' => PDO::PARAM_STR
                ); 
            }
        }
        
        $id_list = join(', ', array_map(function($v) { return "`{$v}`"; }, $fields));
        $value_list = join(', ', array_map(function($v) { return ":{$v}"; }, $fields));
        
        $database->insert("INSERT INTO `".static::table()."` ({$id_list}) VALUES({$value_list})", $params);
    
        $this->id = $database->lastInsertId();
    }
    
    public function save() {
        $database = SihnonFramework_Main::instance()->database();
        
        $class = new ReflectionClass(get_called_class());
        $properties = $class->getproperties();
        
        $fields = array();
        $params = array();
        foreach ($properties as $property) {
            $matches = array();
            if (preg_match('/^_db_(.*)/', $property->name, $matches)) {
                if ($matches[1] != 'id') {
                    $fields[] = $matches[1];
                }
        
                $params[] = array(
                            'name' => $matches[1],
                            'value' => $this->{"_db_{$matches[1]}"},
                            'type' => PDO::PARAM_STR
                );
            }
        }
        
        $id_list = join(', ', array_map(function($v) { return "`{$v}`=:{$v}"; }, $fields));
        $value_list = join(', ', array_map(function($v) { return ":{$v}"; }, $fields));
        
        $database->update("UPDATE `".static::table()."` SET {$id_list} WHERE `id`=:id", $params);
    }
    
    public function delete() {
        $database = SihnonFramework_Main::instance()->database();
        
        $database->update(
        	'DELETE FROM `'.static::table().'` WHERE `id`=:id LIMIT 1',
            array(
                array('name' => 'id', 'value' => $this->id, 'type' => PDO::PARAM_INT),
            )
        );
    
        $this->id = null;
    }
    
    public function __set($name, $value) {
        $fullname = "_db_{$name}";
        if ( ! property_exists(get_called_class(), $fullname)){
            throw new SihnonFramework_Exception_InvalidProperty($name);
        }
        
        $this->{$fullname} = $value;
    }
    
    public function __get($name) {
        $fullname = "_db_{$name}";
        if ( ! property_exists(get_called_class(), $fullname)){
            throw new SihnonFramework_Exception_InvalidProperty($name);
        }
        
        return $this->{$fullname};
    }
    
   
}

?>