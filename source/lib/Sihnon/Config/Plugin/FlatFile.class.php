<?php

class Sihnon_Config_Plugin_FlatFile extends Sihnon_PluginBase implements Sihnon_Config_IPlugin {
    
    /**
     * Name of this plugin
     * @var string
     */
    const PLUGIN_NAME = "FlatFile";
    
    protected $filename;
    
    protected function __construct($options) {
        $this->filename = $options['filename'];
    }
    
    public static function create($options) {
        return new self($options);
    }
    
    public function preload() {
        return parse_ini_file($this->filename, true);;
    }
    
    public function save() {
        throw new Sihnon_Exception_NotImplemented();
    }
    
}

?>