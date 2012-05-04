<?php

class SihnonFramework_Auth_Plugin_Database_User
    extends    Sihnon_DatabaseObject
    implements Sihnon_Auth_IUser,
               Sihnon_Auth_User_IDetails,
               Sihnon_Auth_User_IUpdateable,
               Sihnon_Auth_User_IGroupable {
    
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
    
    public function id() {
        return $this->id;
    }
    
    public function username() {
        return $this->username;
    }
    
    public function checkPassword($password) {
        return ($this->password == sha1($password));
    }
    
    public function changePassword($new_password) {
        $this->password = sha1($new_password);
        $this->last_password_change = time();
        $this->save();
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
    
    /**
     * Gets the user's email address
     *
     * @return string Email address
     */
    public function emailAddress() {
        return $this->email;
    }
    
    /**
     * Sets the user's email address
     * 
     * Requires the backend to implement IUpdateable
     * 
     * @param $emailAddress string New email address
     */
    public function setEmailAddress($emailAddress) {
        $this->email = $emailAddress;
    }
    
    /**
     * Gets the user's real name
     * 
     * @return string Real name
     */
    public function realName() {
        return $this->fullname;
    }
    
    /**
     * Sets the user's real name
     *
     * @param $realName string New real name
     */
    public function setRealName($realName) {
        $this->fullname = $realName;
    }
    
    /**
     * Gets the user's last login time
     *
     * @return int Unix timestamp of the last login time
     */
    public function lastLoginTime() {
        return $this->last_login;
    }
    
    /**
     * Sets the user's last login time
     *
     8 @param $time int Last login time
     */
    public function setLastLoginTime($time) {
        $this->last_login = $time;
    }
    
    /**
     * Gets the user's last password change time
     *
     * @return int Unix timestamp of the last password change
     */
    public function lastPasswordChangeTime() {
        return $this->last_password_change;
    }
    
    /**
     * Sets the user's last password change time
     *
     8 @param $time int Last password change time
     */
    public function setLastPasswordChangeTime($time) {
        $this->last_password_change =  $time;
    }
    
    /*
     * IGroupable methods
     */
     
    /**
     * Returns all users for a given group
     *
     * @return array(Sihnon_Auth_IUser)
     */
    public static function allForGroup(Sihnon_Auth_IGroup $group) {
        return self::allFor('group', $group->id(),  'users_by_group');
    }
    
    /**
     * Returns all groups for this user
     *
     * @return array(Sihnon_Auth_IGroup)
     */
    public function groups($ignore_cache = false) {
        if ($this->groups === null || $ignore_cache) {
            $this->groups = Sihnon_Auth_Plugin_Database_Group::allFor('user', $this->id, 'groups_by_user');
        }
        
        return $this->groups;
    }

    
}

?>