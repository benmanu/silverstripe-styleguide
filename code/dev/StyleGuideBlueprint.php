<?php
class StyleGuideBlueprint extends FixtureBlueprint {

	public function __construct($name, $class = null, $defaults = array()) {
		$this->name = $name;
		$this->class = $class;
	}
	
	public function createObject($identifier, $data = null, $fixtures = null) {
		$class = $this->class;

		$parsedItems = array(
			'ID' => $identifier
		);
		
		// Populate all field values, including relationships.
		if($data) foreach($data as $fieldName => $fieldVal) {
			if(is_array($fieldVal)) {
				// set an arraylist if multiple items are set
				if(count($fieldVal) > 1) {
					$parsedList = new ArrayList();
					foreach($fieldVal as $relVal) {
						$parsedList->push($this->parseValue($relVal, $fixtures));
					}
					$parsedItems[$fieldName] = $parsedList;
				} else {
					foreach($fieldVal as $relVal) {
						$id = $this->parseValue($relVal, $fixtures);
						$parsedItems[$fieldName] = $id;
					}
				}
			} elseif(substr($fieldVal,0,2) == '=>') {
				$items = preg_split('/ *, */',trim($fieldVal));

				// set an arraylist if multiple items are set
				if(count($items) > 1) {
					$parsedList = new ArrayList();
					foreach($items as $item) {
						// Check for correct format: =><relationname>.<identifier>.
						// Ignore if the item has already been replaced with a numeric DB identifier
						if(!is_numeric($item) && !preg_match('/^=>[^\.]+\.[^\.]+/', $item)) {
							throw new InvalidArgumentException(sprintf(
								'Invalid format for relation "%s" on class "%s" ("%s")',
								$fieldName,
								$class,
								$item
							));
						}

						$parsedList->push($this->parseValue($item, $fixtures));
					}
					$parsedItems[$fieldName] = $parsedList;
				} else {
					foreach($items as $item) {
						// Check for correct format: =><relationname>.<identifier>.
						// Ignore if the item has already been replaced with a numeric DB identifier
						if(!is_numeric($item) && !preg_match('/^=>[^\.]+\.[^\.]+/', $item)) {
							throw new InvalidArgumentException(sprintf(
								'Invalid format for relation "%s" on class "%s" ("%s")',
								$fieldName,
								$class,
								$item
							));
						}

						$parsedItems[$fieldName] = $this->parseValue($item, $fixtures);
					}
				}
			} else {
				$parsedItems[$fieldName] = $fieldVal;
			}
		}

		return new ArrayData($parsedItems);
	}

	/**
	 * Parse a value from a fixture file.  If it starts with => 
	 * it will get an ID from the fixture dictionary
	 *
	 * @param String $fieldVal
	 * @param  Array $fixtures See {@link createObject()}
	 * @return String Fixture database ID, or the original value
	 */
	protected function parseValue($value, $fixtures = null) {
		if(substr($value,0,2) == '=>') {
			// Parse a dictionary reference - used to set foreign keys
			$ref = explode('.', substr($value,2));

			$class = $ref[0];
			$identifier = $ref[1];
			$field = (isset($ref[2]) ? $ref[2] : null);

			if($fixtures && !isset($fixtures[$class][$identifier])) {
				throw new InvalidArgumentException(sprintf(
					'No fixture definitions found for "%s"',
					$value
				));
			}

			if(isset($field)) {
				return $fixtures[$class][$identifier]->$field;
			} else {
				return $fixtures[$class][$identifier];
			}
		} else {
			// Regular field value setting
			return $value;
		}
	}

}
