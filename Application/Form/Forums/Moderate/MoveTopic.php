<?php

namespace Application\Form;

class Forums_Moderate_MoveTopic extends \Maverick\Lib\Form {
    private $topic = null;

    public function __construct($topic) {
        $this->topic = $topic;

        parent::__construct();
    }

    public function build() {
        $this->setName('moveTopic');
        $this->setTpl('Standard');
        $this->renderFieldsWithFormTpl();

        $this->addField('Input_Checkbox', 'add_moved_link')
            ->setLabel('Add a "Moved to Link"')
            ->setDescription('This will add a link in the current forum of this topic to its new location.')
            ->setValue('1')
            ->checked();

        $forums       = new \Application\Service\Forums;
        $forumOptions = $forums->getForMove($this->topic->getForum()->get('forum_id'));

        $this->addField('Select', 'new_forum')
            ->setLabel('New Forum')
            ->addOption('0', 'Choose a new forum for this topic...')
            ->addOptions($forumOptions)
            ->required();

        $this->addField('Input_Submit', 'submit')
            ->setValue('Move Topic');
    }

    public function validate() { }

    public function submit() {
        $model = $this->getModel();

        $forums = new \Application\Service\Forums;
        $topics = new \Application\Service\Topics;

        $newForum = $forums->get($model->get('new_forum'));
        $oldForum = $this->topic->getForum();

        $newForum->increase('num_topics');
        $newForum->increase('num_posts', $this->topic->get('num_replies'));

        $oldForum->increase('num_topics', -1);
        $oldForum->increase('num_posts', -1 * $this->topic->get('num_replies'));

        $this->topic->update('forum_id', $newForum->get('forum_id'));

        $topics->commitChanges($this->topic);

        $forums->determineMostRecentPost($newForum);
        $forums->determineMostRecentPost($oldForum);

        $forums->commitChanges($oldForum);
        $forums->commitChanges($newForum);

        if($model->get('add_moved_link')) {
            $topics->clearOldMovedLinks($this->topic);

            $movedTo = $topics->create($this->topic->getName(), $oldForum);

            $movedTo->update('moved_to', $this->topic->get('topic_id'));

            $topics->commitChanges($movedTo);
        }

        return true;
    }
}