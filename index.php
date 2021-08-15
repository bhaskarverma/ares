<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once("vendor/autoload.php");

use Aura\Router\RouterContainer;
use Ares\Models\Core\FrameworkRoutesModel;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\Diactoros\Response;

$request = ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);
$response = new Response();

$routerContainer = new RouterContainer();
$map = $routerContainer->getMap();

$envStr = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/Configuration/env.json');
$envJson = json_decode($envStr, true);

$dbStr = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/Configuration/Database/'.$envJson['env'].'.json');
$dbJson = json_decode($dbStr, true);

$routeModel = new FrameworkRoutesModel($dbJson);

$routes = $routeModel->find()->all();

for($i=0; $i<count($routes); $i++)
{
    $map->{$routes[$i]['routeMethod']}($routes[$i]['routeName'], $routes[$i]['routePath'], $routes[$i]['routeClass']);
}

$matcher = $routerContainer->getMatcher();
$route = $matcher->match($request);

if (!$route) {
    header('HTTP/1.0 404 Not Found');
    exit;
}

foreach ($route->attributes as $key => $val) {
    $request = $request->withAttribute($key, $val);
}

list($action, $method) = explode('@', $route->handler);

$obj = new $action($request, $response);
$resp = $obj->$method();

foreach ($resp->getHeaders() as $name => $values) {
    foreach ($values as $value) {
        header(sprintf('%s: %s', $name, $value), false);
    }
}
http_response_code($resp->getStatusCode());
echo $resp->getBody();