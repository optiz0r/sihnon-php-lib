<?php

interface SihnonFramework_Auth_IFinelyPermissionable extends Sihnon_Auth_IPermissionable {
    
    public function hasPermission(Sihnon_Auth_IUser $user, $permission);
    
}

?>