<?php

/**
 * Provides methods to handle group management of a user in an authentication backend
 */
interface SihnonFramework_Auth_User_IGroupable {

    /**
     * Returns all users for a given group
     *
     * @return array(Sihnon_Auth_IUser)
     */
    public static function allForGroup(Sihnon_Auth_IGroup $group);
    
    /**
     * Returns all groups for this user
     *
     * @return array(Sihnon_Auth_IGroup)
     */
    public function groups();
    
    /**
     * Returns the list of groups that this user is not a member of
     *
     * 'return array(Sihnon_Auth_IGroup)
     */
    public function unusedGroups();
    
}

?>