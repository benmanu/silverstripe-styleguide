<?php
class KSSParameter extends ViewableData {

	protected $parameter;

	private static $casting = array(
		'Name' 			=> 'Varchar',
		'Description' 	=> 'Varchar'
	);

	public function __construct($parameter) {
		$this->parameter = $parameter;
	}

	public function getName() {
		return $this->parameter->getName();
	}

	public function getDescription() {
		return $this->parameter->getDescription();
	}

}
