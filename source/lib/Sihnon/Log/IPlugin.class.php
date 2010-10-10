<?php

interface Sihnon_Log_IPlugin extends Sihnon_IPlugin {
    
    /**
     * Returns a new instance of the Plugin class
     * 
     * @param array(string=>mixed) $options Configuration options for the Plugin object
     */
    public static function create($options);
    

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
    public function log($level, $ctime, $pid, $hostname, $progname, $line, $message);
}

?>