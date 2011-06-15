<?php

class SihnonFramework_RequestParser {

    private $request_string;
    private $page = array();
    private $vars = array();
    
    private $template_dir;
    private $template_code_dir;

    public function __construct($request_string, $template_dir = './source/templates', $template_code_dir = './source/pages') {
        $this->request_string    = $request_string;
        $this->template_dir      = $template_dir;
        $this->template_code_dir = $template_code_dir;

        $this->parse();
    }

    public function parse() {
        if (!$this->request_string) {
        	$this->page = array('home');
            return;
        }

        $components = explode('/', $this->request_string);
        if (!$components) {
            return;
        }

        // Read through the components list looking for elements matching known directories and files
        // to determine which page this request is for
        $base_dir = $this->template_dir;
        while (true) {
            if ($components && ! $components[0]) {
                // Skip over any empty components before we find a page
                array_shift($components);
            }
            
            if ($components && is_dir($base_dir . '/' . $components[0])) {
                $base_dir .= '/' . $components[0];
                array_push($this->page, array_shift($components));
            } elseif ($components && is_file($base_dir . '/' . $components[0] . '.tpl')) {
                // We have found a valid page, so break the loop here,
                // leaving the remaining components as key/value pairs
                array_push($this->page, array_shift($components));
                break;
            } else {
                // See if we've already seen a component and assumed it referred to a dir when a file of the same name exists
                if ($this->page && is_file($base_dir . '.tpl')) {
                    break;
                } elseif ( ! $components && is_file($base_dir . '/index.tpl')) {
                    // The last component in the path was a valid directory, and a directory index exists
                    array_push($this->page, 'index');
                    break; 
                } else {
                    // No valid page was found, so display an error page
                    $this->page = array('404');
                    return;
                }
            }
        }

        // The subsequent tokens are parameters for this page in key value pairs
        while ($components) {
            // If the url ended with a trailing slash, the last element will be null
            $last_element = $components[count($components) - 1];
            if ($last_element == "") {
                array_pop($components);
            }
            
            $this->vars[array_shift($components)] = $components ? array_shift($components) : null;
        }
    }

    public function page() {
        return join('/', $this->page);
    }

    public function exists($key) {
        return array_key_exists($key, $this->vars);
    }

    public function get($key, $default = null) {
        if (isset($this->vars[$key])) {
            return $this->vars[$key];
        }
        
        if (is_string($default) && preg_match('/^[a-zA-Z_]+_Exception/', $default) && class_exists($default) && is_subclass_of($default, 'SihnonFramework_Exception')) {
            throw new $default();
        }

        return $default;
    }
    
    public function getRemainder($after, $hasValue) {
        $include = false;
        $result = array();
        foreach ($this->vars as $var => $value) {
            if ($var == $after) {
                $include = true;
                if ($hasValue) {
                    continue;
                } else {
                    $result[] = $value;
                }
            } elseif ($include) { 
                $result[] = $var;
                $result[] = $value;
            } else {
                continue;
            }
        }
        
        if ($result && ( ! $result[count($result) - 1])) {
            array_pop($result);
        }
        
        return $result;
    }
    
    public function request_string() {
        return $this->request_string;
    }
    
    public function template_dir() {
        return $this->template_dir;
    }
    
    public function template_code_dir() {
        return $this->template_code_dir;
    }

};

?>
