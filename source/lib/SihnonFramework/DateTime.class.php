<?php

class SihnonFramework_DateTime {
    
    const MINUTE = 60;
    const HOUR   = 3600;
    const DAY    = 86400;
    const WEEK   = 604800;
    const MONTH  = 2629744;
    const YEAR   = 31556926;

    /**
     * Formats the time in a fuzzy way
     * 
     * Taken from http://byteinn.com/res/426/Fuzzy_Time_function/ and reformatted for use in a class.
     * "An online free resource for developers who believe in reuse of code and do not waste time to reinvent the wheel."
	 * info@byteinn.com
     * 
     * @param mixed $time The time to be formatted
     * @return string
     */
    public static function fuzzyTime($time) {
        if ( ! preg_match('/^\d+$/', $time)) {
            if (($time = strtotime($time)) == false) {
                return 'never';
            }
        }
        
        $now = time();
        
        // sod = start of day :)
        $sod = mktime(0, 0, 0, date('m', $time), date('d', $time), date('Y', $time));
        $sod_now = mktime(0, 0, 0, date('m', $now), date('d', $now), date('Y', $now));

        // used to convert numbers to strings
        $convert = array(
            1 => 'one', 
            2 => 'two', 
            3 => 'three',
            4 => 'four',
            5 => 'five', 
            6 => 'six', 
            7 => 'seven', 
            8 => 'eight', 
            9 => 'nine', 
            10 => 'ten', 
            11 => 'eleven'
        );

        // today
        if ($sod_now == $sod) {
            if ($time > $now - (self::MINUTE * 3)) {
                return 'just a moment ago';
            } else if ($time > $now - (self::MINUTE * 7)) {
                return 'a few minutes ago';
            } else if ($time > $now - (self::HOUR)) {
                return 'less than an hour ago';
            }
            return 'today at ' . date('g:ia', $time);
        }

        // yesterday
        if (($sod_now - $sod) <= self::DAY) {
            if (date('i', $time) > (self::MINUTE + 30)) {
                $time += self::HOUR / 2;
            }
            return 'yesterday around ' . date('ga', $time);
        }

        // within the last 5 days
        if (($sod_now - $sod) <= (self::DAY * 5)) {
            $str = date('l', $time);
            $hour = date('G', $time);
            if ($hour < 12) {
                $str .= ' morning';
            } else if ($hour < 17) {
                $str .= ' afternoon';
            } else if ($hour < 20) {
                $str .= ' evening';
            } else {
                $str .= ' night';
            }
            return $str;
        }

        // number of weeks (between 1 and 3)...
        if (($sod_now-$sod) < (self::WEEK * 3.5)) {
            if (($sod_now-$sod) < (self::WEEK * 1.5)) {
                return 'about a week ago';
            } else if (($sod_now-$sod) < (self::DAY * 2.5)) {
                return 'about two weeks ago';
            } else {
                return 'about three weeks ago';
            }
        }

        // number of months (between 1 and 11)...
        if (($sod_now-$sod) < (self::MONTH * 11.5)) {
            for ($i = (self::WEEK * 3.5), $m=0; $i < self::YEAR; $i += self::MONTH, $m++) {
                if ( ($sod_now-$sod) <= $i ) {
                    return 'about ' . $convert[$m] . ' month' . (($m>1)?'s':'') . ' ago';
                }
            }
        }

        // number of years...
        for ($i = (self::MONTH * 11.5), $y=0; $i < (self::YEAR * 10); $i += self::YEAR, $y++) {
            if (($sod_now-$sod) <= $i) {
                return 'about ' . $convert[$y] . ' year' . (($y>1)?'s':'') . ' ago';
            }
        }

        // more than ten years...
        return 'more than ten years ago';
    }

}

?>