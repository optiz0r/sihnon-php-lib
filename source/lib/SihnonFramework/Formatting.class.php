<?php

class SihnonFramework_Formatting {
    
    public static function filesize($bytes) {
        return SihnonFramework_Main::formatFilesize($bytes);
    }

    public static function duration($seconds, $fuzziness) {
        return SihnonFramework_Main::formatDuration($seconds, $fuzziness);
    }
    
    public static function pluralise($count, $singular, $multiple) {
        if ($count == 1) {
            return $singular;
        } else{
            return $multiple;
        }
    }
};

?>