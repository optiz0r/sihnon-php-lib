<?php

class SihnonFramework_Database {
    
    const SQLSTATE_SERVER_GONE = 'HY000';

    private $config;
    private $dbh;

    /**
     * Associative array of connection parameters for the database configuration
     * @var array(string=>string)
     */
    private $database_config;
    
    private $hostname;
    private $username;
    private $password;
    private $dbname;

    private $prepared_statements = array();

    public function __construct($dbconfig) {
        if ( ! file_exists($dbconfig)) {
            throw new SihnonFramework_Exception_DatabaseConfigMissing("config file not found");
        }
        
        $this->database_config = parse_ini_file($dbconfig);
        
        $this->hostname = $this->getDatabaseConfig('hostname');
        $this->username = $this->getDatabaseConfig('username');
        $this->password = $this->getDatabaseConfig('password');
        $this->dbname   = $this->getDatabaseConfig('dbname');

        try {
            $this->dbh  = new PDO("mysql:host={$this->hostname};dbname={$this->dbname}", $this->username, $this->password, array(PDO::ATTR_PERSISTENT => true));
        } catch (PDOException $e) {
            throw new Sihnon_Exception_DatabaseConnectFailed($e->getMessage());
        }

    }

    public function __destruct() {
        $this->dbh = null;
    }
    
    protected function reconnect() {
        $this->dbh = null;
        
        try {
            $this->dbh  = new PDO("mysql:host={$this->hostname};dbname={$this->dbname}", $this->username, $this->password, array(PDO::ATTR_PERSISTENT => true));
        } catch (PDOException $e) {
            throw new Sihnon_Exception_DatabaseConnectFailed($e->getMessage());
        }
    }
    
    /**
     * Returns the value of the named item from the database configuration file
     *
     * @param string $key Name of the setting to retrieve
     */
    private function getDatabaseConfig($key) {
        if (!isset($this->database_config[$key])) {
            throw new Sihnon_Exception_DatabaseConfigMissing($key);
        }

        return $this->database_config[$key];
    }
    
    private function query($sql, $count = 0) {
        $results = $this->dbh->query($sql);
        if (! $results) {
            list($std_code, $driver_code, $message) = $this->dbh->errorInfo();
            
            if ($count == 0 && $std_code == static::SQLSTATE_SERVER_GONE) {
                // Retry the query before failing
                return $this->query($sql, ++$count);
            } else {
                throw new Sihnon_Exception_DatabaseQueryFailed($message, $driver_code);
            }
        }
        
        return $results;
    }

    
    public function selectAssoc($sql, $key_col, $value_cols) {
        $results = array();

        foreach ($this->query($sql) as $row) {
            if (is_array($value_cols)) {
                $values = array();
                foreach ($value_cols as $value_col) {
                    $values[$value_col] = $row[$value_col];
                }
                
                $results[$row[$key_col]] = $values;
            } else {
                $results[$row[$key_col]] = $row[$value_col];
            }
        }

        return $results;
    }

	public function selectList($sql, $bind_params = null, $count = 0) {
		if ($bind_params) {
	        $stmt = $this->dbh->prepare($sql);
	        
            foreach ($bind_params as $param) {
                $stmt->bindValue(':'.$param['name'], $param['value'], $param['type']);
            }

            $result = $stmt->execute();
            if (! $result) {
                list($std_code, $driver_code, $message) = $stmt->errorInfo();
                
                if ($count == 0 && $std_code == static::SQLSTATE_SERVER_GONE) {
                    $this->reconnect();
                    return $this->insert($sql, $bind_params, ++$count);
                } else {
                    throw new Sihnon_Exception_DatabaseQueryFailed($message, $driver_code);
                }
            }
            
			return $stmt->fetchAll();

		} else {
			$results = array();

			$result = $this->query($sql);
	        foreach ($result as $row) {
				$results[] = $row;
			}

			return $results;
		}
	}

	public function selectOne($sql, $bind_params = null) {
		$rows = $this->selectList($sql, $bind_params);
		if (count($rows) != 1) {
			throw new Sihnon_Exception_ResultCountMismatch(count($rows));
		}

		return $rows[0];
	}

    public function insert($sql, $bind_params = null, $count = 0) {
        $stmt = $this->dbh->prepare($sql);

        if ($bind_params) {
            foreach ($bind_params as $param) {
                if (isset($param['type'])) {
                    $stmt->bindValue(':'.$param['name'], $param['value'], $param['type']);
                } else {
                    $stmt->bindValue(':'.$param['name'], $param['value']);
                }
            }
        }

        $result = $stmt->execute();
        if (! $result) {
            list($std_code, $driver_code, $message) = $stmt->errorInfo();
            
            if ($count == 0 && $std_code == static::SQLSTATE_SERVER_GONE) {
                $this->reconnect();
                return $this->insert($sql, $bind_params, ++$count);
            } else {
                throw new Sihnon_Exception_DatabaseQueryFailed($message, $driver_code);
            }
        }
        
        return $result;
    }
    
    public function update($sql, $bind_params = null, $count = 0) {
        $stmt = $this->dbh->prepare($sql);

        if ($bind_params) {
            foreach ($bind_params as $param) {
                if (isset($param['type'])) {
                    $stmt->bindValue(':'.$param['name'], $param['value'], $param['type']);
                } else {
                    $stmt->bindValue(':'.$param['name'], $param['value']);
                }
            }
        }

        $result = $stmt->execute();
        if (! $result) {
            list($std_code, $driver_code, $message) = $stmt->errorInfo();
            
            if ($count == 0 && $std_code == static::SQLSTATE_SERVER_GONE) {
                $this->reconnect();
                return $this->update($sql, $bind_params, ++$count);
            } else {
                throw new Sihnon_Exception_DatabaseQueryFailed($message, $driver_code);
            }
        }
        
        return $result;
    }

    public function errorInfo() {
        return $this->dbh->errorInfo();
    }
    
    public function lastInsertId() {
        return $this->dbh->lastInsertId();
    }

}

?>
