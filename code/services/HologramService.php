<?php
class HologramService implements StyleGuide {

	/**
	 * @var \StyleGuide\Parser
	 */
	protected $parser;

	/**
	 * @var String
	 */
	protected $paths;

	/**
	 * Absolute path to the css/scss/sass directory on the system.
	 * @param String $paths
	 */
	public function setURL($paths) {
		$this->paths = $paths;
		$this->parser = new \StyleGuide\HologramParser($this->paths);
	}

	/**
	 * Returns the top level navigation elements.
	 * @return ArrayList
	 */
	public function getNavigation() {
		$topSections = $this->parser->getTopLevelSections();
		$list = new ArrayList();

		foreach($topSections as $section) {
			$list->push(new KSSSection($section));
		}

		return $list;
	}

	/**
	 * Get a single section by reference.
	 * @param  String $reference The sections reference.
	 * @return ArrayList
	 */
	public function getSection($reference) {
		$section = $this->kss->getSection($this->parseReference($reference));
		return new KSSSection($section);
	}

	/**
	 * Returns all sections.
	 * @return ArrayList
	 */
	public function getSections() {
		$sections = $this->kss->getSections();

		$list = new ArrayList();
		foreach($sections as $section) {
			$list->push(new KSSSection($section));
		}

		return $list;
	}

	/**
	 * Return the children of a section.
	 * @param  String 	$reference  The parent sections reference.
	 * @param  Int 		$levelsDown We must go deeper.
	 * @return ArrayList
	 */
	public function getSectionChildren($reference, $levelsDown = null) {
		$sections = $this->kss->getSectionChildren($this->parseReference($reference), $levelsDown);

		$list = new ArrayList();
		foreach($sections as $section) {
			$list->push(new KSSSection($section));
		}

		return $list;
	}

	/**
	 * Changes a reference from a href suitable ref back into it's correct format.
	 * @param  String $reference Formatted reference.
	 * @return String            Unformatted reference.
	 */
	protected function parseReference($reference) {
		$reference = str_replace("section-", "", $reference);
		$reference = str_replace("-", ".", $reference);
		return $reference;
	}

}
