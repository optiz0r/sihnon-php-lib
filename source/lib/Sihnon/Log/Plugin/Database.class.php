<?php

class Sihnon_Log_Plugin_Database extends Sihnon_PluginBase implements Sihnon_Log_IPlugin {
    
    /**
     * Name of this plugin
     * @var string
     */
    const PLUGIN_NAME = "Database";
    
    private $database;
    private $table;
    
    protected function __construct($options) {
        $this->database = $options['database'];
        $this->table    = Sihnon_Main::instance()->config()->get('logging.database.table');
    }
    
    public static function create($options) {
        return new self($options);
    }
    
    /**
     * Records a new entry in the storage backend used by this logging plugin
     * 
     * @param string $level Severity of the log entry
     * @param int $ctime Time the log entry was created
     * @param int $pid ID of the process that created the log entry
     * @param string $hostname Hostname of the system that created the log entry
     * @param string $progname Name of the application that created the log entry
     * @param int $line Line number of the code that created the log entry
     * @param string $message Message to be logged
     */
    public function log($level, $ctime, $pid, $hostname, $progname, $line, $message) {
        $this->database->insert("INSERT INTO {$this->table} (level,ctime,pid,hostname,progname,line,message) VALUES(:level, :ctime, :pid, :hostname, :progname, :line, :message)",
            array(
                array('name' => 'level', 'value' => $level, 'type' => PDO::PARAM_STR),
                array('name' => 'ctime', 'value' => $ctime, 'type' => PDO::PARAM_INT),
                array('name' => 'pid', 'value' => $pid, 'type' => PDO::PARAM_INT),
                array('name' => 'hostname', 'value' => $hostname, 'type' => PDO::PARAM_STR),
                array('name' => 'progname', 'value' => $progname, 'type' => PDO::PARAM_STR),
                array('name' => 'line', 'value' => $line, 'type' => PDO::PARAM_INT),
                array('name' => 'message', 'value' => $message, 'type' => PDO::PARAM_STR)
            )
        );
    }    
}

?>