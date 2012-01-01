<?php

class SihnonFramework_Auth_Plugin_Database_GroupPermission extends Sihnon_DatabaseObject {
    
    protected static $table = 'grouppermission';
    
    protected $_db_id;
    protected $_db_group;
    protected $_db_permission;
    protected $_db_added;
    
    
}