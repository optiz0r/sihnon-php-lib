<?php

/**
 * Provides methods handle user group management in an authentication backend
 */
interface SihnonFramework_Auth_IGroupable {

    /**
     * Checks to see whether a given username exists within the backend
     *
     * @param string $groupname Unique login name for the group to be checked.
     * @return bool Returns true if the group is known to the backend, false otherwise.
     */
    public function groupExists($groupname);
    
    /**
     * Returns a list of all users known to the backend.
     *
     * @return array(Sihnon_Auth_IUser)
     */
    public function listGroups();
    
    /**
     * Retrieves a group
     *
     * @param string $groupname Unique name for the group
     * @param Sihnon_Auth_IGroup Group object
     */
    public function group($groupname);
    
    
}

?>