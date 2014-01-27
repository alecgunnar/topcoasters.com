<?php

namespace Application\Controller;

use \Maverick\Lib\Output;

class Forums_Post extends \Maverick\Lib\Controller {
    public function main($what='', $id=0) {
        \Application\Lib\Members::checkUserStatus(true);

        $forum   = null;
        $topic   = null;
        $post    = null;
        $replyTo = null;

        switch($what) {
            case "topic":
                $forums = new \Application\Service\Forums;
                $forum  = $forums->get(intval($id));
                break;
            case "reply":
                $topics = new \Application\Service\Topics;
                $topic  = $topics->get(intval($id));
                break;
            case "reply-to":
                $posts   = new \Application\Service\Posts;
                $replyTo = $posts->get(intval($id));
                $topic   = $replyTo->getTopic();
                break;
            case "edit":
                $posts = new \Application\Service\Posts;
                $post  = $posts->get(intval($id));
                break;
            default:
                \Application\Lib\Utility::showError('The URL you have followed is invalid. Please go back and try again.');
        }

        if(!$forum && !$topic && !$post) {
            \Maverick\Lib\Router::throw404();
        }

        $title    = '';
        $redirect = '';

        if($forum) {
            $title    = 'Posting a Topic';
            $redirect = 'Your topic has been posted.';
        } elseif($topic) {
            $title    = 'Posting a Reply';
            $redirect = 'Your reply has been posted.';
        } else {
            $title    = 'Editing a Post';
            $redirect = 'Your changes have been saved.';
        }

        Output::setPageTitle($title);

        $obj = $forum ?: ($topic ?: $post);

        $postTopicForm = new \Application\Form\ForumPost($obj, $replyTo);

        if($postTopicForm->isSubmissionValid()) {
            if(($newObj = $postTopicForm->submit()) instanceof \Application\Model\Standard) {
                \Maverick\Lib\Http::location($newObj->getUrl(), $redirect);
            } else {
                $this->setVariable('postingError', true);
            }
        }

        $this->setVariable('form', $postTopicForm->render());
        $this->setVariable('obj', $obj);
    }
}