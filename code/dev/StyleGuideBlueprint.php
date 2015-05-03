<?php
class StyleGuideBlueprint extends FixtureBlueprint {

	public function __construct($name, $class = null, $defaults = array()) {
		$this->name = $name;
		$this->class = $class;
	}
	
	public function createObject($identifier, $data = null, $fixtures = null) {
		$obj = new ArrayData(array_merge(array(
			'ID' => $identifier,
		), $data));
		return $obj;
	}

}
