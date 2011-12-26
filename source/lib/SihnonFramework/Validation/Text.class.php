<?php

class SihnonFramework_Validation_Text extends SihnonFramework_Validation {
    
    const Defaults     = 0xff;
    const Alphabetical = 0x01;
    const Digit        = 0x02;
    const Numeric      = 0x04;
    const Symbol       = 0x08;
    const Whitespace   = 0x16;
    
    protected static $charsets = array(
        static::Alphabetical => ':alpha:',
        static::Digit        => ':digit:',
        static::Numeric      => ':digit:\.-',
        static::Symbol       => ':punct:',
        static::Whitespace   => ':space:',
    );

    public static function charset($input, $charset = static::Defaults) {
        static::pattern($input, static::buildCharsetPattern($charset));
    }
    
    public static function length($input, $min_length = null, $max_length = null) {
        $length = strlen($input);
        
        if ($min_length !== null && $length < $min_length) {
            throw new SihnonFramework_Exception_InvalidLength();
        }
        
        if ($max_length !== null && $length > $max_length) {
            throw new SihnonFramework_Exception_InvalidLength();
        }
    }
    
    public static function pattern($input, $pattern) {
        if ( ! preg_match($pattern, $input)) {
            throw new SihnonFramework_Exception_InvalidContent();
        }
    }
    
    protected static function buildCharsetPattern($charset) {
        $classes = '';
        foreach (static::$charsets as $set => $class) {
            if ($charset & $set) {
                $classes .= $class;
            }
        }
        
        return "/^[{$classes}]*$/";
    }
    
}

?>