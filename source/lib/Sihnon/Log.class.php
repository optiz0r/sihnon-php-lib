<?php

class Sihnon_Log {
    
    const LEVEL_DEBUG   = 'DEBUG';
    const LEVEL_INFO    = 'INFO';
    const LEVEL_WARNING = 'WARNING';
    const LEVEL_ERROR   = 'ERROR';

    private static $hostname = '';
    
    private $backend;
    private $progname;
    
    public function __construct($backend, $options = array(), $progname = '') {
        $this->progname = $progname;
        
        $this->backend = Sihnon_Log_PluginFactory::create($backend, $options);
        $this->log(self::LEVEL_INFO, "Logging started");
    }
    
    public function __destruct() {
        $this->log(self::LEVEL_INFO, "Logging shutdown");        
    }

    public function log($level, $message) {
        $this->backend->log($level, time(), 0, self::$hostname, $this->progname, 0, $message);
    }

    public function debug($message) {
        return $this->log(self::LEVEL_DEBUG, $message);
    }

    public function info($message) {
        return $this->log(self::LEVEL_INFO, $message);
    }

    public function warning($message) {
        return $this->log(self::LEVEL_WARNING, $message);
    }

    public function error($message) {
        return $this->log(self::LEVEL_ERROR, $message);
    }

    public static function initialise() {
        self::$hostname = trim(`hostname`);
    }

}

Sihnon_Log::initialise();

?>
