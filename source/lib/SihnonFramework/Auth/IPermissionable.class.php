<?php

interface SihnonFramework_Auth_IPermissionable {
    
    public function isAdministrator(Sihnon_Auth_IUser $user);
    
}

?>