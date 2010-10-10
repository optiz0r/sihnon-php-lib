<?php

class SihnonFramework_Log_PluginFactory extends Sihnon_PluginFactory {
    
    protected static $plugin_prefix    = 'Sihnon_Log_Plugin_';
    protected static $plugin_interface = 'Sihnon_Log_IPlugin';
    protected static $plugin_dir       = array(
    	SihnonFramework_Lib => 'SihnonFramework/Log/Plugin/',
    	Sihnon_Lib          => 'Sihnon/Log/Plugin/',
	);
    
    public static function init() {
        
    }
    
    public static function create($plugin, $options) {
        self::ensureScanned();
        
        if (! self::isValidPlugin($plugin)) {
            throw new Sihnon_Exception_InvalidPluginName($plugin);
        }
        
        $classname = self::classname($plugin);
        
        return call_user_func(array($classname, 'create'), $options);
    }
    
}

?>