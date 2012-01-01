<?php

interface SihnonFramework_Auth_IPlugin extends Sihnon_IPlugin {
    
    /**
     * Creates a new instance of the Auth Plugin
     * 
     * @param SihnonFramework_Config $config Config option to retrieve plugin configuration
     * @return SihnonFramework_Auth_IPlugin
     */
    public static function create(SihnonFramework_Config $config);
    
    public function userExists($username);
    
    public function listUsers();
    
    public function authenticate($username, $password);
    
    public function authenticateSession($username);
    
}

?>