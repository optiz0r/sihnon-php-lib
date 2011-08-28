<?php

class SihnonFramework_Config {
    
    /**
     * Boolean value type
     * @var bool
     */
    const TYPE_BOOL        = 'bool';
    
    /**
     * Integer value type
     * @var int
     */
    const TYPE_INT         = 'int';
    
    /**
     * Float value type
     * @var float
     */
    const TYPE_FLOAT       = 'float';
    
    /**
     * String value type
     * @var string
     */
    const TYPE_STRING      = 'string';
    
    /**
     * String List value type; list of newline separated strings
     * @var array(string)
     */
    const TYPE_STRING_LIST = 'array(string)';
    
    /**
     * Backend to be used for this Config object
     * @var Sihnon_Config_IPlugin
     */
    private $backend;

    /**
     * Associative array of settings loaded from the database
     * @var array(string=>array(string=>string))
     */
    private $settings       = array();

    /**
     * Constructs a new instance of the Config class
     *
     * @param string $backend Backend to use for storing and retrieving configuration items
     * @param mixed $options Parameters to configure the Config backend
     * @return Sihnon_Config
     */
    public function __construct($backend, $options) {
        $this->backend = Sihnon_Config_PluginFactory::create($backend, $options);
        $this->settings = $this->backend->preload();
    }
    
    protected static function pack($type, $value) {
        switch ($type) {
            case static::TYPE_STRING_LIST: {
                return join("\n", $value);
            } break;
            
            default: {
                return $value;
            }
        }
    }
    
    protected static function unpack($type, $value) {
        switch ($type) {
            case self::TYPE_STRING_LIST:
                return array_map('trim', explode("\n", $value));
                
            default:
               return $value;
        }
    }

    /**
     * Identifies whether the named setting exists
     * 
     * @param string $key Name of the setting
     * @return bool
     */
    public function exists($key) {
        return isset($this->settings[$key]);
    }

    /**
     * Fetches the value of the named setting
     * 
     * @param string $key Name of the setting
     */
    public function get($key) {
        if (!isset($this->settings[$key])) {
            throw new Sihnon_Exception_UnknownSetting($key);
        }

        return static::unpack($this->settings[$key]['type'], $this->settings[$key]['value']);
    }
    
    public function type($key) {
        if (!isset($this->settings[$key])) {
            throw new Sihnon_Exception_UnknownSetting($key);
        }
        
        return $this->settings[$key]['type'];
    }
    
    public function enumerateAll() {
        return array_map(function($r) {return $r['name'];}, $this->settings);
    }
    
    public function set($key, $value) {
        if ( ! ($this->backend instanceof Sihnon_Config_IUpdateable)) {
            throw new Sihnon_Exception_ReadOnlyConfigBackend();
        }
        if (!isset($this->settings[$key])) {
            throw new Sihnon_Exception_UnknownSetting($key);
        }

        $packed_value = static::pack($this->settings[$key]['type'], $value);
        
        // Change the setting value for this run
        $this->settings[$key]['value'] = $packed_value;
        
        // Persist the change into the backend
        return $this->backend->set($key, $packed_value);
    }
    
    public function add($key, $type, $value) {
        if ( ! ($this->backend instanceof Sihnon_Config_IUpdateable)) {
            throw new Sihnon_Exception_ReadOnlyConfigBackend();
        }
        if (isset($this->settings[$key])) {
            throw new Sihnon_Exception_SettingExists($key);
        }
        if ( ! Sihnon_Main::isClassConstantValue(get_called_class(), 'TYPE_', $type)) {
            throw new Sihnon_Exception_UnknownSettingType($type);
        } 
        
        $packed_value = static::pack($type, $value);
        
        // Add the setting for this run
        $this->settings[$key] = array(
            'type'  => $type,
            'value' => $packed_value,
        );
        
        // Persist the setting into the backend
        return $this->backend->add($key, $type, $packed_value);
    }
    
    public function remove($key) {
        if ( ! ($this->backend instanceof Sihnon_Config_IUpdateable)) {
            throw new Sihnon_Exception_ReadOnlyConfigBackend();
        }
        if (!isset($this->settings[$key])) {
            throw new Sihnon_Exception_UnknownSetting($key);
        }

        // Remove the setting for this run
        unset($this->settings[$key]);
        
        // Persist the change into the backend
        return $this->backend->remove($key);
    }

};

?>
