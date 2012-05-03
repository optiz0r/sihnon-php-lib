<?php

/**
 * Provides methods to retrieve information about a user group stored in an authentication backend
 */
interface SihnonFramework_Auth_IGroup {

    /**
     * Returns the unique identifier for the group
     *
     * Depending on the implementation, this could be a numeric or a name
     *
     * @return mixed Unique identifier
     */
    public function id();
    
    /**
     * Returns the group name
     * 
     * @return string Group name
     */
    public function name();
    
    /**
     * Returns the group description
     *
     * @return string Group description
     */
    public function description();
    
    /**
     * Lists the users that are members of this group
     *
     * @return array(Sihnon_Auth_IUser)
     */
    public function users($ignore_cache = false);
    
    /**
     * Checks if the given user is in this froup in the backend
     *
     * @param Sihnon_Auth_IUser $user User to be checked
     * @return bool Returns true if the user is in this group, false otherwise.
     */
    public function inGroup(Sihnon_Auth_IUser $user);
    
    
}

?>