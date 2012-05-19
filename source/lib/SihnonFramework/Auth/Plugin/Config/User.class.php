<?php

class SihnonFramework_Auth_Plugin_Config_User implements Sihnon_Auth_IUser {
    
    protected static $config;
    
    protected $username;
    protected $password;
    
    public static function init($config) {
        static::$config = $config;
    }
    
    public static function exists($username) {
        return static::administratorUsername() == $username;
    }
    
    public static function load($username) {
        if ( ! static::exists($username)) {
            throw new Sihnon_Exception_UnknownUser($username);
        }
        
        $user = new self();
        $user->username = static::$config->get('auth.Config.admin-username');
        $user->password = static::$config->get('auth.Config.admin-password');
        
        return $user;
    }
    
    public static function loadAdmin() {
        return static::load(static::administratorUsername());
    }

    public function id() {
        return $this->username;
    }
    
    public function username() {
        return $this->username;
    }
    
    public function checkPassword($password) {
        return ($this->password == sha1($password));
    }
    
    public function changePassword($new_password) {
        $this->password = sha1($new_password);
        static::$config->set('auth.Config.admin-password', $this->password);
    }
    
    public function isAdministrator() {
        return true;
    }
    
    protected static function administratorUsername() {
        if ( ! static::$config->exists('auth.Config.admin-username')) {
            return 'admin';
        }
        
        return static::$config->get('auth.Config.admin-username');
    }
    
    public function __get($name) {
        switch ($name) {
            case 'username': {
                return $this->username;
            } break;
            
            default: {
                throw new Sihnon_Exception_InvalidProperty($name);
            } break;
        }
    }
    
    public function __set($name, $value) {
        throw new Sihnon_Exception_NotImplemented();        
    }
}

?>