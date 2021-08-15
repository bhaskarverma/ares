<?php

namespace Ares\Modules\Core\Database;

class DatabaseCore {

    private $pdo = null;
    private $dbJson = null;

    public function __construct($dbJson)
    {
        $this->dbJson = $dbJson;
    }

    public function getConnection()
    {
        if($this->pdo == null)
        {
            $dsn = "mysql:host=".$this->dbJson['host'].";dbname=".$this->dbJson['database'].";charset=".$this->dbJson['charset'];
            $options = [
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            $pdo = new \PDO($dsn, $this->dbJson['user'], $this->dbJson['pass'], $options);
            $this->pdo = $pdo;
        }

        return $this->pdo;
    }

}