<?php

class SihnonFramework_Exception extends Exception {};

class SihnonFramework_Exception_NotInitialised         extends SihnonFramework_Exception {};
class SihnonFramework_Exception_AlreadyInitialisted    extends SihnonFramework_Exception {};

class SihnonFramework_Exception_NotImplemented         extends SihnonFramework_Exception {};
class SihnonFramework_Exception_UnsupportedFeature     extends SihnonFramework_Exception {};
class SihnonFramework_Exception_MissingDefinition      extends SihnonFramework_Exception {};
class SihnonFramework_Exception_ClassNotFound          extends SihnonFramework_Exception {};

class SihnonFramework_Exception_TemplateException      extends SihnonFramework_Exception {};
class SihnonFramework_Exception_AbortEntirePage        extends SihnonFramework_Exception_TemplateException {}; 
class SihnonFramework_Exception_Unauthorized           extends SihnonFramework_Exception_TemplateException {};
class SihnonFramework_Exception_FileNotFound           extends SihnonFramework_Exception_TemplateException {};
class SihnonFramework_Exception_NotAuthorised          extends SihnonFramework_Exception_TemplateException {};
class SihnonFramework_Exception_InvalidParameters      extends SihnonFramework_Exception_TemplateException {};

class SihnonFramework_Exception_DatabaseException      extends SihnonFramework_Exception {};
class SihnonFramework_Exception_DatabaseConfigMissing  extends SihnonFramework_Exception_DatabaseException {};
class SihnonFramework_Exception_DatabaseConnectFailed  extends SihnonFramework_Exception_DatabaseException {};
class SihnonFramework_Exception_NoDatabaseConnection   extends SihnonFramework_Exception_DatabaseException {};
class SihnonFramework_Exception_DatabaseQueryFailed    extends SihnonFramework_Exception_DatabaseException {};
class SihnonFramework_Exception_ResultCountMismatch    extends SihnonFramework_Exception_DatabaseException {};
class SihnonFramework_Exception_InvalidProperty        extends SihnonFramework_Exception_DatabaseException {};
class SihnonFramework_Exception_InvalidConditions      extends SihnonFramework_Exception_DatabaseException {};

class SihnonFramework_Exception_ConfigException        extends SihnonFramework_Exception {};
class SihnonFramework_Exception_UnknownSetting         extends SihnonFramework_Exception_ConfigException {};
class SihnonFramework_Exception_ReadOnlyConfigBackend  extends SihnonFramework_Exception_ConfigException {};
class SihnonFramework_Exception_SettingExists          extends SihnonFramework_Exception_ConfigException {};
class SihnonFramework_Exception_UnknownSettingType     extends SihnonFramework_Exception_ConfigException {};

class SihnonFramework_Exception_CacheException         extends SihnonFramework_Exception {};
class SihnonFramework_Exception_InvalidCacheDir        extends SihnonFramework_Exception_CacheException {};
class SihnonFramework_Exception_CacheObjectNotFound    extends SihnonFramework_Exception_CacheException {};

class SihnonFramework_Exception_InvalidPluginName      extends SihnonFramework_Exception {};

class SihnonFramework_Exception_LogException           extends SihnonFramework_Exception {};
class SihnonFramework_Exception_LogFileNotWriteable    extends SihnonFramework_Exception_LogException {};
class SihnonFramework_Exception_InvalidLog             extends SihnonFramework_Exception_LogException {};

class SihnonFramework_Exception_AuthException          extends SihnonFramework_Exception {};
class SihnonFramework_Exception_UnknownUser            extends SihnonFramework_Exception_AuthException {};
class SihnonFramework_Exception_IncorrectPassword      extends SihnonFramework_Exception_AuthException {};
class SihnonFramework_Exception_PasswordsDontMatch     extends SihnonFramework_Exception_AuthException {};
class SihnonFramework_Exception_AlreadyInUse           extends SihnonFramework_Exception_AuthException {};
class SihnonFramework_Exception_DeleteNotPermitted     extends SihnonFramework_Exception_AuthException {};

class SihnonFramework_Exception_ValidationException    extends SihnonFramework_Exception {};
class SihnonFramework_Exception_InvalidContent         extends SihnonFramework_Exception_ValidationException {};
class SihnonFramework_Exception_InvalidLength          extends SihnonFramework_Exception_ValidationException {};

class SihnonFramework_Exception_DaemonException        extends SihnonFramework_Exception {};
class SihnonFramework_Exception_AlreadyRunning         extends SihnonFramework_Exception_DaemonException {};
class SihnonFramework_Exception_LockingFailed          extends SihnonFramework_Exception_DaemonException {};

class SihnonFramework_Exception_LDAPException          extends SihnonFramework_Exception {};
class SihnonFramework_Exception_LDAPConnectionFailed   extends SihnonFramework_Exception_LDAPException {};
class SihnonFramework_Exception_LDAPSecureConnectionFailed extends SihnonFramework_Exception_LDAPException {};
class SihnonFramework_Exception_LDAPBindFailed         extends SihnonFramework_Exception_LDAPException {}; 

class SihnonFramework_Exception_CSRFException          extends SihnonFramework_Exception {};
class SihnonFramework_Exception_CSRFVerificationFailure extends SihnonFramework_Exception_CSRFException {};

?>
