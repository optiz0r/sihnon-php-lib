<?php 

class SihnonFramework_Session {
    
    protected $config;
    
    protected $state;
    protected $dirty;
    
    public function __construct(Sihnon_Config $config) {
        $this->config = $config;
        $this->dirty = false;
        
        $this->init();
    }
    
    public function __destruct() {
        $this->teardown();
    }
    
    protected function init() {
        session_start();
        $this->state = $_SESSION;
    }
    
    protected function teardown() {
        if ($this->dirty) {
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
        session_regenerate_id(true);
    }
    
}

?>