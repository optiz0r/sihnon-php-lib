<?php

class SihnonFramework_BackgroundTask {

    protected function __construct() {
        
    }

    public static function run($command, $cwd=null, $env=null) {
        SihnonFramework_LogEntry::debug(SihnonFramework_Main::instance()->log(), "Running background task: {$command} &", 'default');
        $pipes = array();
        $pid = proc_open($command . ' &', array(), $pipes, $cwd, $env);
        proc_close($pid);
    }
    
};

?>