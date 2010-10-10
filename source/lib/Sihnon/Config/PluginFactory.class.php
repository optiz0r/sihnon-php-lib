<?php

class Sihnon_Config_PluginFactory extends Sihnon_PluginFactory {
    
    const PLUGIN_DIR       = 'Sihnon/Config/Plugin/';
    const PLUGIN_PREFIX    = 'Sihnon_Config_Plugin_';
    const PLUGIN_INTERFACE = 'Sihnon_Config_IPlugin';
    
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