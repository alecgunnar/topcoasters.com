<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class SignOut extends \Maverick\Lib\Controller {
    public function main() {
        \Application\Lib\Members::endSession();
        \Application\Lib\Members::redirectToLast('You have been signed out.');
    }
}