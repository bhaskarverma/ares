<?php

namespace Ares\Models\Core;

use Ares\Modules\Core\Database\ModelCore;

class FrameworkModulesModel extends ModelCore {

    public $moduleID;
    public $moduleName;
    public $moduleVersion;

    public function __construct()
    {
        parent::init();
    }
}