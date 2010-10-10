<?php

/**
 * Base class for all plugins, providing default implementations for 
 * standard plugin methods.
 * 
 * @class Sihnon_PluginBase
 */
class Sihnon_PluginBase {
    
    /**
     * Provides a basic initialisation function that does nothing.
     * 
     */
    public static function init() {
        // Nothing to do
    }
    
    /**
     * Returns the name of this plugin
     * 
     * @return string
     */
    public static function name() {
        return static::PLUGIN_NAME;
    }
    
}

?>