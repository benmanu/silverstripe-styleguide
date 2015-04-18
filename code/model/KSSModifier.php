<?php
class KSSModifier extends ViewableData {

	protected $modifier;

	private static $casting = array(
		'Name' 			=> 'Varchar',
		'ClassName' 	=> 'Varchar',
		'Description' 	=> 'Varchar',
		'ExampleHtml' 	=> 'HTMLText'
	);

	public function __construct($modifier) {
		$this->modifier = $modifier;
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

}
