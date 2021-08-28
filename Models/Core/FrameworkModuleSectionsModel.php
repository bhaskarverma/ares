<?php

namespace Ares\Models\Core;

use Ares\Modules\Core\Database\ModelCore;

class FrameworkModuleSectionsModel extends ModelCore {

    public $sectionID;
    public $sectionName;
    public $sectionModule;
    public $sectionPath;

    public function __construct()
    {
        parent::init();
    }
}