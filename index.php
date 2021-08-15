<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once("vendor/autoload.php");

use Ares\Modules\Core\Database\DatabaseFactory;

$DBFactory = new DatabaseFactory("local");

$frameworkRoute = $DBFactory->loadModel("Core","FrameworkRoutesModel")->initialize();

$frameworkRoute->routeName = "TestRoute";
$frameworkRoute->routePath = "TestRoutePath";
$frameworkRoute->isAuthRequired = "No";

$frameworkRoute->insert();