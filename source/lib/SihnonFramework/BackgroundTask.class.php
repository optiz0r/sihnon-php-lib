<?php

class SihnonFramework_BackgroundTask {

    protected function __construct() {
        
    }

    public static function run($command) {
        SihnonFramework_LogEntry::debug(SihnonFramework_Main::instance()->log(), "Running background task: {$command} &", 'default');
        $pipes = array();
        $pid = proc_open($command . ' &', array(), $pipes);
        proc_close($pid);
    }
    
};

?>