<?php

class SihnonFramework_Auth_Plugin_Database_GroupPermission extends Sihnon_DatabaseObject {
    
    protected static $table = 'grouppermission';
    
    protected $_db_id;
    protected $_db_group;
    protected $_db_permission;
    protected $_db_added;
    
    /**
     * Creates a new group-permission mapping for the given Group and Permission objects
     *
     * @param Sihnon_Auth_Plugin_Database_Group $group Group to be modified
     * @param Sihnon_Auth_Plugin_Database_Permission $permission Permission to be added
     * @return Sihnon_Auth_Plugin_Database_GroupPermission
     */
    public static function newFor(Sihnon_Auth_Plugin_Database_Group $group, Sihnon_Auth_Plugin_Database_Permission $permission) {
        $new_gp = new self();
        $new_gp->group = $group->id;
        $new_gp->permission = $permission->id;
        $new_gp->added = time();
        
        $new_gp->create();
        
        return $new_gp;
    }
    
    /**
     * Returns the group-permission mapping for the given Group and Permission objects
     * 
     * @param Sihnon_Auth_Plugin_Database_Group $group Group to be modified
     * @param Sihnon_Auth_Plugin_Database_Permission $permission Permission to be added
     * @return Sihnon_Auth_Plugin_Database_GroupPermission
     */
    public static function fromGroupPermission(Sihnon_Auth_Plugin_Database_Group $group, Sihnon_Auth_Plugin_Database_Permission $permission) {
        return self::from(array('group', 'permission'), array($group->id, $permission->id));
    }
    
    /**
     * Returns the list of permissions tied to the given group
     *
     * @param Sihnon_Auth_Plugin_Database_Group $group Group to retrive the permissions for
     * @return array(Sihnon_Auth_Plugin_Database_Permission)
     */
    public static function allForGroup(Sihnon_Auth_Plugin_Database_Group $group) {
        return self::allFor('group', $group->id);
    }
    
    
}