<?php

use StyleGuide\Parser;

class HologramParser extends Parser {

    /**
     * Adds a section to the Sections collection
     *
     * @param string $comment
     * @param \splFileObject $file
     */
    protected function addSection($comment, \splFileObject $file) {
        $section = new HologramSection($comment, $file);
        $this->sections[] = $section;
    }

    /**
     * Returns an array of all the sections
     *
     * @return array
     */
    public function getSections() {
        return $this->sections;
    }

}
