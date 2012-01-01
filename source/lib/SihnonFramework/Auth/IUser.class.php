<?php

interface SihnonFramework_Auth_IUser {
    
    public function username();
    
    public function checkPassword($password);
    
}

?>