<?php

class SihnonFramework_Log_PluginBase extends SihnonFramework_PluginBase {
    
    protected $instance;
    
    protected function __construct($instance) {
        $this->instance = $instance;
    }
    
    public function instance() {
        return $this->instance;
    }
    
}