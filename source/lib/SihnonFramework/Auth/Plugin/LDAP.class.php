<?php

class SihnonFramework_Auth_Plugin_LDAP
    extends    Sihnon_PluginBase
    implements Sihnon_Auth_IPlugin,
               Sihnon_Auth_IPermissionable,
               Sihnon_Auth_IFinelyPermissionable {

    protected $config;
    protected $ldap;
    
    protected function __construct($config) {
        $this->config = $config;
        
        $this->initInstance();
    }
    
    protected function initInstance() {
        $this->ldap = ldap_connect($this->config->get('auth.LDAP.servers'), $this->config->get('auth.LDAP.port', 389));
        if ( ! $this->ldap) {
            throw new SihnonFramework_Exception_LDAPConnectionFailed();
        }
        
        if ($this->config->get('auth.LDAP.start-tls', false)) {
            if ( ! ldap_start_tls($this->ldap)) {
                throw new Sihnon_Exception_LDAPSecureConnectionFailed();
            }
        }
        
        $search_dn = $this->config->get('auth.LDAP.search-dn', null);
        $search_password = $this->config->get('auth.LDAP.search-password', null);
        if ( ! ldap_bind($this->ldap, $search_dn, $search_password)) {
            var_dump("Failed to bind as", $search_dn, $search_password);
            throw new SihnonFramework_Exception_LDAPBindFailed();
        }
        
        Sihnon_Auth_Plugin_LDAP_User::init(
            $this->ldap, 
            $this->config->get('auth.LDAP.user-base-dn'),
            $this->config->get('auth.LDAP.group-base-dn'),
            $this->config->get('auth.LDAP.recursive-search', false)
        );
    }
    
    /*
     * IPlugin methods
    */
    
    public static function create(SihnonFramework_Config $config) {
        return new self($config);
    }
    
    public function userExists($username) {
        return Sihnon_Auth_Plugin_LDAP_User::exists($username);
    }
    
    public function listUsers() {
        return Sihnon_Auth_Plugin_LDAP_User::all();
    }
    
    public function authenticate($username, $password) {
        $user = Sihnon_Auth_Plugin_LDAP_User::load($username);
    
        if ( ! $user->checkPassword($password)) {
            throw new Sihnon_Exception_IncorrectPassword();
        }
    
        return $user;
    }
    
    public function user($username) {
        return Sihnon_Auth_Plugin_LDAP_User::load($username);
    }
    
    /*
     * IPermissionable methods
    */
    
    public function isAdministrator(Sihnon_Auth_IUser $user) {
        return $user->isAdministrator();
    }
    
    public function hasPermission(Sihnon_Auth_IUser $user, $permission) {
        return $user->hasPermission($permission);
    }
    public static function ldapEscape($input_str, $for_dn = false) {
        // Taken from Douglas Davis at http://php.sihnon.net/manual/en/function.ldap-search.php#90158
        // see:
        // RFC2254
        // http://msdn.microsoft.com/en-us/library/ms675768(VS.85).aspx
        // http://www-03.ibm.com/systems/i/software/ldap/underdn.html
        
        $str = $input_str;
        if ( ! is_array($str)) {
            $str = array($str);
        }
    
        if ($for_dn) {
            $metaChars = array(',', '=', '+', '<', '>', ';', '\\', '"', '#');
        }
        else {
            $metaChars = array('*', '(', ')', '\\', chr(0));
        }
    
        $quotedMetaChars = array();
        foreach ($metaChars as $key => $value) {
            $quotedMetaChars[$key] = '\\'.str_pad(dechex(ord($value)), 2, '0');
        }
        $str = str_replace($metaChars,$quotedMetaChars,$str); //replace them
        
        return is_array($input_str) ? $str : $str[0];
    }

}

?>