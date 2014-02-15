<?php

namespace Application\Form;

class Account_ProfilePicture extends \Maverick\Lib\Form {
    /**
     * The member being worked with
     *
     * @var \Application\Model\Member | null
     */
    private $member = null;

    public function __construct($member) {
        $this->member = $member;

        parent::__construct();
    }

    public function build() {
        $this->setName('uploadPicture');
        $this->setTpl('Account/UploadProfilePicture');
        $this->setAction('/account/profile-picture/upload');
        $this->allowFileUploads();

        $this->renderFieldsWithFormTpl();

        $this->addField('Input_File', 'profilePicture')
            ->setLabel('Choose a file');

        $this->addField('Input_Submit', 'saveFile')
            ->setValue('Upload Picture');
    }

    public function validate() {
        $file = $this->getFilesModel()->get('profilePicture');

        if(is_null($file) || !$file->get('name')) {
            $this->setFieldError('profilePicture', 'You must choose a picture');

            return;
        }

        $okTypes = array('gif', 'png', 'jpg', 'jpeg');

        $expFile = explode('.', $file->get('name'));

        if(!array_key_exists($expFile[count($expFile) - 1], array_flip($okTypes))) {
            $this->setFieldError('profilePicture', 'That file was not a valid type.');
        }

        list($width, $height) = getimagesize($file->get('tmp_name'));

        if($width > 200 || $height > 200) {
            $this->setFieldError('profilePicture', 'That image is too large.');
        }
    }

    public function submit() {
        $file     = $_FILES['uploadPicture'];
        $fileName = 'assets/uploads/profile_pictures/' . $this->member->get('member_id') . '.png';

        if(file_exists(PUBLIC_PATH . $fileName)) {
            unlink(PUBLIC_PATH . $fileName);
        }

        if(!move_uploaded_file($file['tmp_name']['profilePicture'], PUBLIC_PATH . $fileName)) {
            $this->setFieldError('profilePicture', 'There was an error uploading the picture.');

            return false;
        }

        imagepng(imagecreatefromstring(file_get_contents(PUBLIC_PATH . $fileName)), PUBLIC_PATH . $fileName);
    }
}