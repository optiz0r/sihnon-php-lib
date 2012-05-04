<?php

class SihnonFramework_Auth_Plugin_Database_Permission
    extends    Sihnon_DatabaseObject
    implements Sihnon_Auth_IPermission {
    
    /*
     * Built-in permissions
     */
    
    // The Administrator permission always exists, and is always offers the most functionality
    // This maps to the isAdministrator method for coarse-grained permissions.
    const PERM_Administrator = 1;
    
    protected static $table = 'permission';
    
    protected $_db_id;
    protected $_db_name;
    protected $_db_description;
    
    /*
     * Returns a list of all permissions tied to the given group
     *
     * @param Sihnon_Auth_IGroup $group Group to retrieve permissions for
     * @return array(Sihnon_Auth_IPermission)
     */
    public static function allForGroup(Sihnon_Auth_IGroup $group) {
        return self::allFor('group', $group->id, 'permissions_by_group');
    }
    
    /*
     * Returns a list of all permissions held by the given user
     *
     * Permissions are not tied directly to users but to groups. This method
     * really returns the set of all permissions tied to all groups the user
     * is a member of.
     *
     * @param Sihnon_Auth_IUser $user User to retrieve permissions for
     * @return array(Sihnon_Auth_IPermission)
     */
    public static function allForUser(Sihnon_Auth_IUser $user) {
        return self::allFor('user', $user->id, 'permissions_by_user');
    }
    
    /**
     * Returns the list of permissions not associated with the given group
     *
     * @param Sihnon_Auth_IGroup $group Group to retrieve unused permissions for
     * @return array(Sihnon_Auth_IPermission)
     */
    public static function unusedByGroup(Sihnon_Auth_IGroup $group) {
        return self::allFor('group', $group->id, 'permission_unmatchedgroups');
    }
    
    /**
     * Returns the list of permissions not associated with the given user
     *
     * @param Sihnon_Auth_IUser $user User to retrieve unused permissions for
     * @return array(Sihnon_Auth_IPermission)
     */
    public static function unusedByUser(Sihnon_Auth_IUser $user) {
        return self::allFor('user', $user->id, 'permission_unmatchedusers');
    }
    
    /*
     * IPermission methods
     */
    
    /**
     * Returns the unique identifier for the permission
     *
     * Depending on the implementation, this could be a numeric or a username
     *
     * @return mixed Unique identifier
     */
    public function id() {
        return $this->id;
    }
    
    /**
     * Returns the permission name
     * 
     * @return string Name of the permission
     */
    public function name() {
        return $this->name;
    }
    
    /**
     * Returns the description of the purpose for this permission
     * 
     * @return string Description of the purpose for this permission
     */
    public function description() {
        return $this->description;
    }
    
    

}