<?php 

class SihnonFramework_Session {
    
    protected $config;
    
    protected $enabled;
    protected $state;
    protected $dirty;
    
    public function __construct(Sihnon_Config $config) {
        $this->config = $config;
        $this->enabled = false;
        $this->dirty = false;
        
        if ($this->config->exists('sessions') && $this->config->get('sessions')) {
            $this->enabled = true;
        }
        
        $this->init();
    }
    
    public function __destruct() {
        $this->teardown();
    }
    
    protected function init() {
        if ($this->enabled) {
            session_start();
            $this->state = $_SESSION;
            
            // Override the session parameters if configured
            $params = session_get_cookie_params();
            $lifetime = $this->config->exists('sessions.lifetime')  ? $this->config->get('sessions.lifetime')  : $params['lifetime'];
            $path     = $this->config->exists('sessions.path')      ? $this->config->get('sessions.path')      : $params['path'];
            $domain   = $this->config->exists('sessions.domain')    ? $this->config->get('sessions.domain')    : $params['domain'];
            $secure   = $this->config->exists('sessions.secure')    ? $this->config->get('sessions.secure')    : $params['secure'];
            $httponly = $this->config->exists('sessions.http-only') ? $this->config->get('sessions.http-only') : $params['httponly'];
            session_set_cookie_params($lifetime, $path, $domain, $secure, $httponly);
        }
    }
    
    protected function teardown() {
        if ($this->enabled && $this->dirty) {
            $_SESSION = $this->state;
            session_write_close();
        }
    }
    
    public function set($name, $value) {
        $this->state[$name] = $value;
        $this->dirty = true;
    }
    
    public function get($name, $default = null) {
        if ( ! $this->exists($name)) {
            return $default;
        }
        
        return $this->state[$name];
    }
    
    public function exists($name) {
        return isset($this->state[$name]);
    }
    
    public function delete($name) {
        unset($this->state[$name]);
        $this->dirty = true;
    }
    
    public function securityLeveLChanged() {
        if ($this->enabled) {
            session_regenerate_id(true);
        }
    }
    
}

?>