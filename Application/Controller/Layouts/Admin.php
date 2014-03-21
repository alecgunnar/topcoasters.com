<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Layouts_Admin extends \Maverick\Lib\Controller {
    public function main($variables) {
        $this->setVariables($variables);
    }
}