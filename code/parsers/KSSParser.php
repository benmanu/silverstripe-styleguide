<?php

use StyleGuide\Parser;

class KSSParser extends Parser {

    /**
     * A flag on whether sections have been sorted
     *
     * @var boolean
     */
    protected $sectionsSortedByReference = false;

    /**
     * Adds a section to the Sections collection
     *
     * @param string $comment
     * @param \splFileObject $file
     */
    protected function addSection($comment, \splFileObject $file) {
        if (self::isKssBlock($comment)) {
            $section = new KSSSection($comment, $file);
            $this->sections[$section->getReference(true)] = $section;
            $this->sectionsSortedByReference = false;
        }
    }

    /**
     * Returns a Section object matching the requested reference. If reference
     * is not found, an empty Section object is returned instead
     *
     * @param string $reference
     *
     * @return Section
     *
     * @throws UnexepectedValueException if reference does not exist
     */
    public function getSection($reference) {
        $reference = KSSSection::trimReference($reference);
        $reference = strtolower(KSSSection::normalizeReference($reference));

        foreach ($this->sections as $sectionKey => $section) {
            $potentialMatch = strtolower(KSSSection::normalizeReference($sectionKey));
            if ($reference === $potentialMatch) {
                return $section;
            }
        }
    }

    /**
     * Returns an array of all the sections
     *
     * @return array
     */
    public function getSections() {
        $this->sortSections();
        return $this->sections;
    }

    /**
     * Returns only the top level sections (i.e. 1.0, 2.0, 3.0, etc.)
     *
     * @return array
     */
    public function getTopLevelSections() {
        $this->sortSectionsByDepth();
        $topLevelSections = array();

        foreach ($this->sections as $section) {
            if ($section->getDepth() != 0) {
                break;
            }
            $topLevelSections[] = $section;
        }

        return $topLevelSections;
    }

    /**
     * Returns an array of children for a specified section reference
     *
     * @param string $reference
     * @param int $levelsDown OPTIONAL
     *
     * @return array
     */
    public function getSectionChildren($reference, $levelsDown = null) {
        $reference = strtolower(KSSSection::normalizeReference($reference));
        $this->sortSections();

        $sectionKeys = array_keys($this->sections);
        $sections = array();

        $maxDepth = null;
        if ($levelsDown !== null) {
            $maxDepth = KSSSection::calcDepth($reference) + $levelsDown;
        }

        $reference = KSSSection::trimReference($reference);
        $reference .= '.';
        foreach ($sectionKeys as $sectionKey) {
            $testSectionKey = strtolower(KSSSection::normalizeReference($sectionKey));
            // Only get sections within that level. Do not get the level itself
            if (strpos($testSectionKey . '.', $reference) === 0
                && $testSectionKey . '.' != $reference
            ) {
                $section = $this->sections[$sectionKey];
                if ($maxDepth !== null && $section->getDepth() > $maxDepth) {
                    continue;
                }
                $sections[$sectionKey] = $section;
            }
        }

        return $sections;
    }

    /**
     * Method to only sort the sections if they need sorting
     *
     * @return void
     */
    protected function sortSections() {
        if ($this->sectionsSortedByReference) {
            return;
        }

        uasort($this->sections, 'KSSSection::alphaDepthScoreSort');
        $this->sectionsSortedByReference = true;
    }

    /**
     * Method to sort the sections by depth
     *
     * @return void
     */
    protected function sortSectionsByDepth() {
        uasort($this->sections, 'KSSSection::depthSort');
        $this->sectionsSortedByReference = false;
    }

    /**
     * Checks to see if a comment block is a KSS Comment block
     *
     * @param string $comment
     *
     * @return boolean
     */
    public static function isKssBlock($comment) {
        $commentLines = explode("\n\n", $comment);
        $lastLine = end($commentLines);
        return preg_match('/^\s*Styleguide \w/i', $lastLine) ||
            preg_match('/^\s*No styleguide reference/i', $lastLine);
    }

}
