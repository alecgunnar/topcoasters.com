<?php

namespace Application\Model;

class ExchangeFile extends Standard {
    private $screenshotUrl = null;
    private $categoryName  = null;
    private $categoryLink  = null;
    private $downloadUrl   = null;

    protected function setUp() { }

    public function setUrl() {
        $this->url = '/exchange/file/' . $this->get('file_id') . '/' . $this->get('seo_title');
    }

    private function setScreenshot() {
        $this->screenshotUrl = '';

        if($this->get('screenshot')) {
            $this->screenshotUrl = substr_replace(\Maverick\Maverick::getConfig('Exchange')->get('paths')->get('screenshots'), '/', 0, strlen(PUBLIC_PATH)) . $this->get('screenshot');
        }
    }

    public function getScreenshot() {
        if(is_null($this->screenshotUrl)) {
            $this->setScreenshot();
        }

        return $this->screenshotUrl;
    }

    private function setCategoryName() {
        $this->categoryName = \Maverick\Maverick::getConfig('Exchange')->get('categories')->getAsArray()[$this->get('category')][1];
    }

    public function getCategoryName() {
        if(is_null($this->categoryName)) {
            $this->setCategoryName();
        }

        return $this->categoryName;
    }

    private function setCategoryLink() {
        $link = '/exchange/' . \Maverick\Maverick::getConfig('Exchange')->get('categories')->getAsArray()[$this->get('category')][0];

        $tag = new \Maverick\Lib\Builder_Tag('a');
        $tag->addAttribute('href', $link)
            ->addContent($this->getCategoryName());

        $this->categoryLink = $tag->render();
    }

    public function getCategoryLink() {
        if(is_null($this->categoryLink)) {
            $this->setCategoryLink();
        }

        return $this->categoryLink;
    }

    private function setDownloadUrl() {
        $this->downloadUrl = '/exchange/download/' . $this->get('file_id');
    }

    public function getDownloadUrl() {
        if(is_null($this->downloadUrl)) {
            $this->setDownloadUrl();
        }

        return $this->downloadUrl;
    }
}