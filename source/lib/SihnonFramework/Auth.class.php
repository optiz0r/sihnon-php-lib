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
            $this->user = $this->backend->authenticateSession($this->session->get('user.id'));
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
    
    public function listUsers() {
        return $this->backend->listUsers();
    }
    
    public function authenticate($username, $password) {
        $this->user = $this->backend->authenticate($username, $password);
        $this->authenticated = true;
        
        $this->session->securityLeveLChanged();
        $this->saveSession();
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
    
}

?>