<?php

class Sihnon_Main {
    
    protected static $instance;

    protected $config;
    protected $database;
    protected $log;
    protected $cache;
    
    protected $base_uri;

    protected function __construct() {
        $this->base_uri = dirname($_SERVER['SCRIPT_NAME']) . '/';
    }
    
    protected function init() {
        if (Sihnon_DatabaseSupport) {
            $this->database = new Sihnon_Database(Sihnon_DBConfig);
        }

        $this->config = new Sihnon_Config(Sihnon_ConfigPlugin, array(
        	'database' => $this->database,
        	'table' => Sihnon_ConfigTable, 
        	'filename' => Sihnon_ConfigFile)
        );
        
        $this->log    = new Sihnon_Log($this->config->get('logging.plugin'), array(
        	'database' => $this->database)
        );
                
        $this->cache  = new Sihnon_Cache($this->config);
    }

    /**
     * 
     * @return Sihnon_Main
     */
    public static function instance() {
        if (!self::$instance) {
            self::$instance = new Sihnon_Main();
            self::$instance->init();
        }

        return self::$instance;
    }

    /**
     * 
     * @return Sihnon_Config
     */
    public function config() {
        return $this->config;
    }

    /**
     * 
     * @return Sihnon_Database
     */
    public function database() {
        return $this->database;
    }

    /**
     * 
     * @return Sihnon_Log
     */
    public function log() {
        return $this->log;
    }

    /**
     * 
     * @return Sihnon_Cache
     */
    public function cache() {
        return $this->cache;
    }
    
    public function baseUri() {
        return $this->base_uri;
    }
    
    public function absoluteUrl($relative_url) {
        $secure = isset($_SERVER['secure']);
        $port = $_SERVER['SERVER_PORT'];
        return 'http' . ($secure ? 's' : '') . '://'
            . $_SERVER['HTTP_HOST'] . (($port == 80 || ($secure && $port == 443)) ? '' : ':' . $port)
            . '/' . $this->base_uri . $relative_url; 
    }

    public static function initialise() {
        spl_autoload_register(array('Sihnon_Main','autoload'));
    }
    
    public static function autoload($classname) {
        // Ensure the classname contains only valid class name characters
        if (!preg_match('/^[A-Z][a-zA-Z0-9_]*$/', $classname)) {
            throw new Exception('Illegal characters in classname'); // TODO Subclass this exception
        }

        // Ensure the class to load begins with our prefix
        if (!preg_match('/^Sihnon_/', $classname)) {
            return;
        }

        // Special case: All exceptions are stored in the same file
        if (preg_match('/^Sihnon_Exception/', $classname)) {
            require_once(Sihnon_Lib . 'Sihnon/Exceptions.class.php');
            return;
        }

        // Replace any underscores with directory separators
        $filename = Sihnon_Lib . preg_replace('/_/', '/', $classname);

        // Tack on the class file suffix
        $filename .= '.class.php';

        // If this file exists, load it
        if (file_exists($filename)) {
            require_once $filename;
        }
    }
    
    /**
     * Throws an exception if the requested name has not been defined.
     * 
     * @param string $name Name of the definition to check for the existence of
     * @throws Sihnon_Exception_MissingDefinition
     */
    public static function ensureDefined($name) {
        if (! defined($name)) {
            throw new Sihnon_Exception_MissingDefinition($name);
        }
    }
    
    public static function mkdir_recursive($directory, $permissions=0777) {
        $parts = explode('/', $directory);
        $path = '';
        for ($i=1,$l=count($parts); $i<=$l; $i++) {
            $iPath = $parts;
            $path = join('/', array_slice($iPath, 0, $i));
            if (empty($path)) continue;
            if (!file_exists($path)) {
                if (!mkdir($path)) return false;
                if (!chmod($path, $permissions)) return false;
            }
        }
        return true;
    }
    
    public static function issetelse($var, $default = null) {
        if (isset($var)) {
            return $var;
        }
        
        if (is_string($default) && preg_match('/^Sihnon_Exception/', $default) && class_exists($default) && is_subclass_of($default, Sihnon_Exception)) {
            throw new $default();
        }
        
        return $default;
    }

    public static function formatDuration($time) {
        if (is_null($time)) {
            return 'unknown';
        }

        $labels = array('seconds', 'minutes', 'hours', 'days', 'weeks', 'months', 'years');
        $limits = array(1, 60, 3600, 86400, 604800, 2592000, 31556926, PHP_INT_MAX);

        $working_time = $time;

        $result = "";
        $ptr = count($labels) - 1;

        while ($ptr >= 0 && $working_time < $limits[$ptr]) {
            --$ptr;
        }

        while ($ptr >= 0) {
            $unit_time = floor($working_time / $limits[$ptr]);
            $working_time -= $unit_time * $limits[$ptr];
            $result = $result . ' ' . $unit_time . ' ' . $labels[$ptr];
            --$ptr;
        }

        return $result;
    }
    
}

Sihnon_Main::initialise();

?>
