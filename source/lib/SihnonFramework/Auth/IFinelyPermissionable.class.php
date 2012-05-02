<?php

/**
 * Provides methods to check whether a given user holds one or more fine-grained permissions
 *
 * Compared with IPermissionable, these methods provide a more complex means of user access
 * control, such as having multuple classes of administrator account
 *
 * For auth backends which also support IFinelyPermissionable, calls to these methods
 * may be mapped to a subset of fine-grained permissions. Mixing and matching calls
 * to IPermissionable and IFinelyPermissionable methods is possible, but may lead
 * to unexpected results.
 *
 */
interface SihnonFramework_Auth_IFinelyPermissionable extends Sihnon_Auth_IPermissionable {
    
    /**
     * Checks if the user holds the given permission
     *
     * Used to provide RBAC to restrict access to certain features of an application.
     * Permissions may be defined as class constants or as extensible items stored in the backend.
     *
     * @param Sihnon_Auth_IUser $user User to be checked
     * @param int $permission Identified for the permission to be checked.
     * @return bool Returns true if the user holds the permission, false otherwise.
     */
    public function hasPermission(Sihnon_Auth_IUser $user, $permission);
    
}

?>