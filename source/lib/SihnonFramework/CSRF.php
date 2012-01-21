<?php

class SihnonFramework_CSRF {

    protected $session;
    
    public function __construct() {
        $main = SihnonFramework_Main::instance();
        $this->session = $main->session();
        
        $this->prepareSession();
    }
    
    public function prepareSession() {
        if ( ! $this->session->exists('csrf')) {
            $this->session->set('csrf', uniqid(), true);
        }
    }
    
    public function generate() {
        $key = uniqid();
        $check = $this->generateCheck($key);
        
        return "{$key}:{$check}";
    }
    
    protected function generateCheck($key) {
        return sha1($key . $this->session->get('csrf'));
    }
    
    public function validate($token) {
        list($key, $check) = explode(':', $token);    
        if ($check != $this->generateCheck($key)) {
            throw new SihnonFramework_Exception_CSRFVerificationFailure();
        }
        
        return true;
    }
    
    public function validatePost() {
        $token = SihnonFramework_Main::issetelse($_POST['csrftoken'], 'SihnonFramework_Exception_CSRFVerificationFailure');
        return $this->validate($token);
    }
    
}

?>