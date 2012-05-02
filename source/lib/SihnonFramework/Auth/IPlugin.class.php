<?php

/**
 * Defines methods which authentication backend plugins must implement
 */
interface SihnonFramework_Auth_IPlugin extends Sihnon_IPlugin {
    
    /**
     * Creates a new instance of the Auth Plugin
     * 
     * @param SihnonFramework_Config $config Config option to retrieve plugin configuration
     * @return SihnonFramework_Auth_IPlugin
     */
    public static function create(SihnonFramework_Config $config);
   
    /**
     * Checks to see whether a given username exists within the backend
     *
     * @param string $username Unique login name for the user to be checked.
     * @return bool Returns true if the user is known to the backend, false otherwise.
     */
    public function userExists($username);
    
    /**
     * Returns a list of all users known to the backend.
     *
     * @return array(Sihnon_Auth_IUser)
     */
    public function listUsers();
    
    /**
     * Retrieves a user with the given username after verifying the supplied password is correct.
     * 
     * @param string $username Unique login name of the user.
     * @param string $password Plaintext password of the user to be authenticated.
     * @return Sihnon_Auth_IUser User object for the now-authenticated account
     * 
     * @throws Sihnon_Exception_UnknownUser
     * @throws Sihnon_Exception_IncorrectPassword
     */
    public function authenticate($username, $password);
    
    /**
     * Retrieves a user without also verifying a password.
     *
     * This is used to get the details of a user without logging in as that user.
     *
     * @param string $username Unique login name of the user.
     * @param Sihnon_Auth_IUser User object
     */
    public function user($username);
    
}

?>