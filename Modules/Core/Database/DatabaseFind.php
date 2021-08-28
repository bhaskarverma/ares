<?php

namespace Ares\Modules\Core\Database;

class DatabaseFind {

    private $pdo = null;
    private $tableName = null;
    private $modelObj = null;
    private $modelProps = null;
    private $findCols = [];
    private $whereData = [];
    private $sqlQuery = null;

    public function __construct($pdo, $tableName, $findCols, $modelObj)
    {
        $this->pdo = $pdo;
        $this->tableName = $tableName;
        $this->findCols = $findCols;
        $this->modelObj = $modelObj;

        $reflectionClass = new \ReflectionClass(get_class($this->modelObj));
        $this->modelProps = $reflectionClass->getProperties();

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

        $this->sqlQuery .= "FROM `".$this->tableName."`";
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

        $prep = $this->pdo->prepare($this->sqlQuery);
        $prep->execute($data);
        $retVal = $prep->fetch();

        $returnObj = new \stdClass();

        foreach($retVal as $col => $val)
        {
            $returnObj->$col = $val;
        }

        return $returnObj;
    }

    public function all()
    {
        $data = array();

        foreach($this->whereData AS $col => $val)
        {
            $data[':'.$col] = $val;
        }

        $prep = $this->pdo->prepare($this->sqlQuery);
        $prep->execute($data);
        $retVal = $prep->fetchAll();

        $returnArr = [];

        for($i=0; $i<count($retVal); $i++)
        {
            $returnObj = new \stdClass();

            foreach($retVal[$i] as $col => $val)
            {
                $returnObj->$col = $val;
            }

            array_push($returnArr, $returnObj);
        }

        return $returnArr;
    }

}