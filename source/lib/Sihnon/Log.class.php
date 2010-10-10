<?php

class Sihnon_Log {
    
    const LEVEL_DEBUG   = 'DEBUG';
    const LEVEL_INFO    = 'INFO';
    const LEVEL_WARNING = 'WARNING';
    const LEVEL_ERROR   = 'ERROR';

    private static $hostname = '';
    
    private $database;
    private $config;
    private $table;

    public function __construct(Sihnon_Database $database, Sihnon_Config $config, $table) {
        $this->database = $database;
        $this->config = $config;
        $this->table = $table;

    }

    public function log($severity, $message) {
        $this->database->insert("INSERT INTO {$this->table} (level,ctime,pid,hostname,progname,line,message) VALUES(:level, :ctime, :pid, :hostname, :progname, :line, :message)",
            array(
                array('name' => 'level', 'value' => $severity, 'type' => PDO::PARAM_STR),
                array('name' => 'ctime', 'value' => time(), 'type' => PDO::PARAM_INT),
                array('name' => 'pid', 'value' => 0, 'type' => PDO::PARAM_INT),
                array('name' => 'hostname', 'value' => self::$hostname, 'type' => PDO::PARAM_STR),
                array('name' => 'progname', 'value' => 'webui', 'type' => PDO::PARAM_STR),
                array('name' => 'line', 'value' => 0, 'type' => PDO::PARAM_INT),
                array('name' => 'message', 'value' => $message, 'type' => PDO::PARAM_STR)
            )
        );        
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
