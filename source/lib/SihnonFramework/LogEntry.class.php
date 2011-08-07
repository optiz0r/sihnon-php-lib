<?php

class SihnonFramework_LogEntry {
    
    protected static $localHostname;
    protected static $localProgname;
    
    protected static $types = array(
            'level'		=> 'string',
            'category'	=> 'string',
            'ctime'     => 'int',
            'hostname'  => 'string',
            'progname'  => 'string',
            'pid'		=> 'int',
            'file'	    => 'string',
            'line'		=> 'int',
            'message'	=> 'string',
    );

    protected $level;
    protected $category;
    protected $ctime;
    protected $hostname;
    protected $progname;
    protected $pid;
    protected $file;
    protected $line;
    protected $message;
    
    public static function initialise() {
        static::$localHostname = gethostname();
        static::$localProgname = '';
    }
    
    protected function __construct($level, $category, $ctime, $hostname, $progname, $pid, $file, $line, $message) {
        $this->level    = $level;
        $this->category = $category;
        $this->ctime    = $ctime;
        $this->hostname = $hostname;
        $this->progname = $progname;
        $this->pid      = $pid;
        $this->file     = $file;
        $this->line     = $line;
        $this->message  = $message;
    }
    
    public static function fromArray($row) {
        return new self(
            $row['level'],
            $row['category'],
            $row['ctime'],
            $row['hostname'],
            $row['progname'],
            $row['pid'],
            $row['file'],
            $row['line'],
            $row['message']
        );
    }
    
    public static function localProgname() {
        return static::$localProgname;
    }
    
    public static function setLocalProgname($progname) {
    	static::$localProgname = $progname;
    }
    
    public function fields() {
        return array_keys(static::$types);
    }
    
    public function types() {
        return array_values(static::$types);
    }

    public function values() {
        return array(
            $this->level,
            $this->category,
            $this->ctime,
            $this->hostname,
            $this->progname,
            $this->pid,
            $this->file,
            $this->line,
            $this->message,
        );
    }
    
    public function level() {
        return $this->level;
    }
    
    public function category() {
        return $this->category;
    }

    public function ctime() {
        return $this->ctime;
    }

    public function hostname() {
        return $this->hostname;
    }

    public function progname() {
        return $this->progname;
    }

    public function pid() {
        return $this->pid;
    }
    
    public function file() {
        return $this->file;
    }

    public function line() {
        return $this->line;
    }

    public function message() {
        return $this->message;
    }
    
    protected static function log($logger, $severity, $message, $category = SihnonFramework_Log::CATEGORY_DEFAULT) {
        $backtrace = debug_backtrace(false);
        $entry = new static($severity, $category, time(), static::$localHostname, static::$localProgname, getmypid(), $backtrace[1]['file'], $backtrace[1]['line'], $message);
        
        $logger->log($entry);
    }
    
    public static function logInternal($logger, $severity, $file, $line, $message, $category = SihnonFramework_Log::CATEGORY_DEFAULT) {
        $entry = new static($severity, $category, time(), static::$localHostname, static::$localProgname, getmypid(), $file, $line, $message);
        $logger->log($entry);
    }
    
    public static function debug($logger, $message, $category = SihnonFramework_Log::CATEGORY_DEFAULT) {
        static::log($logger, SihnonFramework_Log::LEVEL_DEBUG, $message, $category);
    }

    public static function info($logger, $message, $category = SihnonFramework_Log::CATEGORY_DEFAULT) {
        static::log($logger, SihnonFramework_Log::LEVEL_INFO, $message, $category);
    }

    public static function warning($logger, $message, $category = SihnonFramework_Log::CATEGORY_DEFAULT) {
        static::log($logger, SihnonFramework_Log::LEVEL_WARNING, $message, $category);
    }

    public static function error($logger, $message, $category = SihnonFramework_Log::CATEGORY_DEFAULT) {
        static::log($logger, SihnonFramework_Log::LEVEL_ERROR, $message, $category);
    }
    
    public static function recentEntries($log, $instance, $order_field, $order_direction = SihnonFramework_Log::ORDER_DESC, $limit = 30) {
        return $log->recentEntries(get_called_class(), $instance, $order_field, $order_direction, $limit);
    }
    
    public static function recentEntriesByField($log, $instance, $field, $value, $order_field, $order_direction = SihnonFramework_Log::ORDER_DESC, $limit = 30) {
        return $log->recentEntriesByField(get_called_class(), $instance, $field, $value, static::$types[$field], $order_field, $order_direction, $limit);
    }

};

SihnonFramework_LogEntry::initialise();

?>
