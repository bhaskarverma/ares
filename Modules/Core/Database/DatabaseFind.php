<?php

namespace Ares\Modules\Core\Database;

class DatabaseFind {

    private $pdo = null;
    private $tableName = null;
    private $findCols = null;
    private $whereData = null;
    private $sqlQuery = null;

    public function __construct($pdo, $tableName, $findCols)
    {
        $this->pdo = $pdo;
        $this->tableName = $tableName;
        $this->findCols = $findCols;

        $this->prepQuery();
    }

    private function prepQuery()
    {
        $this->sqlQuery = "SELECT ";

        if(!empty($this->findCols))
        {
            for($i=0; $i<count($this->findCols); $i++)
            {
                $this->sqlQuery .= $this->findCols[$i];

                if($i < count($this->findCols) - 1)
                {
                    $this->sqlQuery .= ", ";
                }
                else
                {
                    $this->sqlQuery .= " ";
                }
            }
        }
        else
        {
            $this->sqlQuery .= "* ";
        }

        $this->sqlQuery .= "FROM ".$this->tableName;
    }

    public function where($data)
    {
        $this->sqlQuery .= " WHERE ";
        
        foreach($data AS $col => $val)
        {
            $this->sqlQuery .= $col." = :".$col;
        }

        $this->whereData = $data;
        return $this;
    }

    public function one()
    {
        $data = array();

        foreach($this->whereData AS $col => $val)
        {
            $data[':'.$col] = $val;
        }

        $this->pdo->prepare($this->sqlQuery)->exec($data)->fetch();
    }

    public function all()
    {
        $data = array();

        foreach($this->whereData AS $col => $val)
        {
            $data[':'.$col] = $val;
        }

        $this->pdo->prepare($this->sqlQuery)->exec($data)->fetchAll();
    }

}