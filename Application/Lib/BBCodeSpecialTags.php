<?php

namespace Application\Lib;

class BBCodeSpecialTags {
    private static function removeNewLines($str) {
        return preg_replace('~[\r\n]~', '', $str);
    }

    public static function checkTag_list($option, $content, $html) {
        $tag     = $option == 'num' ? 'ol' : 'ul';
        $content = self::removeNewLines(preg_replace('~\[\*\](.+)~', '<li>$1</li>', $content));

        return str_replace(array('{tag}', '{content}'), array($tag, $content), $html);
    }

    public static function checkTag_quote($option, $content, $html) {
        $title        = 'Quote';
        $quoteContent = '';
        $quoting      = '';

        if($option) {
            if(is_numeric($option)) {
                $posts = new \Application\Service\Posts;
                $post  = $posts->get($option);
    
                if($post) {
                    $quoting = '<a href="' . $post->getUrl() . '">Go to Post &rarr;</a>';
                }
            } else {
                $quoting = 'By: ' . $option;
            }
        }

        $quoteContent = '<div class="quoting">' . $quoting . '</div><h3>' . $title . '</h3>' . $content;

        return str_replace('{content}', $quoteContent, $html);
    }

    public static function checkTag_title($option, $content, $html) {
        $size   = '1.1';
        $weight = '100';

        if(!$option || !is_numeric($option) || $option === '1') {
            $size   = '1.5';
            $weight = 'bold';
        } elseif($option == 2) {
            $size   = '1.25';
            $weight = '400';
        }

        return str_replace(array('{option}', '{content}'), array('font-size: ' . $size . 'em; font-weight: ' . $weight . ';', $content), $html);
    }

    public static function checkTag_code($option, $content, $html) {
        $content = str_replace(' ', '&nbsp;', $content);

        return str_replace(array('{option}', '{content}'), array($option, $content), $html);
    }

    public static function checkTag_img($option, $content, $html) {
        if(@($img = getimagesize($content))) {
            list($width, $height) = $img;

            $checkDimension = function($check, $other, $max) {
                if($check > $max) {
                    $diff   = $max / $check;
                    $check  = $max;
                    $other *= $diff;
                }

                return array($check, $other);
            };

            list($width, $height) = $checkDimension($width, $height, \Maverick\Maverick::getConfig('Posting')->get('images')->get('maxWidth'));
            list($height, $width) = $checkDimension($height, $width, \Maverick\Maverick::getConfig('Posting')->get('images')->get('maxHeight'));

            $tag = new \Maverick\Lib\Builder_Tag('img');
            $tag->addAttributes(array('src'    => $content,
                                      'width'  => $width,
                                      'height' => $height,
                                      'title'  => 'Posted Image'));

            return $tag->render();
        }

        $tag = new \Maverick\Lib\Builder_Tag('div');
        $tag->addAttributes(array('style' => 'background: #FFF; border: 1px solid #F00; padding: 5px; display: inline-block;'))
            ->addContent('Broken Image');

        return $tag->render();
    }
}