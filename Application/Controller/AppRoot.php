<?php

namespace Application\Controller;

use Maverick\Lib\Output;

class AppRoot {
    public function preload() {
        Output::addJsFile('http://code.jquery.com/jquery-2.1.0.min.js');

        if(strpos(\Maverick\Lib\Router::getUri()->getPath(), 'admin') === 0) {
            Output::addCssFile('admin');
            Output::setPageLayout('Admin');

            if(array_key_exists('admin_key', $_SESSION)) {
                $this->setNavigation('admin');
            }
        } else {
            Output::addJsFile('main');
            Output::addCssFile('main');

            $this->setNavigation();
        }

        Output::setGlobalVariable('url', \Maverick\Maverick::getConfig('System')->get('url'));
        Output::setGlobalVariable('redirect_msg', \Maverick\Lib\Http::getRedirectMessage());
        Output::setGlobalVariable('search_box_text', 'Search Top Coasters');

        $this->checkForAjaxRequest();
        $this->setBuildId();
        $this->setUserStatus();

        if(file_exists(ROOT_PATH . 'OFFLINE') && !\Application\Lib\Members::checkUserIsMod()) {
            $path = \Maverick\Lib\Router::getUri()->getPath();

            if($path != 'sign-out' && $path != 'sign-in') {
                \Maverick\Lib\Router::forceLoadController('Errors_Offline');

                Output::setPageLayout('Offline');
                Output::setGlobalVariable('offline', true);
            }
        }
    }

    private function setNavigation($navigationLinksSet='public') {
        $mainNavigationLinks         = \Maverick\Maverick::getConfig('MainNavigation')->get($navigationLinksSet)->getAsArray();
        list($navLinks, $activeLink) = $this->processMenuLinks($mainNavigationLinks);

        Output::setGlobalVariable('mainNavigationLinks', $navLinks);

        if(count($mainNavigationLinks[$navLinks[$activeLink][0]])) {
            $sideNavigationLinks         = $mainNavigationLinks[$navLinks[$activeLink][0]][1];
            list($navLinks, $activeLink) = $this->processMenuLinks($sideNavigationLinks);

            Output::setGlobalVariable('sideNavigationLinks', $navLinks);
        }
    }

    private function processMenuLinks($links) {
        $urlPath = '/' . trim(\Maverick\Lib\Router::getUri()->getPath(), '/');

        $processedLinks = array();
        $activeLink     = null;

        if(count($links)) {
            foreach($links as $label => $path) {
                $checkPath = $path;

                if(is_array($path)) {
                    $checkPath = $path[0];
                }

                $processedLinks[] = array($label, $checkPath, false);

                if(strpos($urlPath, $checkPath) === 0) {
                    if(!is_null($activeLink)) {
                        $processedLinks[$activeLink][2] = false;
                    }

                    $activeLink                     = count($processedLinks) - 1;
                    $processedLinks[$activeLink][2] = true;
                }
            }
        }

        return array($processedLinks, $activeLink);
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