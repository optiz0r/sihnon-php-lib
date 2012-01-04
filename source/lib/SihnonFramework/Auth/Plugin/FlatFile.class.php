<?php

class SihnonFramework_Auth_Plugin_FlatFile
    extends    Sihnon_PluginBase
    implements Sihnon_Auth_IPlugin,
               Sihnon_Auth_IUpdateable,
               Sihnon_Auth_IPermissionable {

    protected $config;
    
    protected function __construct($config) {
        $this->config = $config;
    }
    
    /*
     * IPlugin methods
    */
    
    public static function create(SihnonFramework_Config $config) {
        return new self($config);
    }
    
    public function userExists($username) {
        return Sihnon_Auth_Plugin_FlatFile_User::exists($username);
    }
    
    public function listUsers() {
        return Sihnon_Auth_Plugin_FlatFile_User::all();
    }
    
    public function authenticate($username, $password) {
        $user = Sihnon_Auth_Plugin_FlatFile_User::load($username);
    
        if ( ! $user->checkPassword($password)) {
            throw new Sihnon_Exception_IncorrectPassword();
        }
    
        return $user;
    }
    
    public function authenticateSession($username) {
        return Sihnon_Auth_Plugin_FlatFile_User::load($username);
    }
    
    /*
     * IUpdateable methods
    */
    
    public function addUser($username, $password) {
        return Sihnon_Auth_Plugin_FlatFile_User::add($username, $password);
    }
    
    public function removeUser(Sihnon_Auth_IUser $user) {
        $user->delete();
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

}

?>