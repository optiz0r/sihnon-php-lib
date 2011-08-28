<?php

interface SihnonFramework_Config_IUpdateable {
    
    /**
     * 
     * Change the value of a setting
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function set($key, $value);
    
    /**
     * 
     * Add a new setting
     * @param string $key
     * @param string $type
     * @param mixed $value
     * @return bool
     */
    public function add($key, $type, $value);
    
    /**
     * 
     * Remove a setting
     * @param string $key
     * @return bool
     */
    public function remove($key);
    
}

?>