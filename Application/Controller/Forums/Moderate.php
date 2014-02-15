<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Forums_Moderate extends \Maverick\Lib\Controller {
    /**
     * The topic being worked with
     *
     * @var \Application\Model\Topic | null
     */
    private $topic = null;

    public function main($topicId='', $do='') {
        \Application\Lib\Members::checkUserIsMod(true);

        $topics      = new \Application\Service\Topics;
        $this->topic = $topics->get(intval($topicId));

        if(!$this->topic) {
            \Application\Lib\Utility::showError('The URL you have followed is invalid.');
        }

        $commit = true;

        switch($do) {
            case 'stick-it':
                $this->stickTopic();
                break;
            case 'close-it':
                $this->closeTopic();
                break;
            case 'feature-it':
                $this->featureTopic();
                break;
            case "delete-moved-to":
                $this->deleteMovedTo();
                break;
            default:
                $commit = false;
        }

        if($commit) {
            $topics->commitChanges($this->topic);

            \Maverick\Lib\Http::location('/forums/moderate/' . $this->topic->get('topic_id'), 'Moderation Complete!');
        }

        $this->handleMoveTopic();
        $this->handleDeleteTopic();

        $this->setVariable('topic', $this->topic);
    }

    private function stickTopic() {
        $this->topic->toggle('pinned');
    }

    private function closeTopic() {
        $this->topic->toggle('closed');
    }

    private function featureTopic() {
        $this->topic->toggle('featured');

        $featuredTopicsCache = new \Maverick\Lib\Cache('featuredTopics');
        $featuredTopicsCache->clear();
    }

    private function handleMoveTopic() {
        $moveTopicForm = new \Application\Form\Forums_Moderate_MoveTopic($this->topic);

        if($moveTopicForm->isSubmissionValid()) {
            if($moveTopicForm->submit()) {
                \Maverick\Lib\Http::location('/forums/moderate/' . $this->topic->get('topic_id'), 'This topic has been moved.');
            }
        }

        $this->setVariable('moveTopic', $moveTopicForm);
    }

    private function handleDeleteTopic() {
        $deleteTopicForm = new \Application\Form\Forums_Moderate_DeleteTopic($this->topic);

        if($deleteTopicForm->isSubmissionValid()) {
            if($deleteTopicForm->submit()) {
                \Maverick\Lib\Http::location('/forums', 'This topic has been deleted.');
            }
        }

        $this->setVariable('deleteTopic', $deleteTopicForm);
    }

    private function deleteMovedTo() {
        $topics = new \Application\Service\Topics;
        $topics->clearOldMovedLinks($this->topic);
    }
}