<?php

class SihnonFramework_Log_Plugin_Syslog extends SihnonFramework_Log_PluginBase implements SihnonFramework_Log_IPlugin {

    /**
     * Name of this plugin
     * @var string
     */
    const PLUGIN_NAME = "Syslog";
    
    protected static $level_map = array(
        SihnonFramework_Log::LEVEL_DEBUG   => LOG_DEBUG,
        SihnonFramework_Log::LEVEL_INFO    => LOG_INFO,
        SihnonFramework_Log::LEVEL_WARNING => LOG_WARNING,
        SihnonFramework_Log::LEVEL_ERROR   => LOG_ERR,
    );

    protected $format;

    protected function __construct($instance, $format) {
        parent::__construct($instance);

        $this->format   = $format;
        openlog(SihnonFramework_LogEntry::localProgname(), LOG_PID|LOG_ODELAY, LOG_LOCAL0);
    }

    public function __destruct() {
         closelog();
    }

    public static function create(SihnonFramework_Config $config, $instance) {
        $format   = $config->get("logging.".static::PLUGIN_NAME.".{$instance}.format");

        return new self($instance, $format);
    }

    /**
     * Records a new entry in the storage backend used by this logging plugin
     *
     * @param SihnonFramework_LogEntry $entry Log Entry object containing the information to be recorded
     */
    public function log(SihnonFramework_LogEntry $entry) {
        $fields = $entry->fields();
        $values = $entry->values();
        $formatted_entry = str_replace(array_map(function($name) { return "%{$name}%"; }, $fields ), $values, $this->format);

        syslog(static::$level_map[$entry->level()], $formatted_entry);
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

};

?>