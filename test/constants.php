<?php

define('HBC_File', 'test');

require_once('test/config.php');
require_once('source/lib/SihnonFramework/Main.class.php');

SihnonFramework_Main::registerAutoloadClasses('SihnonFramework', SihnonFramework_Lib, 'Sihnon', Sihnon_Lib);

assert('SihnonFramework_Main::isClassConstantValue("SihnonFramework_Config", "TYPE_", "bool") == true');
assert('SihnonFramework_Main::isClassConstantValue("SihnonFramework_Config", "TYPE_", "int") == true');
assert('SihnonFramework_Main::isClassConstantValue("SihnonFramework_Config", "TYPE_", "string") == true');
assert('SihnonFramework_Main::isClassConstantValue("SihnonFramework_Config", "TYPE_", "array(string)") == true');
assert('SihnonFramework_Main::isClassConstantValue("SihnonFramework_Config", "TYPE_", "class") == false');

?>