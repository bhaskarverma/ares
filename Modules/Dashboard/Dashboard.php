<?php

namespace Ares\Modules\Dashboard;

use Ares\Modules\Core\View\ViewEngine;

class Dashboard {

    private $request = null;
    private $response  = null;

    public function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function getDash()
    {
        $engine = new ViewEngine("Core");
        $engine->loadTemplate("Base");

        $data = [];

        $data['project'] = 'Ares';

        $data['stylesheets'] = [];
        array_push($data['stylesheets'], "https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback");
        array_push($data['stylesheets'], "plugins/fontawesome-free/css/all.min.css");
        array_push($data['stylesheets'], "plugins/overlayScrollbars/css/OverlayScrollbars.min.css");
        array_push($data['stylesheets'], "css/adminlte.min.css");

        $data['scripts'] = [];
        array_push($data['scripts'], "plugins/jquery/jquery.min.js");
        array_push($data['scripts'], "plugins/bootstrap/js/bootstrap.bundle.min.js");
        array_push($data['scripts'], "plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js");
        array_push($data['scripts'], "js/adminlte.js");
        // array_push($data['scripts'], "css/adminlte.min.css");
        // array_push($data['scripts'], "css/adminlte.min.css");
        // array_push($data['scripts'], "css/adminlte.min.css");
        // array_push($data['scripts'], "css/adminlte.min.css");
        // array_push($data['scripts'], "css/adminlte.min.css");

        $data['paths'] = [];
        $data['module'] = [];
        $data['module']['title'] = "Dashboard";
        $data['module']['displayName'] = "Dashboard";
        $data['module']['body'] = '';
        $data['user'] = [];
        $data['user']['name'] = 'Bhaskar Verma';

        $engine->loadData($data);
        
        $this->response->getBody()->write($engine->render());
        return $this->response;
    }

}