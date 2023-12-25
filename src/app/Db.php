<?php
namespace App;

class Db {

    protected string $query;
    protected \PDO $connection;
    protected array $result;

    public function __construct(\PDO $connection) {
        $this->connection = $connection;
    }

    public function query(string $query, array $params = [])
    {
        $params = array_map(function($item){
            return "'".addslashes($item)."'" ;
        }, $params);
        $this->query = vsprintf($query, $params);
        return $this;
    }

    public function get() : array
    {
        try {
            $statement = $this->connection->prepare($this->query);
            $statement->execute();
            $statement->setFetchMode(\PDO::FETCH_ASSOC);
            $this->result = $statement->fetchAll();
        } catch (\PDOException  $th) {
            logger('error', $th->getMessage(), ['query' => $this->getQuery()]);
            throw $th;
        }

        return $this->result;
    }

    public function exec()
    {
        try {
            $statement = $this->connection->prepare($this->query);
            return $statement->execute();
        } catch (\PDOException  $th) {
            logger('error', $th->getMessage(), ['query' => $this->getQuery()]);
            throw $th;
        }
    }

    public function getQuery()
    {
        return $this->query;
    }

}