<?php

class SihnonFramework_Auth {
    
    protected $config;
    protected $session;
    
    protected $backend;
    protected $user;
    protected $authenticated;

    public function __construct(Sihnon_Config $config, Sihnon_Session $session) {
        $this->config = $config;
        $this->session = $session;
        $this->authenticated = false;
        
        $this->init($this->config->get('auth'));
    }
    
    protected function init($backend) {
        $this->backend = Sihnon_Auth_PluginFactory::create($this->config, $backend);
        $this->restoreSession();
    }
    
    public function checkFeatures($interfaces) {
        if ( ! is_array($interfaces)) {
            $interfaces = array($interface);
        }
        
        foreach ($interfaces as $interface) {
            if ( ! is_subclass_of($this->backend, $interface)) {
                throw new SihnonFramework_Exception_UnsupportedFeature($interface);
            }
        }
    }
    
    public function isAuthenticated() {
        return $this->authenticated;
    }
    
    public function authenticatedUser() {
        return $this->user;
    }
    
    public function saveSession() {
        if ($this->user) {
            $this->session->set('user.id', $this->user->username());
        }
    }
    
    public function clearSession() {
        $this->session->delete('user.id');
    }
    
    public function restoreSession() {
        if ($this->session->exists('user.id')) {
            $this->user = $this->backend->user($this->session->get('user.id'));
            $this->authenticated = true;
        }
    }
    
    public function register($username, $password) {
        $this->user = $this->addUser($username, $password);
        $this->authenticated = true;
    }
    
    /*
     * IPlugin methods
     */
    
    /**
     * Checks to see whether a given username exists within the backend
     *
     * @param string $username Unique login name for the user to be checked.
     * @return bool Returns true if the user is known to the backend, false otherwise.
     */
    public function userExists($username) {
        return $this->backend->userExists($username);
    }

    public function listUsers() {
        return $this->backend->listUsers();
    }
    
    public function authenticate($username, $password) {
        $this->user = $this->backend->authenticate($username, $password);
        $this->authenticated = true;
        
        $this->session->securityLeveLChanged();
        $this->saveSession();
    }
    
    public function user($username) {
        return $this->backend->user($username);
    }
    
    public function deauthenticate() {
        $this->user = null;
        $this->authenticated = false;
        
        $this->clearSession();
    }
    
    /*
     * IUpdateable methods
     */
    
    public function addUser($username, $password) {
        if ( ! is_subclass_of($this->backend, 'SihnonFramework_Auth_IUpdateable')) {
            throw new SihnonFramework_Exception_NotImplemented();
        }
        
        return $this->backend->addUser($username, $password);
    }
    
    public function removeUser() {
        if ( ! is_subclass_of($this->backend, 'SihnonFramework_Auth_IUpdateable')) {
            throw new SihnonFramework_Exception_NotImplemented();
        }
        
        $this->backend->removeUser($this->user);
        $this->user = null;
        $this->authenticated = false;
    }
    
    public function changePassword($new_password) {
        if ( ! is_subclass_of($this->backend, 'SihnonFramework_Auth_IUpdateable')) {
            throw new SihnonFramework_Exception_NotImplemented();
        }
        
        $this->backend->changePassword($this->user, $new_password);
    }
    
    /*
     * IPermissionable methods
     */
    
    public function isAdministrator() {
        if ( ! $this->user) {
            return false;
        }
        
        if ( ! is_subclass_of($this->backend, 'SihnonFramework_Auth_IPermissionable')) {
            throw new SihnonFramework_Exception_NotImplemented();
        }
        
        return $this->backend->isAdministrator($this->user);
    }
    
    /*
     * IFinelyPermissionable methods
     */
    
    public function hasPermission($permission) {
        if ( ! $this->user) {
            return false;
        }
        
        if ( ! is_subclass_of($this->backend, 'SihnonFramework_Auth_IFinelyPermissionable')) {
            throw new SihnonFramework_Exception_NotImplemented();
        }
        
        return $this->backend->hasPermission($this->user, $permission);
    }
    
    /**
     * Returns a list of all permissions defined in the backend
     *
     * @return array(Sihnon_Auth_IPermission)
     */
    public function listPermissions() {
        if ( ! is_subclass_of($this->backend, 'SihnonFramework_Auth_IFinelyPermissionable')) {
            throw new SihnonFramework_Exception_NotImplemented();
        }
        
        return $this->backend->listPermissions();
    }
    
    /**
     * Returns a Permission object with the given ID
     *
     * @param mixed $id Unique identifier for the permission to retrieve
     * @return Sihnon_Auth_IPermission
     */
    public function permission($id) {
        if ( ! is_subclass_of($this->backend, 'SihnonFramework_Auth_IFinelyPermissionable')) {
            throw new SihnonFramework_Exception_NotImplemented();
        }
        
        return $this->backend->permission($id);
    }
    
    /*
     * IDetails methods
     */
     
    public function emailAddress() {
        if ( ! $this->user) {
            return false;
        }
        
        if ( ! is_subclass_of($this->backend, 'SihnonFramework_Auth_IDetails')) {
            throw new SihnonFramework_Exception_NotImplemented();
        }
        
        return $this->backend->emailAddress($this->user);
    }
    
    public function setEmailAddress($emailAddress) {
        if ( ! $this->user) {
            return false;
        }
        
        if ( ! is_subclass_of($this->backend, 'SihnonFramework_Auth_IDetails')) {
            throw new SihnonFramework_Exception_NotImplemented();
        }
        
        return $this->backend->setEmailAddress($this->user, $emailAddress);
    }
    
