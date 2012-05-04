<?php

/**
 * Provides methods to modify groups inthe authentication backend.
 *
 * Backends which implement this interface myst also implement the SihnonFramework_Auth_Group_IUpdateable
 * interface on their Sihnon_Auth_IGroup class(es).
 */
interface SihnonFramework_Auth_IUpdateableGroups {

    /**
     * Creaates a new entry for this group in the backend
     * 
     * @param string $groupname Unique name for the group
     * @param string $description Text description of the pupose for the group
     */
    public function addGroup($groupname, $description);
    
    /**
     * Checks whether the given group may be removed by the user
     *
     * Some groups may be vital for system operation, such as an administrators group
     * 
     * @param Sihnon_Auth_IGroup $group Group to be tested for removability
     * @return bool Returns True if the group may be removed, false otherwise.
     */
    public function removable(Sihnon_Auth_IGroup $group);
    
    /**
     * Removes the entry for this group from the backend
     *
     * @param Sihnon_Auth_IGroup $group Group to be removed.
     */
    public function removeGroup(Sihnon_Auth_IGroup $group);
        
    /**
     * Add a user to this group in the backend
     *
     * @param Sihnon_Auth_IGroup $group Group to be modified
     * @param Sihnon_Auth_IUser $user User to be added to the group
     */
    public function addUserToGroup(Sihnon_Auth_IGroup $group, Sihnon_Auth_IUser $user);
    
    /**
     * Removes a user from this group in the backend
     *
     * @param Sihnon_Auth_IGroup $group Group to be modified
     * @param Sihnon_Auth_IUser $user User to be removed from the group
     */
    public function removeUserFromGroup(Sihnon_Auth_IGroup $group, Sihnon_Auth_IUser $user);

}

?>