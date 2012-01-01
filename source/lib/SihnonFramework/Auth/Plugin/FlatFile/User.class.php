<?php

class SihnonFramework_Auth_Plugin_FlatFile_User implements Sihnon_Auth_IUser {
    
    public static function exists($username) {
        throw new SihnonFramework_Exception_NotImplemented();
    }
    
    public static function load($username) {
        throw new SihnonFramework_Exception_NotImplemented();
    }

    public static function add($username, $password) {
        throw new SihnonFramework_Exception_NotImplemented();
    }
    
    public function username() {
        return $this->username;
    }
    
    public function checkPassword($password) {
        return ($this->password == sha1($password));
    }
    
    public function changePassword($new_password) {
        throw new SihnonFramework_Exception_NotImplemented();
    }
    
    public function isAdministrator() {
        throw new SihnonFramework_Exception_NotImplemented();
    }
    
}

?>