<?php

namespace Application\Task;

class UpdateTimesRidden implements Standard {
    public static function getData() {
        return array('title'       => 'Update Roller Coaster Number of Times Ridden',
                     'description' => 'This task will take the total number of times ridden from track records and set it to the times ridden value for each roller coaster.');
    }

    public static function run() {
        $coasters         = new \Application\Service\RollerCoasters;
        $favorites        = new \Application\Service\Favorites;
        $coastersToUpdate = array();

        $favs = $favorites->getAll();

        foreach($favs as $id => $data) {
            if($data->get('coaster_id')) {
                if(array_key_exists($data->get('coaster_id'), $coastersToUpdate)) {
                    $coastersToUpdate[$data->get('coaster_id')] += $data->get('times_ridden');
                } else {
                    $coastersToUpdate[$data->get('coaster_id')] = $data->get('times_ridden');
                }
            }
        }

        foreach($coastersToUpdate as $id => $timesRidden) {
            $coaster = $coasters->get($id);
            $coaster->update('times_ridden', $timesRidden);
            $coasters->commitChanges($coaster);
        }
    }
}