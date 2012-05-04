<?php

/**
 * Provides methods to retrieve information about group permissions in the authentication backend.
 */
interface SihnonFramework_Auth_Group_IFinelyPermissionable {

    /**
     * Returns the list of permissions associated with this group
     *
     * @return array(Sihnon_Auth_IPermission)
     */
    public function permissions();
    
    /**
     * Checks if the group holds the given permission
     *
     * @param Sihnon_Auth_IPermission $permission Permission to be checked
     * @return bool Returns True if this group holds the given permission, false otherwise.
     */
    public function hasPermission(Sihnon_Auth_IPermission $permission);
    
    /**
     * Returns the list of available permissions not already associated with this group
     *
     * @return array(Sihnon_Auth_IPermission)
     */
    public function unusedPermissions();
        
}

?>