<?php

namespace Ares\Modules\Core\Database;

use Ares\Modules\Core\Database\DatabaseFind;
use Ares\Modules\Core\Database\DatabaseCore;
use Aura\Session\SessionFactory;

class ModelCore {

    protected $pdo = null;

    protected function init()
    {
        $session_factory = new SessionFactory();
        $this->session = $session_factory->newInstance($_COOKIE)->getSegment('Ares');
        $dbJson = $this->session->get("dbJson", "Not Found");
        $dbCore = new DatabaseCore($dbJson);
        $this->pdo = $dbCore->getConnection();
    }

    public function getTableName()
    {
        $classPath = get_class($this);
        $tableNameArr = explode("\\", $classPath);
        $className = $tableNameArr[count($tableNameArr) - 1];
        $modelName = strtolower(preg_replace('/(?<=\\w)(?=[A-Z])/',"-$1", $className));

        $tmpArr = explode("-", $modelName);
        array_pop($tmpArr);
        $tableName = implode("-",$tmpArr);

        return $tableName;
    }

    public function find($cols = [])
    {
        $tableName = $this->getTableName();
        $obj = new DatabaseFind($this->pdo, $tableName, $cols, $this);
        return $obj;
    }

    public function query($sql, $data = [])
    {
        return $this->pdo->prepare($sql)->exec($data);
    }

    public function update()
    {
        $reflectionClass = new \ReflectionClass(get_class($this));
        $props = $reflectionClass->getProperties();
        $cols = [];
        $id = "";

        foreach ($props as $prop) {
            if ($prop->class === get_class($this)) {
                if(strpos($prop->getName(), "ID") == false)
                {
                    $cols[$prop->getName()] = $prop->getValue($this);
                }
                else
                {
                    $id = $prop->getName();
                }
            }
        }

        $tableName = $this->getTableName();

        $updateQuery = "UPDATE ".$tableName." SET ";
        
        foreach($cols AS $col => $val)
        {
            $updateQuery .= $col." = ".$val;
        }

        $updateQuery .= " WHERE ".$id." = ?";

        $this->pdo->prepare($updateQuery)->exec([$this->$id]);
    }

    public function insert()
    {
        $reflectionClass = new \ReflectionClass(get_class($this));
        $props = $reflectionClass->getProperties();
        $cols = [];

        foreach ($props as $prop) {
            if ($prop->class === get_class($this)) {
                if(strpos($prop->getName(), "ID") == false)
                {
                    $cols[$prop->getName()] = $prop->getValue($this);
                }
            }
        }

        $tableName = $this->getTableName();

        $insertQuery = "INSERT INTO `".$tableName."` (";

        foreach($cols as $col => $val)
        {
            $insertQuery .= "`".$col."`,";
        }

        $insertQuery = rtrim($insertQuery, ",");
        $insertQuery .= ") VALUES (";

        foreach($cols as $col => $val)
        {
            $insertQuery .= ":".$col.",";
        }

        $insertQuery = rtrim($insertQuery, ",");
        $insertQuery .= ");";

        $insertData = [];

        foreach($cols as $col => $val)
        {
            $insertData[':'.$col] = $val;
        }

        $prepStatement = $this->pdo->prepare($insertQuery);
        $prepStatement->execute($insertData);
    }

    public function delete($id)
    {
        $reflectionClass = new \ReflectionClass(get_class($this));
        $props = $reflectionClass->getProperties();
        $idCol = "";

        foreach ($props as $prop) {
            if ($prop->class === get_class($this)) {
                if(strpos($prop->getName(), "ID") == true)
                {
                   $idCol = $prop->getName();
                }
            }
        }


        $tableName = $this->getTableName();

        $deleteQuery = "DELETE FROM ".$tableName." WHERE ".$idCol." = ?";

        $this->pdo->prepare($deleteQuery)->execute([$id]);
    }
}