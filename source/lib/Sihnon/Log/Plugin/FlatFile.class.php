<?php

class Sihnon_Log_Plugin_FlatFile extends Sihnon_PluginBase implements Sihnon_Log_IPlugin {
    
    /**
     * Name of this plugin
     * @var string
     */
    const PLUGIN_NAME = "FlatFile";
    
    protected $filename;
    protected $fp;
    
    protected function __construct($options) {
        $this->filename = Sihnon_Main::instance()->config()->get('logging.flatfile.filename');
        $this->fp = fopen($this->filename, 'a');
    }
    
    public function __destruct() {
        fclose($this->fp);
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
        $log_entry = implode(',', array($level, $ctime, $pid, $hostname, $progname, $line, $message)) . "\n";
        fwrite($this->fp, $log_entry, strlen($log_entry));
    }
}

?>