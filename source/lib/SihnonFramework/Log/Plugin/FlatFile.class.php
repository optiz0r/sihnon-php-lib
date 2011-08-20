<?php

class SihnonFramework_Log_Plugin_FlatFile extends SihnonFramework_Log_PluginBase implements Sihnon_Log_IPlugin {
    
    /**
     * Name of this plugin
     * @var string
     */
    const PLUGIN_NAME = "FlatFile";
    
    protected $filename;
    protected $format;
    protected $fp;
    
    protected function __construct($instance, $filename, $format) {
        parent::__construct($instance);
        
        $this->filename = $filename;
        $this->format   = $format;
        
        // Verify the given log file can be written to
        $this->verifyLogfile();
        
        $this->fp = fopen($this->filename, 'a');
    }
    
    public function __destruct() {
        fclose($this->fp);
    }
    
    public static function create(SihnonFramework_Config $config, $instance) {
        $filename = $config->get("logging.".self::PLUGIN_NAME.".{$instance}.filename");
        $format   = $config->get("logging.".self::PLUGIN_NAME.".{$instance}.format");
        
        return new self($instance, $filename, $format);
    }
    
    protected function verifyLogfile() {
        // Check that the file exists, or can be created
        $file_ok = false;
        
        if (preg_match('#php://(stderr|stdout)#', $this->filename)) {
            $file_ok = true;
        } else if (file_exists($this->filename) && is_writable($this->filename)) {
            $file_ok = true;
        } else {
            $directory = dirname($this->filename);
            if (file_exists($directory) && is_writeable($directory)) {
                $file_ok = true;
            }
        }
        
        if (!$file_ok) {
            throw new SihnonFramework_Exception_LogFileNotWriteable($this->filename);
        }
    }
    
    /**
     * Records a new entry in the storage backend used by this logging plugin
     * 
     * @param SihnonFramework_LogEntry $entry Log Entry object containing the information to be recorded
     */
    public function log(SihnonFramework_LogEntry $entry) {
        $fields = $entry->fields();
        $values = $entry->values();
        $fields_map = array_combine($fields, $values);
        
        // Make some alterations for ease of display
        $fields_map['timestamp'] = date('d/m/y H:i:s', $fields_map['ctime']);
        
        // split the map back out again now the modifications have been made
        $fields = array_keys($fields_map);
        $values = array_values($fields_map);
        
        $formatted_entry = str_replace(array_map(function($name) { return "%{$name}%"; }, $fields), $values, $this->format) . "\n";
                
        fwrite($this->fp, $formatted_entry, strlen($formatted_entry));
    }
    
    /**
     * Returns an array of recent log messages written using the plugin
     * 
     * @param string $entry_class Class name of the LogEntry class to use to reinstanciate the contents of the log entry
     * @param string $order_field Field name to order log entries by before selecting
     * @param string $order_direction Order in which to sort log entries before selecting. Use the SihnonFramework_Log::ORDER_* constants.
     * @param int $limit Maximum number of log entries to retrieve
     * @return array(SihnonFramework_LogEntry)
     */
    public function recent($entry_class, $order_field, $order_direction, $limit = 30) {
        throw new SihnonFramework_Exception_NotImplemented();
    }
    
	/**
     * Returns an array of recent log messages matching a particular field/value written using the plugin
     * 
     * @param string $entry_class Class name of the LogEntry class to use to reinstanciate the contents of the log entry
     * @param string $field Field to match log entries on
     * @param mixed $value Value to match log entries on
     * @param string $order_field Field name to order log entries by before selecting
     * @param string $order_direction Order in which to sort log entries before selecting. Use the SihnonFramework_Log::ORDER_* constants.
     * @param int $limit Maximum number of log entries to retrieve
     * @return array(SihnonFramework_LogEntry)
     */
    public function recentByField($entry_class, $field, $value, $order_field, $order_direction, $limit = 30) {
        throw new SihnonFramework_Exception_NotImplemented();
    }
    
    
}

?>