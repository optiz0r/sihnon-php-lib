<?php

interface SihnonFramework_Auth_IUpdateable {

    public function addUser($username, $password);
    
    public function removeUser(Sihnon_Auth_IUser $user);
    
    public function changePassword(Sihnon_Auth_IUser $user, $new_password);
    
}

?>