<?php

namespace Application\Controller;

use Maverick\Lib\Output;

class AppRoot {
    public function preload() {
        Output::addJsFile('main');
        Output::addCssFile('main');
        
        Output::setGlobalVariable('title_suffix', ' - Top Coasters');
        Output::setGlobalVariable('url', \Maverick\Maverick::getConfig('System')->get('url'));
        Output::setGlobalVariable('redirect_msg', \Maverick\Lib\Http::getRedirectMessage());
        Output::setGlobalVariable('search_box_text', 'Search Top Coasters');

        $this->checkForAjaxRequest();
        $this->setBuildId();
        $this->setUserStatus();
    }

    private function checkForAjaxRequest() {
        $post = new \Maverick\Lib\Model_Input($_POST);

        if($post->get('ajaxRequest')) {
            Output::setGlobalVariable('ajaxRequest', true);
        }
    }

    private function setBuildId() {
        $buildIdFile = ROOT_PATH . 'BUILD_ID';
        $buildId     = 'heythere';

        if(file_exists($buildIdFile)) {
            $buildId = file_get_contents($buildIdFile);
        }

        Output::setGlobalVariable('build_id', $buildId);
    }

    private function setUserStatus() {
        $member = \Application\Lib\Members::getMember();

        Output::setGlobalVariable('member', $member);

        if(!array_key_exists('go_back_to', $_SESSION)) {
            $_SESSION['go_back_to'] = '/';
        }
    }

    public function postload() {
        $pageId = lcfirst(str_replace('_', '-', \Maverick\Lib\Router::getController(true)));
        Output::setGlobalVariable('page_id', strtolower($pageId));

        $controller = \Maverick\Lib\Router::getController(true);

        $dontGoBackTo  = array('Account', 'SignIn', 'CreateAnAccount', 'Connect', 'Errors', 'ForgotPassword', 'SignOut');
        $expController = explode('_', $controller);

        if(!array_key_exists($expController[0], array_flip($dontGoBackTo)) || $controller == 'Errors_Page' && !Output::getGlobalVariable('ajaxRequest')) {
            $_SESSION['go_back_to'] = \Maverick\Lib\Router::getUri()->getUri();
        }
    }
}