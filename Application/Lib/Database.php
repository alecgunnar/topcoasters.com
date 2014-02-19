<?php

namespace Application\Lib;

class Database {
    public static function sortResults($results, $minimizedData) {
        $sortedResults = array();

        foreach($results as $n => $result) {
            if($result->get('name')) {
                preg_match('~(?:the )?([a-z0-9 ]+)~i', $result->get('name'), $matches);

                if(is_numeric($matches[1][0])) {
                    $sortedResults[26][$n] = $matches[1];
                } else {
                    $sortedResults[strtolower($matches[1][0])][$n] = $matches[1];
                }
            } else {
                $sortedResults[27][$n] = $result;
            }
        }

        ksort($sortedResults);

        foreach($sortedResults as $key => $group) {
            asort($sortedResults[$key]);

            foreach($sortedResults[$key] as $num => $name) {
                $sortedResults[$key][$num] = $results[$num]->minimize($minimizedData);
            }
        }

        return $sortedResults;
    }

    public static function formatDate($date) {
        $expDate = explode('/', $date);

        if(($ct = count($expDate)) >= 2) {
            $month = \DateTime::createFromFormat('n-j', $expDate[0] . '-1');

            if($ct == 2) {
                $formattedDate = $month->format('F') . ' ' . $expDate[1];
            } else {
                $formattedDate = $month->format('F') . ' ' . $expDate[1] . ', ' . $expDate[2];
            }
        } else {
            $formattedDate = $date;
        }

        return $formattedDate;
    }

    public static function appendUnitsLength($quantity) {
        $measures = 'english';

        if(\Application\Lib\Members::checkUserStatus()) {
            $measures = \Maverick\Lib\Output::getGlobalVariable('member')->get('measures');
        }

        $units    = '';

        if($measures == 'english') {
            $units = 'ft';
        } else {
            $units     = 'm';
            $quantity *= 0.3048;
        }

        return preg_replace('~\.0+~', '', number_format(round($quantity, 1), 1)) . ' ' . $units;
    }

    public static function appendUnitsSpeed($quantity) {
        $measures = 'english';

        if(\Application\Lib\Members::checkUserStatus()) {
            $measures = \Maverick\Lib\Output::getGlobalVariable('member')->get('measures');
        }

        $units    = '';

        if($measures == 'english') {
            $units = 'mph';
        } else {
            $units     = 'km/h';
            $quantity *= 1.60934;
        }

        return preg_replace('~\.0+~', '', number_format(round($quantity, 1), 1)) . ' ' . $units;
    }
}