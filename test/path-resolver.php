<?php

define('HBC_File', 'test');

require_once('test/config.php');
require_once('source/lib/SihnonFramework/Main.class.php');

var_dump(
    SihnonFramework_Main::makeAbsolutePath('source/lib/SihnonFramework/Main.class.php'),
    SihnonFramework_Main::makeAbsolutePath('../sihnon-php-lib/source/lib/SihnonFramework/Main.class.php'),
    SihnonFramework_Main::makeAbsolutePath('/home/ben/projects/sihnon-php-lib/source/lib/SihnonFramework/Main.class.php')
);

?>