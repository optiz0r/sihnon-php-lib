<?php

/**
 * IDetails interface
 * 
 * Defines a list of attributes which can be set and retrieved for a User
 */
interface SihnonFramework_Auth_User_IDetails {
    
    /**
     * Gets the user's email address
     *
     * @return string Email address
     */
    public function emailAddress();
    
    /**
     * Sets the user's email address
     * 
     * Requires the backend to implement IUpdateable
     * 
     * @param $emailAddress string New email address
     */
    public function setEmailAddress($emailAddress);
    
    /**
     * Gets the user's real name
     * 
     * @return string Real name
     */
    public function realName();
    
    /**
     * Sets the user's real name
     *
     * @param $realName string New real name
     */
    public function setRealName($realName);
    
    /**
     * Gets the user's last login time
     *
     * @return int Unix timestamp of the last login time
     */
    public function lastLoginTime();
    
    /**
     * Sets the user's last login time
     *
     8 @param $time int Last login time
     */
    public function setLastLoginTime($time);    
    
    /**
     * Gets the user's last password change time
     *
     * @return int Unix timestamp of the last password change
     */
    public function lastPasswordChangeTime();
    
    /**
     * Sets the user's last password change time
     *
     8 @param $time int Last password change time
     */
    public function setLastPasswordChangeTime($time);
    
}

?>