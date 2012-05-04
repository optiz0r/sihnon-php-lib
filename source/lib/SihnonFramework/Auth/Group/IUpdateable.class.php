<?php

/**
 * Provides methods to modify a groups in the authentication backend.
 */
interface SihnonFramework_Auth_Group_IUpdateable {

    /**
     * Creaates a new entry for this group in the backend
     * 
     * @param string $groupname Unique name for the group
     * @param string $description Text description of the purpose for this group
     */
    public static function add($groupname, $description);
    
    /**
     * Updates any changes to this group's information in the backend
     */
    public function save();

    /**
     * Removes the entry for this group from the backend
     */
    public function delete();
    
    /**
     * Checks whether this group may be removed by the user
     *
     * Some groups may be vital for system operation, such as an administrators group
     * 
     * @return bool Returns True if the group may be removed, false otherwise.
     */
    public function removable();
    
    /**
     * Add a user to this group in the backend
     *
     * @param Sihnon_Auth_IUser $user User to be added to the group
     */
    public function addUser(Sihnon_Auth_IUser $user);
    
    /**
     * Removes a user from this group in the backend
     *
     * @param Sihnon_Auth_IUser $user User to be removed from the group
     */
    public function removeUser(Sihnon_Auth_IUser $user);
    
    /**
     * Adds a new permission to this group in the backend
     *
     * @param Sihnon_Auth_IPermission $permission Permission to be added to the group
     */
    public function addPermission(Sihnon_Auth_IPermission $permission);
        
}

?>