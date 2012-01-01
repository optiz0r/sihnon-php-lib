<?php

class SihnonFramework_Daemon {
    
    protected $config;
    
    protected $lock_file;
    protected $lock;
    protected $locked;
    
    public function __construct(SihnonFramework_Config $config) {
        $this->config = $config;
        $this->lock_file = $config->get('daemon.lock-file');
        $this->lock = null;
        $this->locked = false;
        
        $this->init();
    }
    
    public function __destruct() {
        $this->teardown();
    }
    
    protected function init() {
        $this->lock = fopen($this->lock_file, 'w');
        $wouldBlock = false;
        
        $result = flock($this->lock, LOCK_EX|LOCK_NB, $wouldBlock);
        if ($wouldBlock) {
            // Another instance is already running
            throw new SihnonFramework_Exception_AlreadyRunning();
        } else if ( ! $result) {
            throw new SihnonFramework_Exception_LockingFailed();
        }
        
        
    }
    
    protected function teardown() {
        if ( ! $this->locked) {
            return;
        }
        
        flock($this->lock, LOCK_UN);
        fclose($this->lock);
        unlink($this->lock_file);
    }
}

?>