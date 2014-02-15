<?php

namespace Application\Form;

class ExchangeFileUpload extends \Maverick\Lib\Form {
    private $categories = array();
    private $category   = 0;
    private $file       = null;

    public function __construct($categories, $category, $file=null) {
        $this->categories = $categories;
        $this->category   = $category;
        $this->file       = $file;

        if(!is_null($file)) {
            $this->category = $file->get('category');
        }

        parent::__construct();
    }

    public function build() {
        $this->setName('exchangeFileUpload');
        $this->setTpl('Standard');
        $this->renderFieldsWithFormTpl();

        $this->allowFileUploads();

        $onlyIfNeeded = '';

        if(!is_null($this->file)) {
            $onlyIfNeeded = '<br /><strong style="color: #F00;">Only choose a file if you want to replace the current one!</strong>';
        }

        $name = $this->addField('Input_Text', 'name')
            ->setLabel('File Name')
            ->setMaxLength(50)
            ->required('You must name this file.');

        $this->addField('Input_File', 'file')
            ->setLabel('Track File')
            ->setDescription('Valid file types: <strong>' . $this->categories[$this->category][2] . '</strong> Max file size: <strong>' . $this->categories[$this->category][3] . '</strong> MB' . $onlyIfNeeded);

        $this->addField('Input_File', 'image')
            ->setLabel('Screenshot')
            ->setDescription('You may upload a screenshot of the park/coaster you have created.<br /><strong>This must be an in-game screenshot</strong>, if you choose to upload a picture.' . $onlyIfNeeded);

        if(!is_null($this->file) && $this->file->get('screenshot')) {
            $this->addField('Input_Checkbox', 'removeScreenshot')
                ->setLabel('Remove Screenshot')
                ->setDescription('Check the box to delete the screenshot for this file.');
        }

        $description = $this->addField('Editor', 'description')
            ->setLabel('Description')
            ->setDescription(\Maverick\Maverick::getConfig('Posting')->get('postDescription'))
            ->required('You must enter a description for this file.');

        $submit = $this->addField('Input_Submit', 'submit')
            ->setValue('Upload File');

        if(!is_null($this->file)) {
            $name->setValue($this->file->getName());
            $description->setValue($this->file->get('description'));
            $submit->setValue('Save Changes');
        }
    }

    private function checkFileSize($check, $max) {
        if((($check / 1024) / 1024) > $max) {
            return true;
        }

        return false;
    }

    public function validate() {
        $file = $this->getFilesModel()->get('file');

        if($file->get('name')) {
            $types = explode(',', $this->categories[$this->category][2]);
            array_walk($types, function(&$value) { $value = trim(str_replace('.', '', $value)); });
            $okTypes = array_flip($types);
    
            $expType = explode('.', $file->get('name'));
    
            if(!array_key_exists($expType[count($expType) - 1], $okTypes)) {
                $this->setFieldError('file', 'The file you chose was not a valid type.');
            }
    
            if($this->checkFileSize($file->get('size'), $this->categories[$this->category][3])) {
                $this->setFieldError('file', 'The file you chose was too large.');
            }
        } elseif(is_null($this->file)) {
            $this->setFieldError('file', 'You must choose a file to upload.');
        }

        $image = $this->getFilesModel()->get('image');

        if($image->get('name')) {
            if(strpos($image->get('type'), 'image') !== 0) {
                $this->setFieldError('image', 'The screenshot file you chose was not a valid type.');
            }
    
            if($this->checkFileSize($image->get('size'), 1)) {
                $this->setFieldError('image', 'The screenshot image you chose was too large.');
            }
        }
    }

    public function submit() {
        $input = $this->getModel();
        $files = $this->getFilesModel();

        $file  = '';
        $image = '';

        if($files->get('file')->get('name')) {
            if(!is_null($this->file)) {
                $trackFile = \Maverick\Maverick::getConfig('Exchange')->get('paths')->get('files') . $this->file->get('file');

                if(file_exists($trackFile)) {
                    unlink($trackFile);
                }
            }

            $file  = \Application\Lib\Uploads::uploadFile($files->get('file'), \Maverick\Maverick::getConfig('Exchange')->get('paths')->get('files'));
        }

        if(is_null($this->file)) {
            if(!$file) {
                return false;
            }
        } else {
            if(($this->file->get('screenshot') && $files->get('image')->get('name')) || $input->get('removeScreenshot')) {
                $screenshotFile = \Maverick\Maverick::getConfig('Exchange')->get('paths')->get('screenshots') . $this->file->get('screenshot');

                if(file_exists($screenshotFile)) {
                    unlink($screenshotFile);
                }
            }
        }

        if($files->get('image')->get('name')) {
            $image = \Application\Lib\Uploads::uploadFile($files->get('image'), \Maverick\Maverick::getConfig('Exchange')->get('paths')->get('screenshots'), md5($file ?: $this->file->get('file')));
        }

        $exchange = new \Application\Service\Exchange;

        if(is_null($this->file)) {
            return $exchange->create($this->category, $input->get('name'), $file, $image, $input->get('description'));
        } else {
            $this->file->update(array('name'        => $input->get('name'),
                                      'file'        => $file ?: $this->file->get('file'),
                                      'screenshot'  => $input->get('removeScreenshot') ? '' : ($image ?: $this->file->get('screenshot')),
                                      'description' => $input->get('description')));

            return $exchange->commitChanges($this->file);
        }
    }
}