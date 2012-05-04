<?php

class SihnonFramework_Auth_Plugin_Database
    extends    Sihnon_PluginBase 
    implements Sihnon_Auth_IPlugin, 
               Sihnon_Auth_IUpdateable, 
               Sihnon_Auth_IFinelyPermissionable, 
               Sihnon_Auth_IDetails,
               Sihnon_Auth_IGroupable,
               Sihnon_Auth_IUpdateableGroups {

    protected $config;
    protected $database;
    
    protected function __construct($config) {
        $this->config = $config;
        $this->database = SihnonFramework_Main::instance()->database();
    }
    
    /*
     * IPlugin methods 
     */
    
    public static function create(SihnonFramework_Config $config) {
        return new self($config);
    }
    
    public function userExists($username) {
        return Sihnon_Auth_Plugin_Database_User::exists($username);
    }
    
    public function listUsers() {
        return Sihnon_Auth_Plugin_Database_User::all();
    }
    
    public function authenticate($username, $password) {
        try {
            $user = Sihnon_Auth_Plugin_Database_User::from('username', $username);
        } catch (Sihnon_Exception_ResultCountMismatch $e) {
            throw new Sihnon_Exception_UnknownUser();
        }
        
        if ( ! $user->checkPassword($password)) {
            throw new Sihnon_Exception_IncorrectPassword();
        }
        
        $user->setLastLoginTime(time());
        $user->save();
        
        return $user;
    }
    
    public function user($username) {
        return Sihnon_Auth_Plugin_Database_User::from('username', $username);
    }
    
    /*
     * IUpdateable methods
     */
    
    public function addUser($username, $password) {
        return Sihnon_Auth_Plugin_Database_User::add($username, $password);
    }
    
    public function removeUser(Sihnon_Auth_IUser $user) {
        $user->delete();
    }
    
    public function changePassword(Sihnon_Auth_IUser $user, $new_password) {
        $user->changePassword($new_password);
    }
    
    /*
     * IPermissionable methods
     */
    
    public function isAdministrator(Sihnon_Auth_IUser $user) {
        // As this class supports fine-grained permissions, map the isAdministrator function to the Superadmin privilege
        // to fallback for badly written applications
        return $user->hasPermission(Sihnon_Auth_Plugin_Database_Permission::PERM_Administrator);
    }
    
    /*
     * IFinelyPermissionable methods
     */

    /**
     * Checks if the user holds the given permission
     *
     * Used to provide RBAC to restrict access to certain features of an application.
     * Permissions may be defined as class constants or as extensible items stored in the backend.
     *
     * @param Sihnon_Auth_IUser $user User to be checked
     * @param int $permission Identified for the permission to be checked.
     * @return bool Returns true if the user holds the permission, false otherwise.
     */
    public function hasPermission(Sihnon_Auth_IUser $user, $permission) {
        return $user->hasPermission($permission);
    }
    
    /**
     * Returns a list of all permissions defined in the backend
     *
     * @return array(Sihnon_Auth_IPermission)
     */
    public function listPermissions() {
        return Sihnon_Auth_Plugin_Database_Permission::all(null, null, null, 'name', Sihnon_Auth_Plugin_Database_Permission::ORDER_ASC);
    }
    
        /**
     * Returns a Permission object with the given ID
     *
     * @param mixed $id Unique identifier for the permission to retrieve
     * @return Sihnon_Auth_IPermission
     */
    public function permission($id) {
        return Sihnon_Auth_Plugin_Database_Permission::fromId($id);
    }
    
    /*
     * IDetails methods
     */
     
    /**
     * Gets the user's email address
     *
     * @param $user Sihnon_Auth_IUser User
     * @return string Email address
     */
    public function emailAddress(Sihnon_Auth_IUser $user) {
        return $user->emailAddress();
    }
    
    /**
     * Sets the user's email address
     * 
     * Requires the backend to implement IUpdateable
     * 
     * @param $user Sihnon_Auth_IUser User
     * @param $emailAddress string New email address
     */
    public function setEmailAddress(Sihnon_Auth_IUser $user, $emailAddress) {
        return $user->setEmailAddress($emailAddress);
    }
    
    /**
     * Gets the user's real name
     * 
     * @param $user Sihnon_Auth_IUser User
     * @return string Real name
     */
    public function realName(Sihnon_Auth_IUser $user) {
        return $user->realName();
    }
    
    /**
     * Sets the user's real name
     *
     * @param $user Sihnon_Auth_IUser User
     * @param $realName string New real name
     */
    public function setRealName(Sihnon_Auth_IUser $user, $realName) {
        return $user->setRealName($realName);
    }
    
    /**
     * Gets the user's last login time
     *
     * @param $user Sihnon_Auth_IUser User
     * @return int Unix timestamp of the last login time
     */
    public function lastLoginTime(Sihnon_Auth_IUser $user) {
        return $user->lastLoginTime();
    }
    
    /**
     * Sets the user's last login time
     *
     * @param $user Sihnon_Auth_IUser User
     8 @param $time int Last login time
     */
    public function setLastLoginTime(Sihnon_Auth_IUser $user, $time) {
        return $user->setLastLoginTime($time);
    }

    /**
     * Gets the user's last password change time
     *
     * @param $user Sihnon_Auth_IUser User
     * @return int Unix timestamp of the last password change
     */
    public function lastPasswordChangeTime(Sihnon_Auth_IUser $user) {
        return $user->lastPasswordChangeTime();
    }
    
    /**
     * Sets the user's last password change time
     *
     * @param $user Sihnon_Auth_IUser User
     8 @param $time int Last password change time
     */
    public function setLastPasswordChangeTime(Sihnon_Auth_IUser $user, $time) {
        return $user->setLastPasswordChangeTime($time);
    }

    /*
     * IGroupable methods
     */
    
    /**
     * Checks to see whether a given username exists within the backend
     *
     * @param string $groupname Unique login name for the group to be checked.
     * @return bool Returns true if the group is known to the backend, false otherwise.
     */
    public function groupExists($groupname) {
        return Sihnon_Auth_Plugin_Database_Group::exists('name', $groupname);
    }
    
    /**
     * Returns a list of all users known to the backend.
     *
     * @return array(Sihnon_Auth_IUser)
     */
    public function listGroups() {
        return Sihnon_Auth_Plugin_Database_Group::all();
    }
    
    /**
     * Retrieves a group
     *
     * @param string $groupname Unique name for the group
     * @param Sihnon_Auth_IGroup Group object
     */
    public function group($groupname) {
        return Sihnon_Auth_Plugin_Database_Group::from('name', $groupname);
    }
    
    /*
     * IUpdateableGroups methods
     */
     
        /**
     * Creaates a new entry for this group in the backend
     * 
     * @param string $groupname Unique name for the group
     * @param string $description Text description of the pupose for the group
     */
    public function addGroup($groupname, $description) {
        return Sihnon_Auth_Plugin_Database_Group::add($groupname, $description);
    }
    
    /**
     * Checks whether the given group may be removed by the user
     *
     * Some groups may be vital for system operation, such as an administrators group
     * 
     * @param Sihnon_Auth_IGroup $group Group to be tested for removability
     * @return bool Returns True if the group may be removed, false otherwise.
     */
    public function removable(Sihnon_Auth_IGroup $group) {
        return $group->removable();
    }
    
    /**
     * Removes the entry for this group from the backend
     *
     * @param Sihnon_Auth_IGroup $group Group to be removed.
     */
    public function removeGroup(Sihnon_Auth_IGroup $group) {
        $group->delete();
    }
        
    /**
     * Add a user to this group in the backend
     *
     * @param Sihnon_Auth_IGroup $group Group to be modified
     * @param Sihnon_Auth_IUser $user User to be added to the group
     */
    public function addUserToGroup(Sihnon_Auth_IGroup $group, Sihnon_Auth_IUser $user) {
        return $group->addUser($user);
    }
    
    /**
     * Removes a user from this group in the backend
     *
     * @param Sihnon_Auth_IGroup $group Group to be modified
     * @param Sihnon_Auth_IUser $user User to be removed from the group
     */
    public function removeUserFromGroup(Sihnon_Auth_IGroup $group, Sihnon_Auth_IUser $user) {
        return $group->removeUser($user);
    }
}

?>