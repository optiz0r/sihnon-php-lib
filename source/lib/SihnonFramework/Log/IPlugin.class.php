<?php

interface SihnonFramework_Log_IPlugin extends Sihnon_IPlugin {
    
    /**
     * Creates a new instance of the Logging Plugin using the specified instance name, reading in the necessary config
     * 
     * @param SihnonFramework_Config $config Config option to retrieve plugin configuration
     * @param string $instance Name of the instance to create
     * @return SihnonFramework_Log_IPlugin
     */
    public static function create(SihnonFramework_Config $config, $instance);
    
    /**
     * Records a new entry in the storage backend used by this logging plugin
     * 
     * @param SihnonFramework_LogEntry $entry LogEntry object describing information to be recorded
     */
    public function log(SihnonFramework_LogEntry $entry);
    
    /**
     * Returns an array of recent log messages written using the plugin
     * 
     * @param string $entry_class Class name of the LogEntry class to use to reinstanciate the contents of the log entry
     * @param string $order_field Field name to order log entries by before selecting
     * @param string $order_direction Order in which to sort log entries before selecting. Use the SihnonFramework_Log::ORDER_* constants.
     * @param int $limit Maximum number of log entries to retrieve
     * @return array(SihnonFramework_LogEntry)
     */
    public function recent($entry_class, $order_field, $order_direction, $limit = 30);

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
    public function recentByField($entry_class, $field, $value, $order_field, $order_direction, $limit = 30);
    
}

?>