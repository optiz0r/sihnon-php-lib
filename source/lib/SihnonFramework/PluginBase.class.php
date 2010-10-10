<?php

/**
 * Base class for all plugins, providing default implementations for 
 * standard plugin methods.
 * 
 * @class SihnonFramework_PluginBase
 */
class SihnonFramework_PluginBase {
    
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