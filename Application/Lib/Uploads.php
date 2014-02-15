<?php

namespace Application\Lib;

class Uploads {
    public static function uploadFile($fileData, $destination, $useThisName='') {
        $expFileName = explode('.', $fileData->get('name'));
        $suffix      = '';
        $fileType    = '.' . $expFileName[count($expFileName) - 1];

        if(!$useThisName) {
            unset($expFileName[count($expFileName) - 1]);
    
            $fileName = implode('.', $expFileName);
    
            $n = 1;
    
            while(file_exists($destination . $fileName . $suffix . $fileType)) {
                $suffix = '-' . $n;
    
                $n++;
            }
        } else {
            $fileName = $useThisName;
        }

        $finalFileName = $fileName . $suffix . $fileType;

        if(move_uploaded_file($fileData->get('tmp_name'), $destination . $finalFileName)) {
            return $finalFileName;
        }

        return false;
    }
}