<?php

class SihnonFramework_Log {
    
    const LEVEL_DEBUG   = 'debug';
    const LEVEL_INFO    = 'info';
    const LEVEL_WARNING = 'warning';
    const LEVEL_ERROR   = 'error';
    
    const CATEGORY_DEFAULT = 'default';
    
    const ORDER_ASC     = 'ASC';
    const ORDER_DESC    = 'DESC';

    protected $plugins = array();
    
    public function __construct(SihnonFramework_Config $config) {
        $log = SihnonFramework_Main::instance()->log();
        
        // Get a list of the logging plugins to be used
        $plugins = $config->get('logging.plugins');
        
        foreach ($plugins as $plugin) {
            // Get a list of all the instances of this plugin to be used
            $instances = $config->get("logging.{$plugin}");
            foreach ($instances as $instance) {
                try {
                    $this->plugins[$plugin][] = array(
                        'name' => $instance,
                        'backend' => Sihnon_Log_PluginFactory::create($config, $plugin, $instance),
                        'severity' => $config->get("logging.{$plugin}.{$instance}.severity"),
                        'category' => $config->get("logging.{$plugin}.{$instance}.category"),
                    );
                } catch(SihnonFramework_Exception_LogException $e) {
                    SihnonFramework_LogEntry::warning($log, $e->getMessage());
                }
            }
        }
        
        SihnonFramework_LogEntry::info($this, "Logging started");
    }
    
    public function log(SihnonFramework_LogEntry $entry) {
        foreach ($this->plugins as $plugin => $instances) {
            foreach ($instances as $instance) {
                if (in_array($entry->level(), $instance['severity'])) {
                    if (in_array($entry->category(), $instance['category'])) {
                        $instance['backend']->log($entry);
                    }
                }
            }
        }
    }
    
    public function recentEntries($entry_class, $instance_name, $order_field, $order_direction = self::ORDER_DESC, $limit = 10) {
        // Look for the right instance
        foreach ($this->plugins as $plugin => $instances) {
            foreach ($instances as $instance) {
                if ($instance['name'] == $instance_name) {
                    return $instance['backend']->recent($entry_class, $order_field, $order_direction, $limit);
                }
            }
        }
        
        return array();
    }
    
    public function recentEntriesByField($entry_class, $instance_name, $field, $value, $type, $order_field, $order_direction = SihnonFramework_Log::ORDER_DESC, $limit = 30) {
        // Look for the right instance
        foreach ($this->plugins as $plugin => $instances) {
            foreach ($instances as $instance) {
                if ($instance['name'] == $instance_name) {
                    return $instance['backend']->recentByField($entry_class, $field, $value, $type, $order_field, $order_direction, $limit);
                }
            }
        }
        
        return array();
    }

}

?>
