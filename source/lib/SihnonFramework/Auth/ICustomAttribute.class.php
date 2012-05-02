<?php

/**
 * ICustomAttribute interface
 * 
 * Defines methods for setting and retrieving extensible attributes not specifically known by the backend
 *
 * Backends which implement this interface myst also implement the SihnonFramework_Auth_User_ICustomAttribute
 * interface on their Sihnon_Auth_IUser class(es).
 */
interface SihnonFramework_Auth_ICustomAttribute {

    /**
     * Lists the names of all configured custom attributes
     *
     * @param $user Sihnon_Auth_IUser User
     * @return array(string)
     */
    public function availableCustomAttributes(Sihnon_Auth_IUser $user);
    
    /**
     * Returns the name and value of all configured custom attributes
     *
     * @param $user Sihnon_Auth_IUser User
     * @return array(string=>mixed)
     */
    public function allCustomAttributes(Sihnon_Auth_IUser $user);
    
    /**
     * Gets the value of a custom attribute
     *
     * @param $user Sihnon_Auth_IUser User
     * @param $name string Attribute name
     * @return string Attribute value
     */
    public function customAttribute(Sihnon_Auth_IUser $user, $name);
    
    /**
     * Sets the value of a custom attribute
     * 
     * Requires the backend to implement IUpdateable
     * 
     * @param $user Sihnon_Auth_IUser User
     * @param $name string Attribute name
     * @param $value mixed Attribute value
     */
    public function setCustomAttribute(Sihnon_Auth_IUser $user, $name, $value);
        
}

?>