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
     * Returns the list of available permissions not already associated with this group
     *
     * @return array(Sihnon_Auth_IPermission)
     */
    public function unusedPermissions();
        
}

?>