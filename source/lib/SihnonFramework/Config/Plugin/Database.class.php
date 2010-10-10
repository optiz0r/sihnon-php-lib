<?php

class SihnonFramework_Config_Plugin_Database extends Sihnon_PluginBase implements Sihnon_Config_IPlugin {
    
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
    
}

?>