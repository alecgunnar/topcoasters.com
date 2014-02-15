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
            case "delete":
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
            if($topic->get('closed') && !Output::getGlobalVariable('member')->get('is_mod')) {
                \Application\Lib\Utility::showError('This topic is closed, you may not post a reply.');
            }

            $title    = 'Posting a Reply';
            $redirect = 'Your reply has been posted.';
        } else {
            if($what == 'delete') {
                $this->doDelete($post);
            } else {
                if($post->getTopic()->get('closed') && !Output::getGlobalVariable('member')->get('is_mod')) {
                    \Application\Lib\Utility::showError('The topic this post is in has been closed, you may not edit the post.');
                }
    
                $title    = 'Editing a Post';
                $redirect = 'Your changes have been saved.';
            }
        }

        Output::setPageTitle($title);

        $obj = $forum ?: ($topic ?: $post);

        $postTopicForm = new \Application\Form\Forums_Post($obj, $replyTo);

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

    private function doDelete($post) {
        \Application\Lib\Members::checkUserIsMod(true);

        if($post->get('is_first_post')) {
            \Application\Lib\Utility::showError('This post cannot be deleted.');
        }

        $forums = new \Application\Service\Forums;
        $topics = new \Application\Service\Topics;
        $posts  = new \Application\Service\Posts;

        $posts->delete($post);

        $post->getTopic()->increase('num_replies', -1);
        $post->getTopic()->getForum()->increase('num_posts', -1);

        $topics->determineMostRecentPost($post->getTopic());
        $forums->determineMostRecentPost($post->getTopic()->getForum());

        \Maverick\Lib\Http::location($post->getTopic()->getUrl(), 'The post has been deleted.');
    }
}