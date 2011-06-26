<?php

define('HBC_File', 'test');

require_once('test/config.php');
require_once('source/lib/SihnonFramework/Main.class.php');

SihnonFramework_Main::registerAutoloadClasses('SihnonFramework', SihnonFramework_Lib,
												'RippingCluster', RippingCluster_Lib);
SihnonFramework_Main::registerAutoloadClasses('Net', RippingCluster_Lib);

assert(class_exists('RippingCluster_Cache', true));
assert(class_exists('Net_Gearman_Job_HandBrake', true));
assert(! class_exists('IDontExist', true));

?>