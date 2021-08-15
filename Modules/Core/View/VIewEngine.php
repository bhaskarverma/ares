<?php

namespace Ares\Modules\Core\View;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class ViewEngine {

        private $module = null;
        private $twig = null;
        private $template = null;
        private $data = [];

        public function __construct($module)
        {
            $this->module = $module;
            $loader = new FilesystemLoader('/Views/'.$this->module.'/');
            $this->twig = new Environment($loader);
        }

        public function updateModule($module)
        {
            $this->module = $module;
            $loader = new FilesystemLoader('/Views/'.$this->module.'/');
            $this->twig = new Environment($loader);
        }

        public function loadTemplate($template)
        {
            $this->template = $template;
        }

        public function loadData($data = [])
        {
            $this->data = $data;
        }

        public function render()
        {
            return $this->twig->render($this->template.'.twig', $this->data);
        }
}