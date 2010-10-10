<?php

class Sihnon_Exception extends Exception {};

class Sihnon_Exception_DatabaseException      extends Sihnon_Exception {};
class Sihnon_Exception_DatabaseConfigMissing  extends Sihnon_Exception_DatabaseException {};
class Sihnon_Exception_DatabaseConnectFailed  extends Sihnon_Exception_DatabaseException {};
class Sihnon_Exception_NoDatabaseConnection   extends Sihnon_Exception_DatabaseException {};
class Sihnon_Exception_DatabaseQueryFailed    extends Sihnon_Exception_DatabaseException {};
class Sihnon_Exception_ResultCountMismatch    extends Sihnon_Exception_DatabaseException {};

class Sihnon_Exception_ConfigException        extends Sihnon_Exception {};
class Sihnon_Exception_UnknownSetting         extends Sihnon_Exception_ConfigException {};

class Sihnon_Exception_CacheException         extends Sihnon_Exception {};
class Sihnon_Exception_InvalidCacheDir        extends Sihnon_Exception_CacheException {};
class Sihnon_Exception_CacheObjectNotFound    extends Sihnon_Exception_CacheException {};

class Sihnon_Exception_InvalidPluginName      extends Sihnon_Exception {};

?>
