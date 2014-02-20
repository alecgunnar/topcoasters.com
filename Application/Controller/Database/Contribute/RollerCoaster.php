<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Database_Contribute_RollerCoaster extends \Maverick\Lib\Controller {
	public function main($what='', $parkIdIn=0) {
		\Application\Lib\Members::checkUserIsMod(true);

	    $title       = 'Adding a Roller Coaster';
        $editCoaster = null;
        $parkId      = 0;

        if(is_numeric($what)) {
            $rollerCoasters = new \Application\Service\RollerCoasters;
            $coaster        = $rollerCoasters->get(intval($what));

            if($coaster) {
                $title       = 'Editing ' . $coaster->getName() . ' at ' . $coaster->getPark()->getName();
                $editCoaster = $coaster;
            }
        } elseif($what === 'park') {
            $parkId = intval($parkIdIn);
        }

        Output::setPageTitle($title);

        $rollerCoasterForm = new \Application\Form\Database_Contribute_RollerCoaster($editCoaster, $parkId);

        if($rollerCoasterForm->isSubmissionValid()) {
            if(($coaster = $rollerCoasterForm->submit()) instanceof \Application\Model\RollerCoaster) {
                \Maverick\Lib\Http::location($coaster->getUrl(), 'This roller coaster has been saved');
            }
        }

        $this->setVariable('form', $rollerCoasterForm->render());
	}
}