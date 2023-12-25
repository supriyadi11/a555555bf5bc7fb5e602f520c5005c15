<?php
namespace App;

class Database {

    private string $_db;
    private string $_connectionName;
    private string $_dbname;
    private string $_host;
    private string $_username;
    private string $_password;
    private int $_port;
    private $_connection;

    public function __construct($connectionName = 'mysql') {
        $this->_connectionName = $connectionName;
        $config = config("database.{$this->_connectionName}");
        $this->_db = $config['db'];
        $this->_dbname = $config['dbname'];
        $this->_host = $config['host'];
        $this->_username = $config['username'];
        $this->_password = $config['password'];
        $this->_port = $config['port'];
    }

    public function connect()
    {
        try {
            $this->_connection = new \PDO("{$this->_db}:host={$this->_host};dbname={$this->_dbname};port={$this->_port}", $this->_username, $this->_password);
            $this->_connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch(\PDOException $e) {
            throw $e;
        }
    }

    public function getConnection()
    {
        return $this->_connection;
    }
}