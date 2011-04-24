<?php

class SihnonFramework_Log_Plugin_Database extends SihnonFramework_Log_PluginBase implements Sihnon_Log_IPlugin {
    
    /**
     * Name of this plugin
     * @var string
     */
    const PLUGIN_NAME = "Database";
    
    private $database;
    private $table;
    
    protected function __construct($instance, $database, $table) {
        parent::__construct($instance);
        
        $this->database = $database;
        $this->table    = $table;
    }
    
    public static function create(SihnonFramework_Config $config, $instance) {
        $database = SihnonFramework_Main::instance()->database();
        $table = $config->get("logging.".self::PLUGIN_NAME.".{$instance}.table", 'SihnonFramework_Exception_MissingParameter');
        
        return new self($instance, $database, $table);
    }
    
    /**
     * Records a new entry in the storage backend used by this logging plugin
     * 
     * @param SihnonFramework_LogEntry $entry Log Entry object containing the information to be recorded
     */
    public function log(SihnonFramework_LogEntry $entry) {
        $fields = $entry->fields();
        $types  = $entry->types();
        $values = $entry->values();
        
        $bindings = array();
        for ($i = 0, $l = count($fields); $i < $l; ++$i) {
            $type = '';
            switch ($types[$i]) {
                case 'int':
                    $type = PDO::PARAM_INT;
                    break;
                case 'bool':
                    $type = PDO::PARAM_BOOL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
                    break;
            }
            
            $bindings[] = array(
                'name'  => $fields[$i],
                'value' => $values[$i],
                'type'  => $type, 
            );
        }
        
        $field_list = join(', ', $fields);
        $bindings_list = join(', ', array_map(function($value) { return ":{$value}"; }, $fields));
        
        $this->database->insert("INSERT INTO {$this->table} ({$field_list}) VALUES({$bindings_list})", $bindings);
    }
    
    /**
     * Returns an array of recent log messages written using the plugin
     * 
     * @param string $entry_class Class name of the LogEntry class to use to reinstanciate the contents of the log entry
     * @param int $limit Maximum number of log entries to retrieve
     * @return array(SihnonFramework_LogEntry)
     * 
     * @todo customise order field instead of using hardcoded ctime
     */
    public function recent($entry_class, $order_field, $order_direction = SihnonFramework_Log::ORDER_DESC, $limit = 30) {
        $entries = array();
        
        $records = $this->database->selectList("SELECT * FROM `{$this->table}` ORDER BY `{$order_field}` {$order_direction} LIMIT {$limit}", array());
        foreach ($records as $record) {
            $entries[] = $entry_class::fromArray($record);
        }
        
        return $entries;
    }
    
    public function recentByField($entry_class, $field, $value, $type, $order_field, $order_direction = SihnonFramework_Log::ORDER_DESC, $limit = 30) {
        $entries = array();
        
        $pdo_type = '';
        switch ($type) {
            case 'int':
                $pdo_type = PDO::PARAM_INT;
                break;
            case 'bool':
                $pdo_type = PDO::PARAM_BOOL;
                break;
            default:
                $pdo_type = PDO::PARAM_STR;
                break;
        }
        
        
        $records = $this->database->selectList("SELECT * FROM `{$this->table}` WHERE `{$field}`=:{$field} ORDER BY `{$order_field}` {$order_direction} LIMIT {$limit}",
            array(
                array('name' => $field, 'value' => $value, 'type' => $pdo_type),
            )
        );
        foreach ($records as $record) {
            $entries[] = $entry_class::fromArray($record);
        }
        
        return $entries;
    }
    
    
}

?>