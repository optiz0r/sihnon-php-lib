<?php

/**
 * Provides methods to retrieve information about a user object stored in an authentication backend
 */
interface SihnonFramework_Auth_IPermission {

    /**
     * Returns the unique identifier for the permission
     *
     * Depending on the implementation, this could be a numeric or a username
     *
     * @return mixed Unique identifier
     */
    public function id();
    
    /**
     * Returns the permission name
     * 
     * @return string Name of the permission
     */
    public function name();
    
    /**
     * Returns the description of the purpose for this permission
     * 
     * @return string Description of the purpose for this permission
     */
    public function description();
    
    
}

?>