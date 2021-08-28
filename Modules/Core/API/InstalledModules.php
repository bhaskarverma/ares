<?php

namespace Ares\Modules\Core\API;

use Ares\Modules\Core\View\ViewEngine;
use Ares\Models\Core\FrameworkModulesModel;
use Ares\Models\Core\FrameworkModuleSectionsModel;

class InstalledModules {

    private $engine = null;
    private $session = null;
    private $result = null;
    private $request = null;
    private $response  = null;

    public function __construct($request, $response)
    {
        $this->engine = new ViewEngine('Core');
        $this->engine->loadTemplate("NavMultiple");
        $this->result = "";
        $this->request = $request;
        $this->response = $response;
    }

    public function getModules()
    {
        $modulesModel = new FrameworkModulesModel();

        $modules = $modulesModel->find()->all();

        foreach($modules as $module)
        {
            $navsModel = new FrameworkModuleSectionsModel();
            $navs = $navsModel->find()->where(['sectionModule' => $module->moduleID])->all();

            $moduleTemplateData = [];
            $moduleTemplateData['val'] = $module->moduleName;
            $moduleTemplateData['navs'] = [];
            
            foreach($navs as $nav)
            {
                $navData = [];
                $navData['url'] = $nav->sectionPath;
                $navData['val'] = $nav->sectionName;
                array_push($moduleTemplateData['navs'], $navData);
            }

            $this->engine->loadData($moduleTemplateData);
            $this->result .= $this->engine->render();
        }

        $this->response->getBody()->write($this->result);
        return $this->response;
    }
}