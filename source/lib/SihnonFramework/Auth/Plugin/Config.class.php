<?php

class SihnonFramework_Auth_Plugin_Config
    extends    Sihnon_PluginBase
    implements Sihnon_Auth_IPlugin,
               Sihnon_Auth_IUpdateable,
               Sihnon_Auth_IPermissionable {

    protected $config;
    
    protected function __construct($config) {
        $this->config = $config;
        
        Sihnon_Auth_Plugin_Config_User::init($config);
    }
    
    /*
     * IPlugin methods
    */
    
    public static function create(SihnonFramework_Config $config) {
        return new self($config);
    }
    
    public function userExists($username) {
        return Sihnon_Auth_Plugin_Config_User::exists($username);
    }
    
    public function listUsers() {
        return array(
            Sihnon_Auth_Plugin_Config_User::loadAdmin(),
        );
    }
    
    public function authenticate($username, $password) {
        $user = Sihnon_Auth_Plugin_Config_User::load($username);
    
        if ( ! $user->checkPassword($password)) {
            throw new Sihnon_Exception_IncorrectPassword();
        }
    
        return $user;
    }
    
    public function user($username) {
        return Sihnon_Auth_Plugin_Config_User::load($username);
    }
    
    /*
     * IUpdateable methods
    */
    
    public function addUser($username, $password) {
        throw new Sihnon_Exception_NotImplemented();
    }
    
    public function removeUser(Sihnon_Auth_IUser $user) {
        throw new Sihnon_Exception_NotImplemented();
    }
    
    public function changePassword(Sihnon_Auth_IUser $user, $new_password) {
        $user->changePassword($new_password);
    }
    
    /*
     * IPermissionable methods
    */
    
    public function isAdministrator(Sihnon_Auth_IUser $user) {
        return $user->isAdministrator();
    }
    
    public function hasPermission(Sihnon_Auth_IUser $user, $permission) {
        return $user->isAdministrator();
    }

}

?>