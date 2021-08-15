<?php

namespace Ares\Modules\Core\Database;

class DatabaseFactory {

    private $dbJson = null;
    private $modelPackage = null;
    private $model = null;

    public function __construct($configFile)
    {
        $jsonStr = file_get_contents($_SERVER['DOCUMENT_ROOT']."/Configuration/Database/".$configFile.".json");
        $this->dbJson = json_decode($jsonStr, true);
    }

    public function loadModel($package, $model)
    {
        $this->modelPackage = $package;
        $this->model = $model;
        return $this;
    }

    public function initialize()
    {
        $modelStr = "\\Ares\\Models\\".$this->modelPackage."\\".$this->model;
        $modelObj = new $modelStr($this->dbJson);
        return $modelObj;
    }

}