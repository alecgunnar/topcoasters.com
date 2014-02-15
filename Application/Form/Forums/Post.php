<?php

namespace Application\Form;

class Forums_Post extends \Maverick\Lib\Form {
    private $obj     = null;
    private $replyTo = null;

    public function __construct($obj, $replyTo) {
        $this->obj     = $obj;
        $this->replyTo = $replyTo;

        parent::__construct();
    }

    public function build() {
        $this->setName('postMessage');
        $this->setTpl('Standard');
        $this->renderFieldsWithFormTpl();

        if($this->obj instanceof \Application\Model\Forum || ($this->obj instanceof \Application\Model\Post && $this->obj->get('is_first_post'))) {
            $title = '';

            if($this->obj instanceof \Application\Model\Post) {
                $title = $this->obj->getTopic()->getName();
            }

            $this->addField('Input', 'name')
                ->setLabel('Topic Title')
                ->required('You must enter a title for this topic.')
                ->setValue($title)
                ->setMaxLength(80);
        }

        $bbcode = new \Application\Lib\BBCode;

        $messageValue = $bbcode->decode($this->obj->get('message'));

        if(!is_null($this->replyTo)) {
            $messageValue = '[quote=' . $this->replyTo->get('post_id') . ']' . $bbcode->decode($this->replyTo->get('message')) . '[/quote]';
        }

        $this->addField('Editor', 'message')
            ->setLabel('Post Message')
            ->setDescription(\Maverick\Maverick::getConfig('Posting')->get('postDescription'))
            ->required('You must enter a message for this post.')
            ->setValue($messageValue);

        $btnText = 'Submit';

        switch(get_class($this->obj)) {
            case "Application\Model\Forum":
                $btnText = 'Post Topic';
                break;
            case "Application\Model\Topic":
                $btnText = 'Post Reply';
                break;
            default:
                $btnText = 'Save Changes';
                break;
        }

        $this->addField('Input_Submit', 'submit')
            ->setValue($btnText);
    }

    public function validate() { }

    public function submit() {
        $input = $this->getModel();

        if($this->obj instanceof \Application\Model\Forum) {
            $topics = new \Application\Service\Topics;
            $topic  = $topics->create($input->get('name'), $this->obj);

            if($topic instanceof \Application\Model\Topic) {
                $posts  = new \Application\Service\Posts;

                return $posts->create($input->get('message'), $topic, true);
            }
        } elseif($this->obj instanceof \Application\Model\Post) {
            if($this->obj->get('is_first_post')) {
                $topics = new \Application\Service\Topics;
                $topic  = $this->obj->getTopic();

                $topic->update('seo_title', \Application\Lib\Utility::generateSeoTitle($input->get('name'), $topic->get('topic_id')));
                $topic->update('name', $input->get('name'));

                $topics->commitChanges($topic);
            }

            $posts = new \Application\Service\Posts;

            $this->obj->update('message', $input->get('message'));

            return $posts->commitChanges($this->obj);
        } elseif($this->obj instanceof \Application\Model\Topic) {
            $posts = new \Application\Service\Posts;
            return $posts->create($input->get('message'), $this->obj, false);
        }

        return false;
    }
}