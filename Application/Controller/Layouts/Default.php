<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Layouts_Default extends \Maverick\Lib\Controller {
    public function main($variables) {
        $this->setVariables($variables);

        $get = new \Maverick\Lib\Model_Input($_GET);

        $this->setVariable('search_box_value', $get->get('q'));
    }
}