<?php

class SihnonFramework_Auth_PluginFactory extends Sihnon_PluginFactory {
    
    protected static $plugin_prefix    = 'Sihnon_Auth_Plugin_';
    protected static $plugin_interface = 'SihnonFramework_Auth_IPlugin';
    protected static $plugin_dir       = array(
    	SihnonFramework_Lib => 'SihnonFramework/Auth/Plugin/',
    	Sihnon_Lib          => 'Sihnon/Auth/Plugin/',
	);
    
    public static function init() {
        
    }
    
    public static function create(SihnonFramework_Config $config, $plugin) {
        self::ensureScanned();
        
        if (! self::isValidPlugin($plugin)) {
            throw new Sihnon_Exception_InvalidPluginName($plugin);
        }
        
        $classname = self::classname($plugin);
        
        return call_user_func(array($classname, 'create'), $config);
    }
    
}

?>