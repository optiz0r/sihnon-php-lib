<?php

/**
 * Provides methods to modify group permissions in the authentication backend.
 */
interface SihnonFramework_Auth_Group_IUpdateableFinePermissions {

    /**
     * Adds a permission to this group
     *
     * @param Sihnon_Auth_IPermission
     */
    public function addPermission(Sihnon_Auth_IPermission $permission);
    
    /**
     * Removes a permission from this group
     * 
     * @param Sihnon_Auth_IPermission
     */
    public function removePermission(Sihnon_Auth_IPermission $permission);
        
}

?>