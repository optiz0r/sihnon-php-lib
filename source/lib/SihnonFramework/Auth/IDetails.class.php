<?php

/**
 * IDetails interface
 * 
 * Defines a list of attributes which can be set and retrieved for a User
 *
 * Backends which implement this interface myst also implement the SihnonFramework_Auth_User_IDetails
 * interface on their Sihnon_Auth_IUser class(es).
 */
interface SihnonFramework_Auth_IDetails {
    
    /**
     * Gets the user's email address
     *
     * @param $user Sihnon_Auth_IUser User
     * @return string Email address
     */
    public function emailAddress(Sihnon_Auth_IUser $user);
    
    /**
     * Sets the user's email address
     * 
     * Requires the backend to implement IUpdateable
     * 
     * @param $user Sihnon_Auth_IUser User
     * @param $emailAddress string New email address
     */
    public function setEmailAddress(Sihnon_Auth_IUser $user, $emailAddress);
    
    /**
     * Gets the user's real name
     * 
     * @param $user Sihnon_Auth_IUser User
     * @return string Real name
     */
    public function realName(Sihnon_Auth_IUser $user);
    
    /**
     * Sets the user's real name
     *
     * @param $user Sihnon_Auth_IUser User
     * @param $realName string New real name
     */
    public function setRealName(Sihnon_Auth_IUser $user, $realName);
    
    /**
     * Gets the user's last login time
     *
     * @param $user Sihnon_Auth_IUser User
     * @return int Unix timestamp of the last login time
     */
    public function lastLoginTime(Sihnon_Auth_IUser $user);
    
    /**
     * Sets the user's last login time
     *
     * @param $user Sihnon_Auth_IUser User
     8 @param $time int Last login time
     */
    public function setLastLoginTime(Sihnon_Auth_IUser $user, $time);
    
    /**
     * Gets the user's last password change time
     *
     * @param $user Sihnon_Auth_IUser User
     * @return int Unix timestamp of the last password change
     */
    public function lastPasswordChangeTime(Sihnon_Auth_IUser $user);
    
    /**
     * Sets the user's last password change time
     *
     * @param $user Sihnon_Auth_IUser User
     8 @param $time int Last password change time
     */
    public function setLastPasswordChangeTime(Sihnon_Auth_IUser $user, $time);
    
}

?>