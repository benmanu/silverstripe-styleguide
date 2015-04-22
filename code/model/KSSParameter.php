<?php
class KSSParameter extends ViewableData {

	/**
	 * @var KSSSection
	 */
	protected $section;

	/**
     * Name of the parameter
     *
     * @var string
     */
    protected $name = '';

    /**
     * Description of the parameter
     *
     * @var string
     */
    protected $description = '';

	private static $casting = array(
		'Name' 			=> 'Varchar',
		'Description' 	=> 'Varchar'
	);

	public function __construct($name, $description = '', $section) {
		$this->section = $section;

		$this->setName($name);
        $this->setDescription($description);
	}

	/**
     * Returns the name of the parameter
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name of the parameter
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Returns the description of the parameter
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the description of the parameter
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

}
