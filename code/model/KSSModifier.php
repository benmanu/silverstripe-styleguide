<?php
class KSSModifier extends ViewableData {

	protected $modifier;

	protected $section;

	private static $casting = array(
		'Name' 			=> 'Varchar',
		'ClassName' 	=> 'Varchar',
		'Description' 	=> 'Varchar',
		'ExampleHtml' 	=> 'HTMLText'
	);

	public function __construct($modifier, $section) {
		$this->modifier = $modifier;
		$this->section = $section;
	}

	public function getName() {
		return $this->modifier->getName();
	}

	public function getClassName() {
		return $this->modifier->getClassName();
	}

	public function getDescription() {
		return $this->modifier->getDescription();
	}

	public function getExampleHtml() {
		return $this->modifier->getExampleHtml();
	}

	public function getReference() {
		$filter = URLSegmentFilter::create();
		return $filter->filter($this->section->getReference() . '-' . $this->getName());
	}

}
