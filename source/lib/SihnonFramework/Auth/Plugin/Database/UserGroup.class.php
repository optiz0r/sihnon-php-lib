<?php

class SihnonFramework_Auth_Plugin_Database_UserGroup extends Sihnon_DatabaseObject {
    
    protected static $table = 'usergroup';
    
    protected $_db_id;
    protected $_db_user;
    protected $_db_group;
    protected $_db_added;
    
    /**
     * Creates a new user-group mapping for the given User and Group objects
     *
     * @param Sihnon_Auth_Plugin_Database_User $user User
     * @param Sihnon_Auth_Plugin_Database_Group $group Group
     * @return Sihnon_Auth_Plugin_Database_UserGroup
     */
    public static function newFor(Sihnon_Auth_Plugin_Database_User $user, Sihnon_Auth_Plugin_Database_Group $group) {
        $new_ug = new self();
        $new_ug->user = $user->id;
        $new_ug->group = $group->id;
        $new_ug->added = time();
        
        $new_ug->create();
        
        return $new_ug;
    }
    
    /**
     * Returns all user-group mappings for the given User object
     * 
     * @param Sihnon_Auth_Plugin_Database_User $user User
     * @return array(Sihnon_Auth_Plugin_Database_UserGroup)
     */
    public static function allForUser(Sihnon_Auth_Plugin_Database_User $user) {
        return self::allFor('user', $user->id);
    }
    
    /**
     * Returns all user-group mappings for the given Group object
     * 
     * @param Sihnon_Auth_Plugin_Database_Group $group Group
     * @return array(Sihnon_Auth_Plugin_Database_UserGroup)
     */
    public static function allForGroup(Sihnon_Auth_Plugin_Database_Group $group) {
        return self::allFor('group', $group->id);
    }
    
    /**
     * Returns a user-group mapping given the User and Group objects
     *
     * @param Sihnon_Auth_Plugin_Database_User $user User
     * @param Sihnon_Auth_Plugin_Database_Group $group Group
     * @return Sihnon_Auth_Plugin_Database_UserGroup
    */
    public static function fromUserGroup(Sihnon_Auth_Plugin_Database_User $user, Sihnon_Auth_Plugin_Database_Group $group) {
        return static::from(array('user', 'group'), array($user->id, $group->id));
    }
    
    /**
     * Returns the User object associated with this user-group mapping
     *
     * @return Sihnon_Auth_Plugin_Database_User
     */
    public function user() {
        return Sihnon_Auth_Plugin_Database_User::fromId($this->user);
    }
    
    /**
     * Returns the Group object associated with this user-group mapping
     *
     * @return Sihnon_Auth_Plugin_Database_Group
     */
    public function group() {
        return Sihnon_Auth_Plugin_Database_Group::fromId($this->group);
    }
    
    
}