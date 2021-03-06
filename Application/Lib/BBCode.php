<?php

namespace Application\Lib;

class BBCode {
    /** 
     * The available BBCode tags and their HTML equivalents
     *
     * @var array
     */
    private $tags = array('b'       => '<strong>{content}</strong>',
                          'i'       => '<em>{content}</em>',
                          'u'       => '<span style="text-decoration: underline;">{content}</span>',
                          's'       => '<strike>{content}</strike>',
                          'sup'     => '<sup>{content}</sup>',
                          'sub'     => '<sub>{content}</sub>',
                          'left'    => '<div class="textLeft">{content}</div>',
                          'center'  => '<div class="textCenter">{content}</div>',
                          'right'   => '<div class="textRight">{content}</div>',
                          'list'    => '<{tag}>{content}</{tag}>',
                          'img'     => '<img src="{content}" />',
                          'url'     => '<a href="{option}" target="_blank">{content}</a>',
                          'youtube' => '<iframe width="560" height="315" src="//www.youtube.com/embed/{content}" frameborder="0" allowfullscreen></iframe>',
                          'quote'   => '<div class="quote">{content}</div>',
                          'title'   => '<div style="margin-bottom: 5px; {option}">{content}</div>',
                          'code'    => '<div class="code">{content}</div>');

    /**
     * Tags which should not be immediately followed by a new line
     *
     * @var array
     */
    private $noNewLinesAround = array('left', 'center', 'right', 'list', 'quote', 'title');

    /**
     * Tags which will not have nested bbcodes parsed
     *
     * @var array
     */
    private $noNestedTags = array('code');

    /**
     * The tree of the tags
     *
     * @var array
     */
    private $nodes = array();

    /**
     * Current index
     *
     * @var integer
     */
    private $currentNodeIndex = 0;

    /**
     * The current node in the tree
     *
     * @var mixed
     */
    private $node = null;

    /**
     * The current state of the tokenizer
     *
     * @var integer
     */
    private $state = 0;

    /**
     * The characters making up the opening tag
     *
     * @var string
     */
    private $openTag = '';

    /**
     * The option for the current tag
     *
     * @var string
     */
    private $option = '';

    /**
     * The closing tag being checked
     *
     * @var string
     */
    private $closingTag = '';

    /** 
     * Calls methods which are needed to parse the BBCode message, then returns the parsed message
     *
     * @param  string  $str
     * @param  boolean $serialize=true
     * @return string
     */
    public function parse($str) {
        $str = $this->removeIllegalNewLines($str);

        $this->tokenize($str);
    }

    /**
     * Removes new lines from around certain tags that shouldn't have them
     *
     * @param  string $str
     * @return string
     */
    private function removeIllegalNewLines($str) {
        $allTags = implode('|', $this->noNewLinesAround);

        foreach($this->noNewLinesAround as $tag) {
            $str = preg_replace('~\[/' . $tag . '\](\r\n|\n){1}~is', '[/' . $tag . ']', $str);
        }

        return $str;
    }

    /**
     * Renders the bbcode from the serialized form
     *
     * @param string $serialized
     * @return string
     */
    public function render($serialized) {
        $this->parse($serialized);

        return $this->toHtml();
    }

    /**
     * Takes the node tree and creates a bbcode string for editing
     *
     * @param  string $serialized
     * @return string
     */
    public function decode($serialized) {
        if(($unserialized = @unserialize($serialized)) !== false) {
            $this->nodes = $unserialized;
        } else {
            return $serialized;
        }

        return $this->toBBCode();
    }

    /**
     * Converts the BBCode tree to HTML
     *
     * @param  string|null $content=null
     * @param  boolean     $dontPArse=false
     * @return string
     */
    private function toHtml($content=null, $dontParse=false) {
        if(is_null($content)) {
            $content = $this->nodes[0]->getContent();
        }

        $rendered = '';

        if(count($content)) {
            foreach($content as $c) {
                if(is_array($c)) {
                    $node = $this->nodes[$c[0]];

                    $dontParseChildren = false;

                    if(array_key_exists($node->getTag(), array_flip($this->noNestedTags)) || $dontParse) {
                        $dontParseChildren = true;
                    }

                    $option  = $node->getOption();
                    $content = $this->toHtml($node->getContent(), $dontParseChildren);

                    if($dontParse) {
                        $rendered = '[' . $node->getTag() . ($option ? '=' . $option : '') . ']' . $content . '[/' . $node->getTag() . ']';
                    } else {
                        $content       = trim($content);
                        $checkFunction = 'checkTag_' . $node->getTag();
            
                        if(method_exists(new BBCodeSpecialTags, $checkFunction)) {
                            $html = BBCodeSpecialTags::$checkFunction($option, $content, $this->tags[$node->getTag()]);
                        } else {
                            $html = str_replace(array('{option}', '{content}'), array($option, $content), $this->tags[$node->getTag()]);
                        }
    
                        $rendered .= $html;
                    }
                } else {
                    $rendered .= $c;
                }
            }
        }

        return $rendered;
    }

