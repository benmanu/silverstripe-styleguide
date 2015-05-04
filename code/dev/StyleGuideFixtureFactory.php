<?php
class StyleGuideFixtureFactory extends FixtureFactory {

	/**
	 * @param String $name Unique name for this blueprint
	 * @param array|FixtureBlueprint $defaults Array of default values, or a blueprint instance
	 */
	public function define($name, $defaults = array()) {
		$this->blueprints[$name] = $defaults;
		return $this;
	}

	public function createObject($name, $identifier, $data = null) {
		$blueprint = $this->blueprints['StyleGuide'];
		$obj = $blueprint->createObject($identifier, $data, $this->fixtures);

		if(!isset($this->fixtures[$name])) {
			$this->fixtures[$name] = array();
		}
		$this->fixtures[$name][$identifier] = $obj;

		return $obj;
	}

	public function createRaw($table, $identifier, $data) {
		return $this->createObject($table, $identifier, $data);
	}

	public function get($class, $identifier) {
		return (isset($this->fixtures[$class][$identifier]) ? $this->fixtures[$class][$identifier] : null);
	}

}
