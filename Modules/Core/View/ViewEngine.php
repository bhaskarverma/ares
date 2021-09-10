<?php

namespace Ares\Modules\Core\View;

class ViewEngine {

        private $resp = null;

        public function __construct()
        {
            $this->resp = file_get_contents("Views/index.html");
        }

        public function render()
        {
            return $this->resp;
        }
}