<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Disconnect extends \Maverick\Lib\Controller {
    public function main() {
        $members = new \Application\Service\Members;

        $member = $members->get();
    }
}