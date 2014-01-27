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
        $this->setAction('/search');

        $this->addField('Input', 'q')
            ->setPlaceholder(\Maverick\Lib\Output::getGlobalVariable('search_box_text'))
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