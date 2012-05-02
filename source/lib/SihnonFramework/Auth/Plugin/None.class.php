<?php

class SihnonFramework_Auth_Plugin_None
    extends    Sihnon_PluginBase
    implements Sihnon_Auth_IPlugin {

    protected function __construct() {
        
    }
    
    /*
     * IPlugin methods
    */
    
    public static function create(SihnonFramework_Config $config) {
        return new self();
    }
    
    public function userExists($username) {
        throw new SihnonFramework_Exception_NotImplemented();
    }
    
    public function listUsers() {
        throw new SihnonFramework_Exception_NotImplemented();
    }
    
    public function authenticate($username, $password) {
        throw new SihnonFramework_Exception_NotImplemented();
    }
    
    public function user($username) {
        throw new SihnonFramework_Exception_NotImplemented();
    }
    
}

?>