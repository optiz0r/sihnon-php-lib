<?php

class SihnonFramework_Auth_Plugin_Database_Permission extends Sihnon_DatabaseObject {
    
    /*
     * Built-in permissions
     */
    
    // The Administrator permission always exists, and is always offers the most functionality
    // This maps to the isAdministrator method for coarse-grained permissions.
    const PERM_Administrator = 1;
    
    protected static $table = 'permission';
    
    protected $_db_id;
    protected $_db_name;
    protected $_db_description;
    
    
}