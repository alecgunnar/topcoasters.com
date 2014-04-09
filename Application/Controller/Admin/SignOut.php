<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Admin_SignOut extends \Maverick\Lib\Controller {
    public function main() {
        if(array_key_exists('admin_key', $_SESSION)) {
            unset($_SESSION['admin_key']);
        }

        \Maverick\Lib\Http::location('/admin', 'You have been signed out.');
    }
}