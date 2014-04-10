<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Admin_Members extends \Maverick\Lib\Controller {
    public function main() {
        Output::setPageTitle('Viewing All Members');

        $members    = new \Application\Service\Members;
        $allMembers = $members->getAll();

        $this->setVariable('members', $allMembers);
    }
}