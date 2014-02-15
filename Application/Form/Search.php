<?php

namespace Application\Form;

class Search extends \Maverick\Lib\Form {
    private $placeholder = 'Search Top Coasters';
    private $searchWhat  = '';

    public function __construct($placeholder, $searchWhat) {
        $this->placeholder = $placeholder;
        $this->searchWhat  = $searchWhat;

        parent::__construct();
    }

    /**
     * Builds the form to be rendered later
     *
     * @return null
     */
    public function build() {
        $this->setName('search');
        $this->setAction('/search' . ($this->searchWhat ? '/' . $this->searchWhat : ''));

        $this->addField('Input', 'q')
            ->setPlaceholder($this->placeholder)
            ->setTpl('Search');
    }

    /**
     * Validates the form
     */
    public function validate() { }

    /**
     * Submits the form
     */
    public function submit() { }
}