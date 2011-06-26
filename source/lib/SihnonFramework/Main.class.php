<?php

class SihnonFramework_Main {
    
    protected static $instance;

    protected static $autoload_classes = array(
        array(
            'base' => 'SihnonFramework',
            'base_dir_prefix' => SihnonFramework_Lib,
            'subclass' => 'Sihnon',
            'subclass_dir_prefix' => Sihnon_Lib,
        ),
    );
    
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
        
        $this->log    = new Sihnon_Log($this->config);
                
        $this->cache  = new Sihnon_Cache($this->config);
    }

    /**
     * 
     * @return SihnonFramework_Main
     */
    public static function instance() {
        if (!self::$instance) {
            $called_class = get_called_class();
            self::$instance = new $called_class();
            self::$instance->init();
        }

        return self::$instance;
    }

    /**
     * 
     * @return SihnonFramework_Config
     */
    public function config() {
        return $this->config;
    }

    /**
     * 
     * @return SihnonFramework_Database
     */
    public function database() {
        return $this->database;
    }

    /**
     * 
     * @return SihnonFramework_Log
     */
    public function log() {
        return $this->log;
    }

    /**
     * 
     * @return SihnonFramework_Cache
     */
    public function cache() {
        return $this->cache;
    }
    
    public function baseUri() {
        return $this->base_uri;
    }
    
    public function absoluteUrl($relative_url) {
        $secure = isset($_SERVER['SECURE']) || (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on');
        $port = $_SERVER['SERVER_PORT'];
        return 'http' . ($secure ? 's' : '') . '://'
            . $_SERVER['HTTP_HOST'] . (($port == 80 || ($secure && $port == 443)) ? '' : ':' . $port)
            . '/' . $this->base_uri . $relative_url; 
    }

    public static function initialise() {
        // Provide a means to load framework classes autonomously
        spl_autoload_register(array('SihnonFramework_Main','autoload'));

        // Handle error messages using custom error handler
        set_error_handler(array(get_called_class(), 'errorHandler'));
    }
    
    public static function errorHandler($errno, $errstr, $errfile = '', $errline = 0, $errcontext = array()) {
        $severity = '';
        switch ($errno) {
            case E_NOTICE:
            case E_USER_NOTICE:
                $severity = SihnonFramework_Log::LEVEL_INFO;
                break;
            case E_WARNING:
            case E_USER_WARNING:
                $severity = SihnonFramework_Log::LEVEL_WARNING;
                break;
            case E_ERROR:
            case E_USER_ERROR:
                $severity = SihnonFramework_Log::LEVEL_ERROR;
                break;
            default:
                $severity = SihnonFramework_Log::LEVEL_INFO;
                break;
        }
        
        SihnonFramework_LogEntry::logInternal(static::instance()->log(), $severity, $errfile, $errline, $errstr, SihnonFramework_Log::CATEGORY_DEFAULT);
        
        // If dev mode is enabled, fail here to enable the normal PHP error handling
        return ! Sihnon_Dev;
    }
    
    public static function autoload($classname) {
        // Ensure the classname contains only valid class name characters
        if (!preg_match('/^[A-Z][a-zA-Z0-9_]*$/', $classname)) {
            throw new Exception('Illegal characters in classname');
        }
        
        foreach (self::$autoload_classes as $class) {
    
            // Ensure the class to load begins with our prefix
            if (preg_match("/^{$class['base']}_/", $classname)) {
                // Special case: all related exceptions are grouped into a single file
                if (preg_match("/^({$class['base']}_(?:.*?_)?)Exception/", $classname, $matches)) {
                    $exceptions_filename = /*$class['base_dir_prefix'] .*/ preg_replace('/_/', '/', $matches[1]) . 'Exceptions.class.php';
                    if (stream_resolve_include_path($exceptions_filename)) {
                        require_once($exceptions_filename);
                    }
                    return;
                }
                    
                // Replace any underscores with directory separators
                $filename = /*$class['base_dir_prefix'] .*/ preg_replace('/_/', '/', $classname) . '.class.php';
        
                // If this file exists, load it
                if (stream_resolve_include_path($filename)) {
                    require_once($filename);
                    return;
                }
                
                // Try again without the .class suffix
                $filename = /*$class['base_dir_prefix'] .*/ preg_replace('/_/', '/', $classname) . '.php';
        
                // If this file exists, load it
                if (stream_resolve_include_path($filename)) {
                    require_once($filename);
                    return;
                }
            } elseif ($class['subclass'] && preg_match("/^{$class['subclass']}_/", $classname)) {
                // Sihnon_ classes subclass the SihnonFramework_ classes.
                // If a subclass doesn't exist, create it on the fly
                
                // Special case: all related exceptions are grouped into a single file
                if (preg_match("/^({$class['subclass']}_(?:.*?_)?)Exception/", $classname, $matches)) {
                    $exceptions_filename = /*$class['subclass_dir_prefix'] .*/ preg_replace('/_/', '/', $matches[1]) . 'Exceptions.class.php'; 
                    if (stream_resolve_include_path($exceptions_filename)) {
                        require_once($exceptions_filename);
                    } else {
                        // Create this class to extend the Framework parent
                        $parent_classname = preg_replace("/^{$class['subclass']}_/", "{$class['base']}_", $classname);
                        class_alias($parent_classname, $classname);
                        return;
                    }
                }
                
                // Replace any underscores with directory separators
                $filename = /*$class['subclass_dir_prefix'] .*/ preg_replace('/_/', '/', $classname) . '.class.php';
        
                // If this file exists, load it
                if (stream_resolve_include_path($filename)) {
                    require_once($filename);
                    return;
                } else {
                    // Create this class to extend the Framework parent
                    $parent_classname = preg_replace("/^{$class['subclass']}_/", "{$class['base']}_", $classname);
                    class_alias($parent_classname, $classname);
                    return; 
                }
            }
        }
    }
    
    /**
     * Adds additional class names to the autoloader
     * 
     * The base name is the prefix of all classes in the base tree. The subclass name is the prefix of all classes from
     * another tree in which the base classes can be extended. For example, the SihnonFramework and Sihnon class trees.
     * 
     * For the base and subclasses, the dir prefix is the top-level directory which contains all the class files.
     * 
     * If there are no subclasses, the latter two parameters can be left as null, or unspecified.
     * 
     * @param unknown_type $base
     * @param unknown_type $base_dir_prefix
     * @param unknown_type $subclass
     * @param unknown_type $subclass_dir_prefix
     */
    public static function registerAutoloadClasses($base, $base_dir_prefix, $subclass = null, $subclass_dir_prefix = null) {
        $canonical_base_dir_prefix = static::makeAbsolutePath($base_dir_prefix);
        if ( ! $canonical_base_dir_prefix) {
            throw new SihnonFramework_Exception_FileNotFound($base_dir_prefix);
        }
        
        // The paths must end with a trailing slash
        if ($canonical_base_dir_prefix && $canonical_base_dir_prefix[strlen($canonical_base_dir_prefix) - 1] != DIRECTORY_SEPARATOR) {
            $canonical_base_dir_prefix .= DIRECTORY_SEPARATOR;
        }
        
        $include_path_prefix = $canonical_base_dir_prefix . PATH_SEPARATOR;
        
        $canonical_subclass_dir_prefix = null;
        if ($subclass_dir_prefix) {
            $canonical_subclass_dir_prefix = static::makeAbsolutePath($subclass_dir_prefix);
            if ( ! $canonical_subclass_dir_prefix) {
                throw new SihnonFramework_Exception_FileNotFound($subclass_dir_prefix);
            }
            
            if ($canonical_subclass_dir_prefix[strlen($canonical_subclass_dir_prefix) - 1] != DIRECTORY_SEPARATOR) {
                $canonical_subclass_dir_prefix .= DIRECTORY_SEPARATOR;
            }
            
            $include_path_prefix = $canonical_subclass_dir_prefix . PATH_SEPARATOR . $include_path_prefix;
        }
        
        set_include_path($include_path_prefix . get_include_path());
        
        array_unshift(self::$autoload_classes, array(
            'base' => $base,
            'base_dir_prefix' => $canonical_base_dir_prefix,
            'subclass' => $subclass,
            'subclass_dir_prefix' => $canonical_subclass_dir_prefix,
        ));
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
    
    /**
     * Returns the canonical form of the given path, made absolute if relative
     * 
     * @param string $relative_path
     * @return string
     */
    public static function makeAbsolutePath($relative_path) {
        if (preg_match('#^/#', $relative_path)) {
            // This path is already absolute, just canonicalise it
            return realpath($relative_path);
        }
        
        $absolute_path = getcwd() . DIRECTORY_SEPARATOR . $relative_path;
        return realpath($absolute_path);
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
    
    public static function rmdir_recursive($dir) { 
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir") self::rmdir_recursive($dir."/".$object); else unlink($dir."/".$object); 
                }
            }
            reset($objects); 
            rmdir($dir);
        }
        
        return true;
    }
    
    public static function issetelse($var, $default = null) {
        if (isset($var)) {
            return $var;
        }
        
        if (is_string($default) && preg_match('/^Sihnon(Framework)?_Exception/', $default) && class_exists($default) && is_subclass_of($default, 'SihnonFramework_Exception')) {
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
    
    public static function formatFilesize($bytes) {
        if (is_null($bytes)) {
            return 'unknown';
        }
        
        $labels = array('B', 'KB', 'MB', 'GB', 'TB');
        $limits = array(1, 1024, 1024*1024, 1024*1024*1024, 1024*1024*1024*1024);
        
        $size = $bytes;
        $ptr = count($labels) - 1;
        while ($ptr >= 0 && $bytes < $limits[$ptr]) {
            --$ptr;
        }
        
        $size = round($bytes / $limits[$ptr], 2) . ' ' . $labels[$ptr];
        
        return $size;
    }
    
}

SihnonFramework_Main::initialise();

?>
