<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Database_Contribute_AmusementPark extends \Maverick\Lib\Controller {
	public function main($parkIn='') {
		\Application\Lib\Members::checkUserStatus(true);

	    $title    = 'Adding an Amusement Park';
        $parkId   = intval($parkIn);
        $editPark = null;

        if($parkId) {
            $amusementParks = new \Application\Service\AmusementParks;
            $park           = $amusementParks->get($parkId);

            if($park) {
                $title    = 'Editing ' . $park->get('name');
                $editPark = $park;
            }
        }

        Output::setPageTitle($title);

        $amusementParkForm = new \Application\Form\Database_Contribute_AmusementPark($editPark);

        if($amusementParkForm->isSubmissionValid()) {
            if(($park = $amusementParkForm->submit()) instanceof \Application\Model\AmusementPark) {
                \Maverick\Lib\Http::location($park->getUrl(), 'This amusement park has been saved');
            }
        }

        $this->setVariable('form', $amusementParkForm->render());
	}
}