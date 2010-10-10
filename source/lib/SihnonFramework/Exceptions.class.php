<?php

class SihnonFramework_Exception extends Exception {};

class SihnonFramework_Exception_NotImplemented         extends Sihnon_Exception {};
class SihnonFramework_Exception_MissingDefinition      extends Sihnon_Exception {};

class SihnonFramework_Exception_DatabaseException      extends Sihnon_Exception {};
class SihnonFramework_Exception_DatabaseConfigMissing  extends Sihnon_Exception_DatabaseException {};
class SihnonFramework_Exception_DatabaseConnectFailed  extends Sihnon_Exception_DatabaseException {};
class SihnonFramework_Exception_NoDatabaseConnection   extends Sihnon_Exception_DatabaseException {};
class SihnonFramework_Exception_DatabaseQueryFailed    extends Sihnon_Exception_DatabaseException {};
class SihnonFramework_Exception_ResultCountMismatch    extends Sihnon_Exception_DatabaseException {};

class SihnonFramework_Exception_ConfigException        extends Sihnon_Exception {};
class SihnonFramework_Exception_UnknownSetting         extends Sihnon_Exception_ConfigException {};

class SihnonFramework_Exception_CacheException         extends Sihnon_Exception {};
class SihnonFramework_Exception_InvalidCacheDir        extends Sihnon_Exception_CacheException {};
class SihnonFramework_Exception_CacheObjectNotFound    extends Sihnon_Exception_CacheException {};

class SihnonFramework_Exception_InvalidPluginName      extends Sihnon_Exception {};

?>
