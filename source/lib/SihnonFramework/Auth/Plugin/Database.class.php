<?php

class SihnonFramework_Auth_Plugin_Database
    extends    Sihnon_PluginBase 
    implements Sihnon_Auth_IPlugin, 
               Sihnon_Auth_IUpdateable, 
               Sihnon_Auth_IFinelyPermissionable, 
               Sihnon_Auth_IDetails {

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
        
        $user->setLastLoginTime(time());
        $user->save();
        
        return $user;
    }
    
    public function user($username) {
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
        return $user->hasPermission($permission);
    }
    
    /*
     * IDetails methods
     */
     
    /**
     * Gets the user's email address
     *
     * @param $user Sihnon_Auth_IUser User
     * @return string Email address
     */
    public function emailAddress(Sihnon_Auth_IUser $user) {
        return $user->emailAddress();
    }
    
    /**
     * Sets the user's email address
     * 
     * Requires the backend to implement IUpdateable
     * 
     * @param $user Sihnon_Auth_IUser User
     * @param $emailAddress string New email address
     */
    public function setEmailAddress(Sihnon_Auth_IUser $user, $emailAddress) {
        return $user->setEmailAddress($emailAddress);
    }
    
    /**
     * Gets the user's real name
     * 
     * @param $user Sihnon_Auth_IUser User
     * @return string Real name
     */
    public function realName(Sihnon_Auth_IUser $user) {
        return $user->realName();
    }
    
    /**
     * Sets the user's real name
     *
     * @param $user Sihnon_Auth_IUser User
     * @param $realName string New real name
     */
    public function setRealName(Sihnon_Auth_IUser $user, $realName) {
        return $user->setRealName($realName);
    }
    
    /**
     * Gets the user's last login time
     *
     * @param $user Sihnon_Auth_IUser User
     * @return int Unix timestamp of the last login time
     */
    public function lastLoginTime(Sihnon_Auth_IUser $user) {
        return $user->lastLoginTime();
    }
    
    /**
     * Sets the user's last login time
     *
     * @param $user Sihnon_Auth_IUser User
     8 @param $time int Last login time
     */
    public function setLastLoginTime(Sihnon_Auth_IUser $user, $time) {
        return $user->setLastLoginTime($time);
    }

    /**
     * Gets the user's last password change time
     *
     * @param $user Sihnon_Auth_IUser User
     * @return int Unix timestamp of the last password change
     */
    public function lastPasswordChangeTime(Sihnon_Auth_IUser $user) {
        return $user->lastPasswordChangeTime();
    }
    
    /**
     * Sets the user's last password change time
     *
     * @param $user Sihnon_Auth_IUser User
     8 @param $time int Last password change time
     */
    public function setLastPasswordChangeTime(Sihnon_Auth_IUser $user, $time) {
        return $user->setLastPasswordChangeTime($time);
    }
    
}

?>