<?php

/**
 * Provides methods to modify the authentication backend, such as adding and removing users, or changing passwords.
 *
 * Backends which implement this interface myst also implement the SihnonFramework_Auth_User_IUpdateable
 * interface on their Sihnon_Auth_IUser class(es).
 */
interface SihnonFramework_Auth_IUpdateable {

    /**
     * Creaates a new entry for this user in the backend
     * 
     * @param string $username Unique login name for the user
     * @param string $password Plaintext password for the user
     */
    public function addUser($username, $password);
    
    /**
     * Removes the entry for this user from the backend
     */
    public function removeUser(Sihnon_Auth_IUser $user);
    
    /**
     * Changes the password for this user in the backend
     *
     * Use of this method rather than modifying any property directly is highly recommended
     * so that the correct encryption or hashing algorithm is used.
     */
    public function changePassword(Sihnon_Auth_IUser $user, $new_password);
    
}

?>