<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class TrackRecord extends \Maverick\Lib\Controller {
    private $newFavorite = null;

    public function main($what='', $action='', $idIn=0) {
        Output::setPageTitle('Track Record');

        \Application\Lib\Members::checkUserStatus(true);

        $showCoasters = false;
        $showParks    = false;

        if(is_numeric($idIn)) {
            $id = intval($idIn);

            if($what === 'parks') {
                $showParks = true;
            } else {
                $showCoasters = true;
            }

            switch($action) {
                case "save":
                    $this->saveRecord($showCoasters, $id);
                    break;
                case "remove":
                    $this->removeRecord($showCoasters, $id);
                    break;
                case "add":
                    $this->addRecord($showCoasters, $id);
                    break;
                case "edit":
                    $this->setVariable('edit_id', $id);
            }
        }

        $this->showAll($showCoasters, $showParks);
    }

    private function showAll($showCoasters, $showParks) {
        $favorites   = new \Application\Service\Favorites;
        $coasterFavs = $favorites->getCoastersForMember(Output::getGlobalVariable('member')->get('member_id'));
        $parkFavs    = $favorites->getParksForMember(Output::getGlobalVariable('member')->get('member_id'));

        $coasters = Output::getTplEngine()->getTemplate('Blocks/TrackRecordCoasters', array('favorites' => $coasterFavs));
        $parks    = Output::getTplEngine()->getTemplate('Blocks/TrackRecordParks', array('favorites' => $parkFavs));

        $tabs = array('Roller Coasters' => array($showCoasters, $coasters),
                      'Amusement Parks' => array($showParks, $parks));

        $this->setVariable('tabs', $tabs);
    }

    private function addRecord($isCoaster, $id) {
        $favorites = new \Application\Service\Favorites;

        if($isCoaster) {
            $favorite = $favorites->getCoastersForMember(Output::getGlobalVariable('member')->get('member_id'), $id);
            $error    = 'favoriteCoasterExists';
            $dbColumn = 'coaster_id';

            $rollerCoasters = new \Application\Service\RollerCoasters;
            $coaster        = $rollerCoasters->get($id);

            if(array_key_exists($coaster->get('status'), array_flip(array('building', 'rumored')))) {
                $this->setVariable('coasterCannotBeAdded', true);

                return;
            }
        } else {
            $favorite = $favorites->getParksForMember(Output::getGlobalVariable('member')->get('member_id'), $id);
            $error    = 'favoriteParkExists';
            $dbColumn = 'park_id';
        }

        if($favorite) {
            $this->setVariable($error, true);

            return;
        }

        $newFavorite = $favorites->put(array('member_id' => Output::getGlobalVariable('member')->get('member_id'),
                                             $dbColumn   => $id));

        \Maverick\Lib\Http::location($newFavorite->getUrl(), 'Your track record has been updated.');
    }

    private function saveRecord($isCoaster, $id) {
        $favorites = new \Application\Service\Favorites;
        $favorite  = $favorites->get($id);

        if($favorite) {
            $post = new \Maverick\Lib\Model_Input($_POST);

            if($isCoaster) {
                $favorite->update('times_ridden', $post->get('timesRidden'));

                if($post->get('rating') != $favorite->get('rating')) {
                    $update  = array();
                    $change  = $post->get('rating') - $favorite->get('rating');
                    $coaster = $favorite->getCoaster();

                    $update['total_rates'] = $coaster->get('total_rates') + $change;
                    $update['rates']       = $coaster->get('rates');

                    if(!$favorite->get('rating') || !$update['rates']) {
                        $update['rates'] += 1;
                    }

                    $update['rating'] = $update['total_rates'] / $update['rates'];

                    $coaster->update($update);

                    $rollerCoasters = new \Application\Service\RollerCoasters;
                    $rollerCoasters->commitChanges($coaster);

                    $favorite->update('rating', $post->get('rating'));

                    $topCoastersCache = new \Maverick\Lib\Cache('topRatedCoasters');
                    $topCoastersCache->clear();
                }
            } else {
                if($post->get('rating') != $favorite->get('rating')) {
                    $update  = array();
                    $change  = $post->get('rating') - $favorite->get('rating');
                    $park    = $favorite->getPark();

                    $update['total_rates'] = $park->get('total_rates') + $change;
                    $update['rates']       = $park->get('rates');

                    if(!$favorite->get('rating') || !$update['rates']) {
                        $update['rates'] += 1;
                    }

                    $update['rating'] = $update['total_rates'] / $update['rates'];

                    $park->update($update);

                    $amusementParks = new \Application\Service\AmusementParks;
                    $amusementParks->commitChanges($park);

                    $favorite->update('rating', $post->get('rating'));

                    $topCoastersCache = new \Maverick\Lib\Cache('topRatedParks');
                    $topCoastersCache->clear();
                }
            }

            $favorites->commitChanges($favorite);
            Output::printJson(array('status' => 'ok'));
        }
    }

    private function removeRecord($isCoaster, $id) {
        $favorites = new \Application\Service\Favorites;
        $favorite  = $favorites->get($id);

        if($favorite) {
            if($isCoaster) {
                if($favorite->get('rating')) {
                    $update  = array();
                    $coaster = $favorite->getCoaster();
    
                    $update['total_rates'] = $coaster->get('total_rates') - $favorite->get('rating');
                    $update['rates']       = $coaster->get('rates') - 1;
    
                    if($update['rates']) {
                        $update['rating'] = $update['total_rates'] / $update['rates'];
                    } else {
                        $update['rating'] = 0;
                    }
    
                    $coaster->update($update);
    
                    $rollerCoasters = new \Application\Service\RollerCoasters;
                    $rollerCoasters->commitChanges($coaster);
    
                    $topCoastersCache = new \Maverick\Lib\Cache('topRatedCoasters');
                    $topCoastersCache->clear();
                }
            } else {
                if($favorite->get('rating')) {
                    $update = array();
                    $park   = $favorite->getPark();
    
                    $update['total_rates'] = $park->get('total_rates') - $favorite->get('rating');
                    $update['rates']       = $park->get('rates') - 1;
    
                    if($update['rates']) {
                        $update['rating'] = $update['total_rates'] / $update['rates'];
                    } else {
                        $update['rating'] = 0;
                    }
    
                    $park->update($update);
    
                    $amusementParks = new \Application\Service\AmusementParks;
                    $amusementParks->commitChanges($park);

                    $topCoastersCache = new \Maverick\Lib\Cache('topRatedParks');
                    $topCoastersCache->clear();
                }
            }

            $favorites->delete($favorite);
            Output::printJson(array('status' => 'ok'));
        }
    }
}