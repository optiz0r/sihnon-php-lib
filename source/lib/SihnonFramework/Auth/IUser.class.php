<?php

/**
 * Provides methods to retrieve information about a user object stored in an authentication backend
 */
interface SihnonFramework_Auth_IUser {

    /**
     * Returns the unique identifier for the user
     *
     * Depending on the implementation, this could be a numeric or a username
     *
     * @return mixed Unique identifier
     */
    public function id();
    
    /**
     * Returns the username
     * 
     * @return string Username
     */
    public function username();
    
    /**
     * Checks the given password against the one stored for this user
     *
     * @param $password string Password to compare
     * @return bool Returns true if the password matches, false otherwise
     */
    public function checkPassword($password);
    
}

?>