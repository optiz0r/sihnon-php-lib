<?php

/**
 * Provides methods to modify the user in the backend, such as updating changes and changing passwords.
 */
interface SihnonFramework_Auth_User_IUpdateable {

    /**
     * Creaates a new entry for this user in the backend
     * 
     * @param string $username Unique login name for the user
     * @param string $password Plaintext password for the user
     */
    public static function add($username, $password);
    
    /**
     * Updates any changes to this user's information in the backend
     */
    public function save();

    /**
     * Removes the entry for this user from the backend
     */
    public function delete();
    
    /**
     * Changes the password for this user in the backend
     *
     * Use of this method rather than modifying any property directly is highly recommended
     * so that the correct encryption or hashing algorithm is used.
     */
    public function changePassword($new_password);
    
}

?>