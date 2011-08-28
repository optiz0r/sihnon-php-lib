<?php

class SihnonFramework_Config_Plugin_Database extends Sihnon_PluginBase implements Sihnon_Config_IPlugin, Sihnon_Config_IUpdateable {
    
    /**
     * Name of this plugin
     * @var string
     */
    const PLUGIN_NAME = "Database";
    
    private $database;
    private $table;
    
    protected function __construct($options) {
        $this->database = $options['database'];
        $this->table    = $options['table'];
    }
    
    public static function create($options) {
        return new self($options);
    }
    
    public function preload() {
        return $this->database->selectAssoc("SELECT name,type,value FROM {$this->table}", 'name', array('name', 'value', 'type'));
    }
    
    public function save() {
        throw new Sihnon_Exception_NotImplemented();
    }
    
    public function set($key, $value) {
        return $this->database->update("UPDATE `{$this->table}` SET `value`=:value WHERE `name`=:name", array(
            array('name' => 'name',  'value' => $key,   'type' => PDO::PARAM_STR),
            array('name' => 'value', 'value' => $value, 'type' => PDO::PARAM_STR),
        ));
    }
    
    public function add($key, $type, $value) {
        return $this->database->insert("INSERT INTO `{$this->table}` (`name`,`value`,`type`) VALUES(:name,:value,:type)", array(
            array('name' => 'name',  'value' => $key,   'type' => PDO::PARAM_STR),
            array('name' => 'value', 'value' => $value, 'type' => PDO::PARAM_STR),
            array('name' => 'type',  'value' => $type,  'type' => PDO::PARAM_STR),
        ));
    }
    
}

?>