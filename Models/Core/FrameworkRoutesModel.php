<?php

namespace Ares\Models\Core;

use Ares\Modules\Core\Database\ModelCore;

class FrameworkRoutesModel extends ModelCore {

    public $routeID;
    public $routeName;
    public $routePath;
    public $routeMethod;
    public $routeClass;
    public $isAuthRequired;

    public function __construct($dbJson)
    {
        parent::init($dbJson);
    }
}