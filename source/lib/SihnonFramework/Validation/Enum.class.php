<?php

Class SihnonFramework_Validation_Enum extends SihnonFramework_Validation {
    
    public static function validate($inputs, $class, $prefix) {
        if ( ! is_array($inputs)) {
            $inputs = array($inputs);
        }
        
        foreach ($inputs as $input) {
            if ( ! SihnonFramework_Main::isClassConstantValue($class, $prefix, $input)) {
                throw new SihnonFramework_Exception_InvalidContent();
            }
        }
    }
    
}

?>