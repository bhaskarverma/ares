<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once("vendor/autoload.php");

use Aura\Router\RouterContainer;
use Ares\Models\Core\FrameworkRoutesModel;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\Diactoros\Response;
use Aura\Session\SessionFactory;
use Ares\Modules\Core\View\ViewEngine;

$session_factory = new SessionFactory();
$session = $session_factory->newInstance($_COOKIE)->getSegment('Ares');

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

$session->set("envJson", $envJson);
$session->set("dbJson", $dbJson);

$routeModel = new FrameworkRoutesModel();

$routes = $routeModel->find()->all();

foreach($routes as $route)
{
    $map->{$route->routeMethod}($route->routeName, $route->routePath, $route->routeClass);
}

$matcher = $routerContainer->getMatcher();
$route = $matcher->match($request);

if (!$route) {
    if($request->GetServerParams()['REQUEST_METHOD'] == 'GET')
    {
        $view = new ViewEngine();
        $response->getBody()->write($view->render());
        $resp = $response;
    }
    else
    {
        header('HTTP/1.0 404 Not Found');
        exit;
    }
}
else
{
    foreach ($route->attributes as $key => $val) {
        $request = $request->withAttribute($key, $val);
    }
    
    list($action, $method) = explode('@', $route->handler);
    
    $obj = new $action($request, $response);
    $resp = $obj->$method();
    
}

foreach ($resp->getHeaders() as $name => $values) {
    foreach ($values as $value) {
        header(sprintf('%s: %s', $name, $value), false);
    }
}
http_response_code($resp->getStatusCode());
echo $resp->getBody();