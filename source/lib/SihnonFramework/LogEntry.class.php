<?php

abstract class SihnonFramework_LogEntry {

    protected static $table_name = "";

    protected $id;
    protected $level;
    protected $ctime;
    protected $pid;
    protected $hostname;
    protected $progname;
    protected $line;
    protected $message;
    
    protected function __construct($id, $level, $ctime, $pid, $hostname, $progname, $line, $message) {
        $this->id       = $id;
        $this->level    = $level;
        $this->ctime    = $ctime;
        $this->pid      = $pid;
        $this->hostname = $hostname;
        $this->progname = $progname;
        $this->line     = $line;
        $this->message  = $message;
    }

    public static function fromDatabaseRow($row) {
        $called_class = get_called_class();
        return new $called_class(
            $row['id'],
            $row['level'],
            $row['ctime'],
            $row['pid'],
            $row['hostname'],
            $row['progname'],
            $row['line'],
            $row['message']
        );
    }

    public static function fromId($id) {
        $called_class = get_called_class();
        $database = Sihnon_Main::instance()->database();
        return $called_class::fromDatabaseRow(
            $database->selectOne('SELECT * FROM '.static::$table_name.' WHERE id=:id', array(
                array('name' => 'id', 'value' => $id, 'type' => PDO::PARAM_INT)
                )
            )
        );
    }

    public static function recent($limit = 100) {
        $entries = array();

        $database = Sihnon_Main::instance()->database();
        foreach ($database->selectList('SELECT * FROM '.static::$table_name.' ORDER BY ctime DESC LIMIT :limit', array(
                array('name' => 'limit', 'value' => $limit, 'type' => PDO::PARAM_INT)
            )) as $row) {
            $entries[] = static::fromDatabaseRow($row);
        }

        return $entries;
    }

    public function id() {
        return $this->id;
    }

    public function level() {
        return $this->level;
    }

    public function ctime() {
        return $this->ctime;
    }

    public function pid() {
        return $this->pid;
    }

    public function hostname() {
        return $this->hostname;
    }

    public function progname() {
        return $this->progname;
    }

    public function line() {
        return $this->line;
    }

    public function message() {
        return $this->message;
    }

};

?>
