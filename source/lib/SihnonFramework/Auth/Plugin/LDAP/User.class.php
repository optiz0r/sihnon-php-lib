<?php

class SihnonFramework_Auth_Plugin_LDAP_User implements Sihnon_Auth_IUser {
    
    protected static $ldap;
    protected static $user_base_dn;
    protected static $group_base_dn;
    protected static $recursive_search;
    
    protected $groups = null;
    
    protected $fullname;
    protected $username;
    
    public static function init($ldap, $user_base_dn, $group_base_dn, $recursive_search) {
        static::$ldap = $ldap;
        static::$user_base_dn = $user_base_dn;
        static::$group_base_dn = $group_base_dn;
        static::$recursive_search = $recursive_search;
    }
        
    public static function exists($username) {
        $ldap_username = Sihnon_Auth_Plugin_LDAP::ldapEscape($username);
        $filter = "(&(objectClass=posixAccount)(uid={$ldap_username}))";
        
        $search = ldap_search(static::$ldap, static::$user_base_dn, $filter, array('uid'), 0, 1);
        $result = ldap_get_entries(static::$ldap, $search);
        
        return $result['count'] == 1;
    }
    
    protected static function fromLDAP($result) {
        $user = new self();
        $user->fullname = $result['cn'][0];
        $user->username = $result['uid'][0];
        
        return $user;
    }
    
    public static function load($username) {
        $ldap_username = Sihnon_Auth_Plugin_LDAP::ldapEscape($username);
        $filter = "(&(objectClass=posixAccount)(uid={$ldap_username}))";
        
        $search = ldap_search(static::$ldap, static::$user_base_dn, $filter, array('cn', 'uid'), 0, 1);
        $result = ldap_get_entries(static::$ldap, $search);
        
        if ($result['count'] != 1) {
            throw new Sihnon_Exception_UnknownUser($username);
        }

        return static::fromLDAP($result[0]);
    }
    
    public static function all() {
        $filter = "(objectClass=posixAccount)";
        
        $search = null;
        if (static::$recursive_search) {
            $search = ldap_search(static::$ldap, static::$user_base_dn, $filter, array('cn', 'uid'), 0);
        } else {
            $search = ldap_list(static::$ldap, static::$user_base_dn, $filter, array('cn', 'uid'), 0);
        }
        $result = ldap_get_entries(static::$ldap, $search);
        
        $users = array();
        for ($i = 0, $l = $result['count']; $i < $l; ++$i) {
            $users[] = static::fromLDAP($result[$i]);
        }

        return $users;
    }
    
    public function username() {
        return $this->username;
    }
    
    public function checkPassword($password) {
        $ldap_user_dn = Sihnon_Auth_Plugin_LDAP::ldapEscape($this->fullname, true);
        return ldap_bind(static::$ldap, "cn={$ldap_user_dn},".static::$base_dn, $password);
    }
    
    public function changePassword($new_password) {
        throw new Sihnon_Exception_NotImplemented();
    }
    
    public function isAdministrator() {
        return $this->hasPermission('wheel');
    }
    
    public function hasPermission($permission) {
        return in_array($permission, $this->permissions());
    }
    
    public function permissions() {
        if ($this->groups === null) {
            $ldap_username = Sihnon_Auth_Plugin_LDAP::ldapEscape($this->username);
            
            $filter = "(&(objectClass=posixGroup)(memberUid={$ldap_username}))";
            
            $search = null;
            if (static::$recursive_search) {
                $search = ldap_search(static::$ldap, static::$group_base_dn, $filter, array('cn'), 0);
            } else {
                $search = ldap_list(static::$ldap, static::$group_base_dn, $filter, array('cn'), 0);
            }
            $result = ldap_get_entries(static::$ldap, $search);
            
            $this->groups = array();
            for ($i = 0, $l = $result['count']; $i < $l; ++$i) {
                $this->groups[] = $result[$i]['cn'][0];
            }
            
        }
        
        return $this->groups;
    }
    
    public function __get($name) {
        switch ($name) {
            case 'username': {
                return $this->username;
            } break;
            
            case 'fullname': {
                return $this->fullname;
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