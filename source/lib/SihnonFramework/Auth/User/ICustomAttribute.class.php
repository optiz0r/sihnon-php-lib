<?php

/**
 * ICustomAttribute interface
 * 
 * Defines methods for setting and retrieving extensible attributes not specifically known by the backend
 */
interface SihnonFramework_Auth_User_ICustomAttribute {

    /**
     * Lists the names of all configured custom attributes
     *
     * @return array(string)
     */
    public function availableCustomAttributes();
    
    /**
     * Returns the name and value of all configured custom attributes
     *
     * @return array(string=>mixed)
     */
    public function allCustomAttributes();
    
    /**
     * Gets the value of a custom attribute
     *
     * @param $name string Attribute name
     * @return string Attribute value
     */
    public function customAttribute($name);
    
    /**
     * Sets the value of a custom attribute
     * 
     * Requires the backend to implement IUpdateable
     * 
     * @param $name string Attribute name
     * @param $value mixed Attribute value
     */
    public function setCustomAttribute($name, $value);
        
}

?>