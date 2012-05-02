<?php

/**
 * Provides methods to check whether a given user holds a pre-defined permission set
 *
 * Compared with IFinelyPermissionable, these methods provide a simple means of user access
 * control, such as having a single class of administrator account
 *
 * For auth backends which also support IFinelyPermissionable, calls to these methods
 * may be mapped to a subset of fine-grained permissions. Mixing and matching calls
 * to IPermissionable and IFinelyPermissionable methods is possible, but may lead
 * to unexpected results.
 *
 */
interface SihnonFramework_Auth_IPermissionable {
    
    /**
     * Checks if the user is defined as an Administrator
     *
     * @param Sihnon_Auth_IUser $user User to check administrative status of.
     * @return bool Returns true if the user is an administrator, false otherwise
     */
    public function isAdministrator(Sihnon_Auth_IUser $user);
    
}

?>