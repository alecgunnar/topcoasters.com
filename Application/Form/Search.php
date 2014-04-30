<?php

namespace Application\Form;

class Search extends \Maverick\Lib\Form {
    /**
     * Builds the form to be rendered later
     *
     * @return null
     */
    public function build() {
        $this->setName('search');
        $this->setMethod('get');
        $this->setAction('/search');
        $this->setTpl('Search');

        $this->toggleSubmissionToken();

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