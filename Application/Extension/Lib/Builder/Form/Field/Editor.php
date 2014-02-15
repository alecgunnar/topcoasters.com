<?php

namespace Maverick\Lib;

class Builder_Form_Field_Editor extends Builder_Form_Field_TextArea {
    public function render() {
        $id = $this->getAttribute('id');

        if(!$id) {
            $id = $this->getName() . '_editor_markItUp';
            $this->addAttribute('id', $id);
        }

        $script = new Builder_Tag('script');

        $script->addContent('$(document).ready(function(){$("#' . $id . '").markItUp(markItUpSettings)});');

        return $script->render() . parent::render();
    }
}