<?php
class KSSService implements StyleGuide {

	protected $kss;

	protected $url;

	private static $casting = array(
		'Title' => 'String'
	);

	/**
	 * Absolute path to the css/scss/sass directory on the system.
	 * @param String $url
	 */
	public function setURL($url) {
		$this->url = $url;
		$this->kss = new \Scan\Kss\Parser($this->url);
	}

	public function getNavigation() {
		$topSections = $this->kss->getTopLevelSections();
		$list = new ArrayList();

		foreach($topSections as $section) {
			$list->push(new KSSSection($section));
		}

		return $list;
	}

	public function getSection($reference) {
		$section = $this->kss->getSection($this->parseReference($reference));
		return new KSSSection($section);
	}

	public function getSections() {
		$sections = $this->kss->getSections();

		$list = new ArrayList();
		foreach($sections as $section) {
			$list->push(new KSSSection($section));
		}

		return $list;
	}

	public function getSectionChildren($reference, $levelsDown = null) {
		$sections = $this->kss->getSectionChildren($this->parseReference($reference), $levelsDown);

		$list = new ArrayList();
		foreach($sections as $section) {
			$list->push(new KSSSection($section));
		}

		return $list;
	}

	protected function parseReference($reference) {
		$reference = str_replace("section-", "", $reference);
		$reference = str_replace("-", ".", $reference);
		return $reference;
	}

}
