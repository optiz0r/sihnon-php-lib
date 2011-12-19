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
        $this->backend = Sihnon_Auth_PluginFactory::create($config, $backend);
        $this->restoreSession();
    }
    
    public function isAuthenticated() {
        return $this->authenticated;
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
        return $this->backend->addUser($username, $password);
    }
    
    public function removeUser() {
        $this->backend->removeUser($this->user);
        $this->user = null;
        $this->authenticated = false;
    }
    
    /*
     * IPermissionable methods
     */
    
    public function isAdministrator() {
        if ( ! $this->user) {
            return false;
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
        
        return $this->backend->hasPermission($this->user, $permission);
    }
    
}

?>