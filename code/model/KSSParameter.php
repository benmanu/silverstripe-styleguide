<?php
class KSSParameter extends ViewableData {

	/**
	 * @var Scan\Kss\Parameter
	 */
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
