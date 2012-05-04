<?php

class SihnonFramework_Auth_Plugin_Database_Group
    extends    Sihnon_DatabaseObject
    implements Sihnon_Auth_IGroup,
               Sihnon_Auth_Group_IFinelyPermissionable,
               Sihnon_Auth_Group_IUpdateableFinePermissions {
    
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
     * Checks whether this group may be removed by the user
     *
     * Some groups may be vital for system operation, such as an administrators group
     * 
     * @return bool Returns True if the group may be removed, false otherwise.
     */
    public function removable() {
        return $this->id != 1;
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
    
    /**
     * Returns the list of permissions associated with this group
     *
     * @return array(Sihnon_Auth_IPermission)
     */
    public function permissions() {
        return Sihnon_Auth_Plugin_Database_Permission::allForGroup($this);
    }

    /**
     * Returns the list of available permissions not already associated with this group
     *
     * @return array(Sihnon_Auth_IPermission)
     */
    public function unusedPermissions() {
        return Sihnon_Auth_Plugin_Database_Permission::unusedByGroup($this);
    }

    /**
     * Adds a permission to this group
     *
     * @param Sihnon_Auth_IPermission
     */
    public function addPermission(Sihnon_Auth_IPermission $permission) {
        $new_gp = Sihnon_Auth_Plugin_Database_GroupPermission::newFor($this, $permission);
    }
    
    /**
     * Removes a permission from this group
     * 
     * @param Sihnon_Auth_IPermission
     */
    public function removePermission(Sihnon_Auth_IPermission $permission) {
        $gp = Sihnon_Auth_Plugin_Database_GroupPermission::fromGroupPermission($this, $permission);
        $gp->delete();
    }
    
}