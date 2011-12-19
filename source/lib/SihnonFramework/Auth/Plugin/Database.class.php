<?php

class SihnonFramework_Auth_Plugin_Database
    extends    Sihnon_PluginBase 
    implements Sihnon_Auth_IPlugin, 
               Sihnon_Auth_IUpdateable, 
               Sihnon_Auth_IFinelyPermissionable {

    protected $config;
    protected $database;
    
    protected function __construct($config) {
        $this->config = $config;
        $this->database = SihnonFramework_Main::instance()->database();
    }
    
    /*
     * IPlugin methods 
     */
    
    public static function create(SihnonFramework_Config $config) {
        return new self($config);
    }
    
    public function userExists($username) {
        return Sihnon_Auth_Plugin_Database_User::exists($username);
    }
    
    public function listUsers() {
        return Sihnon_Auth_Plugin_Database_User::all();
    }
    
    public function authenticate($username, $password) {
        try {
            $user = Sihnon_Auth_Plugin_Database_User::from('username', $username);
        } catch (Sihnon_Exception_ResultCountMismatch $e) {
            throw new Sihnon_Exception_UnknownUser();
        }
        
        if ( ! $user->checkPassword($password)) {
            throw new Sihnon_Exception_IncorrectPassword();
        }
        
        return $user;
    }
    
    public function authenticateSession($username) {
        return Sihnon_Auth_Plugin_Database_User::from('username', $username);
    }
    
    /*
     * IUpdateable methods
     */
    
    public function addUser($username, $password) {
        return Sihnon_Auth_Plugin_Database_User::add($username, $password);
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
        // As this class supports fine-grained permissions, map the isAdministrator function to the Superadmin privilege
        // to fallback for badly written applications
        return $user->hasPermission(Sihnon_Auth_Plugin_Database_Permission::PERM_Administrator);
    }
    
    /*
     * IFinelyPermissionable methods
     */

    public function hasPermission(Sihnon_Auth_IUser $user, $permission) {
        return new $user->hasPermission($permission);
    }
    
}

?>