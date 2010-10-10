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

        switch ($this->settings[$key]['type']) {
            case self::TYPE_STRING_LIST:
                return array_map('trim', explode("\n", $this->settings[$key]['value']));
                
            default:
               return $this->settings[$key]['value'];
        }
    }

};

?>