    public function realName() {
        if ( ! $this->user) {
            return false;
        }
        
        if ( ! is_subclass_of($this->backend, 'SihnonFramework_Auth_IDetails')) {
            throw new SihnonFramework_Exception_NotImplemented();
        }
        
        return $this->backend->realName($this->user);
    }
    
    public function setRealName($realName) {
        if ( ! $this->user) {
            return false;
        }
        
        if ( ! is_subclass_of($this->backend, 'SihnonFramework_Auth_IDetails')) {
            throw new SihnonFramework_Exception_NotImplemented();
        }
        
        return $this->backend->setRealName($this->user, $realName);    
    }
    
    /*
     * ICustomAttributes methods
     */
    
    public function availableCustomAttributes() {
        if ( ! $this->user) {
            return false;
        }
        
        if ( ! is_subclass_of($this->backend, 'SihnonFramework_Auth_ICustomAttribute')) {
            throw new SihnonFramework_Exception_NotImplemented();
        }
        
        return $this->backend->availableCustomAttributes($this->user);
    }
    
    public function allCustomAttributes() {
        if ( ! $this->user) {
            return false;
        }
        
        if ( ! is_subclass_of($this->backend, 'SihnonFramework_Auth_ICustomAttribute')) {
            throw new SihnonFramework_Exception_NotImplemented();
        }
        
        return $this->backend->allCustomAttributes($this->user);
    }
    
    public function customAttribute($name) {
        if ( ! $this->user) {
            return false;
        }
        
        if ( ! is_subclass_of($this->backend, 'SihnonFramework_Auth_ICustomAttribute')) {
            throw new SihnonFramework_Exception_NotImplemented();
        }
        
        return $this->backend->customAttribute($this->user, $name);
    }
    
    public function setCustomAttribute($name, $value) {
        if ( ! $this->user) {
            return false;
        }
        
        if ( ! is_subclass_of($this->backend, 'SihnonFramework_Auth_ICustomAttribute')) {
            throw new SihnonFramework_Exception_NotImplemented();
        }
        
        return $this->backend->setCustomAttribute($this->user, $name, $value);
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
        if ( ! is_subclass_of($this->backend, 'SihnonFramework_Auth_IGroupable')) {
            throw new SihnonFramework_Exception_NotImplemented();
        }
        
        return $this->backend->groupExists($groupname);
    }
    
    /**
     * Returns a list of all users known to the backend.
     *
     * @return array(Sihnon_Auth_IUser)
     */
    public function listGroups() {
        if ( ! is_subclass_of($this->backend, 'SihnonFramework_Auth_IGroupable')) {
            throw new SihnonFramework_Exception_NotImplemented();
        }
        
        return $this->backend->listGroups();
    }
    
    /**
     * Retrieves a group
     *
     * @param string $groupname Unique name for the group
     * @param Sihnon_Auth_IGroup Group object
     */
    public function group($groupname)  {
        if ( ! is_subclass_of($this->backend, 'SihnonFramework_Auth_IGroupable')) {
            throw new SihnonFramework_Exception_NotImplemented();
        }
        
        return $this->backend->group($groupname);
    }
    
    /*
     * IUpdateableGroups
     */
     
        /**
     * Creaates a new entry for this group in the backend
     * 
     * @param string $groupname Unique name for the group
     * @param string $description Text description of the pupose for the group
     */
    public function addGroup($groupname, $description) {
        if ( ! is_subclass_of($this->backend, 'SihnonFramework_Auth_IUpdateableGroups')) {
            throw new SihnonFramework_Exception_NotImplemented();
        }
        
        return $this->backend->addGroup($groupname, $description);
    }
    
    /**
     * Removes the entry for this group from the backend
     *
     * @param Sihnon_Auth_IGroup $group Group to be removed.
     */
    public function removeGroup(Sihnon_Auth_IGroup $group) {
        if ( ! is_subclass_of($this->backend, 'SihnonFramework_Auth_IUpdateableGroups')) {
            throw new SihnonFramework_Exception_NotImplemented();
        }
        
        return $this->backend->removeGroup($groupname);
    }
        
    /**
     * Add a user to this group in the backend
     *
     * @param Sihnon_Auth_IGroup $group Group to be modified
     * @param Sihnon_Auth_IUser $user User to be added to the group
     */
    public function addUserToGroup(Sihnon_Auth_IGroup $group, Sihnon_Auth_IUser $user) {
        if ( ! is_subclass_of($this->backend, 'SihnonFramework_Auth_IUpdateableGroups')) {
            throw new SihnonFramework_Exception_NotImplemented();
        }
        
        return $this->backend->addUserToGroup($group, $user);
    }
    
    /**
     * Removes a user from this group in the backend
     *
     * @param Sihnon_Auth_IGroup $group Group to be modified
     * @param Sihnon_Auth_IUser $user User to be removed from the group
     */
    public function removeUserFromGroup(Sihnon_Auth_IGroup $group, Sihnon_Auth_IUser $user) {
        if ( ! is_subclass_of($this->backend, 'SihnonFramework_Auth_IUpdateableGroups')) {
            throw new SihnonFramework_Exception_NotImplemented();
        }
        
        return $this->backend->removeUserFromGroup($group, $user);
    }
}

?>