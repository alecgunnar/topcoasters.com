<?php

namespace Application\Form;

class Forums_Moderate_DeleteTopic extends \Maverick\Lib\Form {
    private $topic = null;

    public function __construct($topic) {
        $this->topic = $topic;

        parent::__construct();
    }

    public function build() {
        $this->setName('deleteTopic');
        $this->setTpl('Standard');
        $this->renderFieldsWithFormTpl();

        $this->addField('Input_Password', 'password')
            ->setLabel('Enter your password to delete this topic')
            ->required();

        $this->addField('Input_Submit', 'submit')
            ->setValue('Delete Topic');
    }

    public function validate() {
        $model  = $this->getModel();
        $member = \Maverick\Lib\Output::getGlobalVariable('member');

        if(\Application\Lib\Members::getPasswordMd5($model->get('password'), $member->get('password_salt')) == $member->get('password_md5')) {
            return true;
        }

        $this->setFieldError('password', 'The password you entered was invalid.');
    }

    public function submit() {
        $model = $this->getModel();

        $forums = new \Application\Service\Forums;
        $topics = new \Application\Service\Topics;
        $posts  = new \Application\Service\Posts;

        $forum = $this->topic->getForum();

        $topics->delete($this->topic);
        $topics->clearOldMovedLinks($this->topic);

        $posts->deleteForTopic($this->topic->get('topic_id'));

        $forum->increase('num_topics', -1);
        $forum->increase('num_posts', -1 * $this->topic->get('num_replies'));

        $forums->determineMostRecentPost($forum);
        $forums->commitChanges($forum);

        if($this->topic->get('featured')) {
            $featuredTopicsCache = new \Maverick\Lib\Cache('featuredTopics');
            $featuredTopicsCache->clear();
        }

        return true;
    }
}