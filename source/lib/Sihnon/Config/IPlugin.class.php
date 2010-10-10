<?php

interface Sihnon_Config_IPlugin extends Sihnon_IPlugin {
    
    /**
     * Returns a new instance of the Plugin class
     * 
     * @param array(string=>mixed) $options Configuration options for the Plugin object
     */
    public static function create($options);
    
    /**
     * Loads all the configuration items from the storage backend
     * 
     * @param string $source_filename Filename of the source
     * @param bool $scan Request that the source be scanned for content. Defaults to true.
     * @param bool $use_cache Request that the cache be used. Defaults to true.
     * @return RippingCluster_Source
     */
    public function preload();
    
    /**
     * Saves the value of all configuration items back into the storage backend
     */
    public function save();
    
}

?>