    /**
     * Converts the BBCode tree to BBCode
     *
     * @param  string|null $content=null
     * @param  boolean     $dontPArse=false
     * @return string
     */
    private function toBBCode($content=null, $dontParse=false) {
        if(is_null($content)) {
            $content = $this->nodes[0]->getContent();
        }

        $rendered = '';

        if(count($content)) {
            foreach($content as $c) {
                if(is_array($c)) {
                    $node = $this->nodes[$c[0]];

                    $option  = $node->getOption();
                    $content = $this->toBBCode($node->getContent());

                    $rendered .= '[' . $node->getTag() . ($option ? '=' . $option : '') . ']' . $content . '[/' . $node->getTag() . ']';
                } else {
                    $rendered .= $c;
                }
            }
        }

        return $rendered;
    }

    private function tokenize($str) {
        $this->node       = new node;
        $this->state      = 0;
        $this->openTag    = '';
        $this->option     = '';
        $this->closingTag = '';

        $this->nodes[] = $this->node;

        $length = strlen($str);

        for($i = 0; $i < $length; $i++) {
            switch($this->state) {
                case 0:
                    $this->parseChar($str[$i]);
                    break;
                case 1:
                    $this->parseOpenTag($str[$i]);
                    break;
                case 2:
                    $this->parseOpenTagOption($str[$i]);
                    break;
                case 3:
                    $this->parseClosingTag($str[$i]);
                    break;
            }
        }
    }

    private function openTag() {
        $tag = new node;
        $tag->setTag($this->openTag);
        $tag->setParent($this->node->getIndex());
        $tag->setIndex(count($this->nodes));

        $this->nodes[] = $tag;

        $this->node->addContent(count($this->nodes) - 1, true);
        $this->node = $tag;

        $this->openTags[count($this->nodes) - 1] = $this->openTag;
    } 

    private function setTagOption() {
        $this->node->setOption($this->option);
        $this->option = '';
    }

    private function closeTag($nodeIndex=null) {
        $parentNode    = $this->node->getParent();
        $this->openTag = '';

        if($parentNode >= 0) {
            $this->node = $this->nodes[$parentNode];
        }
    }

    private function addToContent($char) {
        $this->node->addContent($char);
    }

    private function parseChar($char) {
        if($char == '[') {
            $this->state = 1;
        } else {
            $this->addToContent($char);
        }
    }

    private function parseOpenTag($char) {
        if(($char == '=' || $char == ']')) {
            if(array_key_exists($this->openTag, $this->tags)) {
                if($char == '=') {
                    $this->state = 2;
                } else {
                    $this->state = 0;
                }

                $this->openTag();
            } else {
                $this->state = 0;
                $this->addToContent('[' . $this->openTag . $char);
            }
            
            $this->openTag = '';
        } elseif($char == '/') {
            $this->state = 3;
        } else {
            $this->openTag .= strtolower($char);
        }
    }

    private function parseOpenTagOption($char) {
        if($char == ']') {
            $this->setTagOption();

            $this->state = 0;
        } else {
            $this->option .= $char;
        }
    }

    private function parseClosingTag($char) {
        if($char == ']') {
            if($this->closingTag == $this->node->getTag()) {
                $this->closeTag();
            } else {
                $this->addToContent('[/' . $this->closingTag . $char);
            }

            $this->state      = 0;
            $this->closingTag = '';
        } else {
            $this->closingTag .= strtolower($char);
        }
    }
}

class node {
    private $parentNode = -1;
    private $index      = 0;
    private $tag        = '';
    private $option     = '';
    private $content    = array();

    public function setParent($parent) {
        $this->parentNode = $parent;
    }

    public function getParent() {
        return $this->parentNode;
    }

    public function setIndex($index) {
        $this->index = $index;
    }

    public function getIndex() {
        return $this->index;
    }

    public function setTag($tag) {
        $this->tag = $tag;
    }

    public function getTag() {
        return $this->tag;
    }

    public function setOption($option) {
        $this->option = $option;
    }

    public function getOption() {
        return $this->option;
    }

    public function addContent($content, $addAsNode=false) {
        if($addAsNode) {
            $this->content[] = array($content);
            $this->content[] = '';
        } else {
            $index = (count($this->content) - 1);

            if($index < 0) {
                $index = 0;
            }

            if(!array_key_exists($index, $this->content)) {
                $this->content[$index] = '';
            }

            $this->content[$index] .= $content;
        }
    }

    public function getContent() {
        return $this->content;
    }
}