<?php
class StyleGuideFixtureFactory extends FixtureFactory {

	public function createObject($name, $identifier, $data = null) {
		if(!isset($this->blueprints[$name])) {
			$this->blueprints[$name] = new FixtureBlueprint($name);
		}
		$blueprint = $this->blueprints[$name];
		$obj = $blueprint->createObject($identifier, $data, $this->fixtures);
		$class = $blueprint->getClass();

		if(!isset($this->fixtures[$class])) {
			$this->fixtures[$class] = array();
		}
		$this->fixtures[$class][$identifier] = $obj;

		return $obj;
	}

	public function get($class, $identifier) {
		return (isset($this->fixtures[$class][$identifier]) ? $this->fixtures[$class][$identifier] : null);
	}

}
