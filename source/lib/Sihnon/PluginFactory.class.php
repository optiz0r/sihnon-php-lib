<?php

abstract class Sihnon_PluginFactory implements Sihnon_IPluginFactory {
    
    static private $validPlugins = array();
    
    /**
     * Scan the plugin directory for potential plugins, and load any valid ones found
     * 
     * @param bool $force Rescan the plugin directory even if it has already been scanned before
     */
    public static function scan($force = false) {
        if ($force || ! isset(self::$validPlugins[get_called_class()])) {
            $candidatePlugins = static::findPlugins(static::PLUGIN_DIR);
            
            static::loadPlugins($candidatePlugins, static::PLUGIN_PREFIX, static::PLUGIN_INTERFACE);
        }
    }
        
    protected static function ensureScanned() {
        if (! isset(self::$validPlugins[get_called_class()])) {
            static::scan();
        }
    }
    
    protected static function isValidPlugin($plugin) {
        return isset(self::$validPlugins[get_called_class()][$plugin]);
    }
    
    public static function getValidPlugins() {
        static::ensureScanned();
        return array_keys(self::$validPlugins[get_called_class()]);
    }
    
    protected static function findPlugins($directory) {
        $plugins = array();
        
        $iterator = new Sihnon_Utility_ClassFilesIterator(new Sihnon_Utility_VisibleFilesIterator(new DirectoryIterator(Sihnon_Lib . $directory)));
        
        foreach ($iterator as /** @var SplFileInfo */ $file) {
            $plugin = preg_replace('/.class.php$/', '', $file->getFilename());
            $plugins[] = $plugin;
        }
        
        return $plugins;
    }
    
    protected static function loadPlugins($plugins, $prefix, $interface) {
        self::$validPlugins[get_called_class()] = array();
        
        foreach ($plugins as $plugin) {
            $fullClassname = $prefix . $plugin;
            if ( ! class_exists($fullClassname, true)) {
                continue;
            }
            
            if ( ! in_array($interface, class_implements($fullClassname))) {
                continue;
            }
            
            // Initialise the plugin
            call_user_func(array($fullClassname, 'init'));
        
            self::$validPlugins[get_called_class()][$plugin] = $fullClassname;
        }
    }
    
    public static function classname($plugin) {
        static::ensureScanned();
        
        if ( ! self::isValidPlugin($plugin)) {
            throw new Sihnon_Exception_InvalidPluginName($plugin);
        }
        
        return self::$validPlugins[get_called_class()][$plugin];
    }
    
}

?>