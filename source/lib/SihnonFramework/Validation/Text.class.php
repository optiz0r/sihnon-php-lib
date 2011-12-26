<?php

class SihnonFramework_Validation_Text extends SihnonFramework_Validation {
    
    const Defaults     = 0xff;
    const Alphabetical = 0x01;
    const Digit        = 0x02;
    const Numeric      = 0x04;
    const Symbol       = 0x08;
    const Whitespace   = 0x16;
    
    protected static $contents = array(
        static::Alphabetical => ':alpha:',
        static::Digit        => ':digit:',
        static::Numeric      => ':digit:\.-',
        static::Symbol       => ':punct:',
        static::Whitespace   => ':space:',
    );

    public static function content($inputs, $content = static::Defaults) {
        static::pattern($inputs, static::buildContentPattern($content));
    }
    
    public static function length($inputs, $min_length = null, $max_length = null) {
        if ( ! is_array($inputs)) {
            $inputs = array($inputs);
        }
        
        foreach ($inputs as $input) { 
            $length = strlen($input);
        
            if ($min_length !== null && $length < $min_length) {
                throw new SihnonFramework_Exception_InvalidLength();
            }
            
            if ($max_length !== null && $length > $max_length) {
                throw new SihnonFramework_Exception_InvalidLength();
            }
        }
    }
    
    public static function pattern($inputs, $pattern) {
        if ( ! is_array($inputs)) {
            $inputs = array($inputs);
        }
        
        foreach ($inputs as $input) {
            if ( ! preg_match($pattern, $input)) {
                throw new SihnonFramework_Exception_InvalidContent();
            }
        }
    }
    
    protected static function buildContentPattern($contents) {
        $classes = '';
        foreach (static::$contents as $set => $class) {
            if ($contents & $set) {
                $classes .= $class;
            }
        }
        
        return "/^[{$classes}]*$/";
    }
    
}

?>