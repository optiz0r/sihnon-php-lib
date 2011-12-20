<?php

abstract class SihnonFramework_DatabaseObject {
    
    protected static $table;
    
    protected static function table() {
        return static::$table;
    }
    
    protected function setDatabaseProperties(array $properties) {
        foreach($properties as $property => $value) {
            if (property_exists(get_called_class(), '_db_' . $property)) {
                $this->{'_db_' . $property} = $value;
            } else {
                throw new Sihnon_Exception_InvalidProperty($property);
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
        $database = SihnonFramework_Main::instance()->database();
        
        $object = self::fromDatabaseRow(
            $database->selectOne('SELECT * FROM `'.static::table().'` WHERE id=:id', array(
                    array('name' => 'id', 'value' => $id, 'type' => PDO::PARAM_INT)
                )
            )
        );
    
        return $object;
    }
    
    /**
     * Load a DatabaseObject given a field name and value
     * 
     * @param string $name Name of the field to match on
     * @param mixed $value Value of the field to match on
     */
    public static function from($field, $value) {
        $database = SihnonFramework_Main::instance()->database();
        
        $object = self::fromDatabaseRow(
            $database->selectOne("SELECT * FROM `".static::table()."` WHERE `{$field}`=:{$field}", array(
                    array('name' => $field, 'value' => $value, 'type' => PDO::PARAM_STR)
                )
            )
        );
        
        return $object;        
    }
    
    /**
     * Load a list of all objects
     * 
	 * @return SihnonFramework_DatabaseObject
     */
    public static function all() {
        $database = SihnonFramework_Main::instance()->database();
        
        $objects = array();
        foreach ($database->selectList('SELECT * FROM `'.static::table().'` WHERE `id` > 0 ORDER BY `id` DESC') as $row) {
            $objects[] = static::fromDatabaseRow($row);
        }
    
        return $objects;
    }
    
    public static function all_for($field, $value, $view = null, $additional_conditions = null, $additional_params = null) {
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
        
        $field_list = join(', ', array_map(function($v) { return "`{$v}`"; }, $fields));$objects = array();
        
        $params = array(
            array('name' => $field, 'value' => $value, 'type' => PDO::PARAM_STR),
        );
        if ($additional_params) {
            $params = array_merge($params, $additional_params);
        }
        
        if ($additional_conditions) {
            $conditions = "AND ({$additional_conditions}) ";
        }
        
        foreach ($database->selectList("SELECT {$field_list} FROM `{$view}` WHERE `{$field}`=:{$field} {$conditions} ORDER BY `id` DESC", $params) as $row) {
            $objects[] = static::fromDatabaseRow($row);
        }
        
        return $objects;
    }
    
    public static function exists($field, $value, $view = null) {
        $database = Sihnon_Main::instance()->database();
        
        return $database->selectOne('SELECT COUNT(*) FROM `'.static::table().'` "WHERE `{$field}`=:{$field} LIMIT 1', array(
                array('name' => $field, 'value' => $value, 'type' => PDO::PARAM_STR),
            )
        );
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
    
    protected function save() {
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