<?php

class SihnonFramework_Auth_Plugin_Database_Group
    extends    Sihnon_DatabaseObject
    implements Sihnon_Auth_IGroup {
    
    protected static $table = 'group';
    
    protected $_db_id;
    protected $_db_name;
    protected $_db_description;
    
    protected $users = null;
    
    /*
     * IGroup methods
     */
    
    /**
     * Returns the unique identifier for the group
     *
     * Depending on the implementation, this could be a numeric or a name
     *
     * @return mixed Unique identifier
     */
    public function id() {
        return $this->id;
    }
    
    /**
     * Returns the group name
     * 
     * @return string Group name
     */
    public function name() {
        return $this->name;
    }
    
    /**
     * Returns the group description
     *
     * @return string Group description
     */
    public function description() {
        return $this->description;
    }
    
    /**
     * Lists the users that are members of this group
     *
     * @return array(Sihnon_Auth_IUser)
     */
    public function users($ignore_cache = false) {
        if ($this->users === null) {
            $this->users = Sihnon_Auth_Plugin_Database_User::allForGroup($this);
        }
        
        return $this->users;
    }
    
    /**
     * Checks if the given user is in this froup in the backend
     *
     * @param Sihnon_Auth_IUser $user User to be checked
     * @return bool Returns true if the user is in this group, false otherwise.
     */
    public function inGroup(Sihnon_Auth_IUser $user) {
        $users = $this->users();
        
        foreach ($users as $user_) {
            if ($user_->id() == $user->id()) {
                return true;
            }
        }
        
        return false;
    }
    
    /*
     * IUpdateable methods
     */
     
        /**
     * Creaates a new entry for this group in the backend
     * 
     * @param string $groupname Unique name for the group
     * @param string $description Text description of the purpose for this group
     */
    public static function add($groupname, $description) {
        $group = new self();
        $group->name = $groupname;
        $group->description = $description;
        $group->create();
        
        return $group;
    }
    
    /**
     * Add a user to this group in the backend
     *
     * @param Sihnon_Auth_IUser $user User to be added to the group
     */
    public function addUser(Sihnon_Auth_IUser $user) {
        $new_ug = Sihnon_Auth_Plugin_Database_UserGroup::newFor($user, $this);
    }
    
    /**
     * Removes a user from this group in the backend
     *
     * @param Sihnon_Auth_IUser $user User to be removed from the group
     */
    public function removeUser(Sihnon_Auth_IUser $user) {
        throw new SihnonFramework_Exception_NotImplemented();
    }
    
}