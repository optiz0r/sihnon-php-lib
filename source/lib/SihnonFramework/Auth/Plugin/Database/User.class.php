<?php

class SihnonFramework_Auth_Plugin_Database_User extends Sihnon_DatabaseObject implements Sihnon_Auth_IUser {
    
    protected static $table = 'user';

    protected $_db_id;
    protected $_db_username;
    protected $_db_password;
    protected $_db_fullname;
    protected $_db_email;
    protected $_db_last_login;
    protected $_db_last_password_change;
    
    protected $groups = null;
    protected $permissions = null;
    
    public static function exists($username) {
        return parent::exists('username', $username);
    }
    
    public static function add($username, $password) {
        $user = new self();
        $user->username = $username;
        $user->password = sha1($password);
        $user->last_password_change = time();
        $user->create();
        
        return $user;
    }
    
    public function username() {
        return $this->username;
    }
    
    public function checkPassword($password) {
        return ($this->password == sha1($password));
    }
    
    public function changePassword($new_password) {
        $this->password = sha1($new_password);
        $this->save();
    }
    
    public function groups($ignore_cache = false) {
        if ($this->groups === null || $ignore_cache) {
            $this->groups = Sihnon_Auth_Plugin_Database_Group::allFor('user', $this->id, 'groups_by_user');
        }
        
        return $this->groups;
    }
    
    public function permissions($ignore_cache = false) {
        if ($this->permissions === null || $ignore_cache) {
            $this->permissions = Sihnon_Auth_Plugin_Database_Permission::allFor('user', $this->id, 'permissions_by_user');
        }
        
        return $this->permissions;
    }
    
    public function hasPermission($permission) {
        $permissions = $this->permissions();
        foreach ($permissions as $has_permission) {
            if ($permission == $has_permission->id) {
                return true;
            }
        }
        
        return false;
    }
    
}

?>