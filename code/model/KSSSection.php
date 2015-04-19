<?php
class KSSSection extends ViewableData {

	/**
	 * @var Scan\Kss\Section
	 */
	protected $section;

	private static $casting = array(
		'Title' 		=> 'Varchar',
		'Description' 	=> 'Varchar',
		'Markup' 		=> 'HTMLText',
		'MarkupNormal'  => 'HTMLText',
		'Deprecated' 	=> 'Varchar',
		'Compatibility' => 'Varchar',
		'Section'		=> 'Varchar',
		'Reference' 	=> 'Varchar'
	);

	public function __construct($section) {
		$this->section = $section;
	}

	/**
	 * @return String
	 */
	public function getTitle() {
		return $this->section->getTitle();
	}

	/**
	 * Return the description with custom elements extracted.
	 * @return String
	 */
	public function getDescription() {
		$description = $this->section->getDescription();
		$descriptionSections = array();

		// exclude the Template: section
		if($description) {
            $commentSections = explode("\n\n", $description);

            foreach($commentSections as $commentSection) {
            	if($commentSection != $this->getTemplateComment()) {
	                $descriptionSections[] = $commentSection;
	            }
            }
        }

		return implode("\n\n", $descriptionSections);
	}

	/**
	 * @return HTMLText
	 */
	public function getMarkup() {
		return $this->section->getMarkup();
	}

	/**
	 * @return HTMLText
	 */
	public function getMarkupNormal($replacement = '') {
		return $this->section->getMarkupNormal($replacement);
	}

	/**
	 * @return String
	 */
	public function getDeprecated() {
		return $this->section->getDeprecated();
	}

	/**
	 * @return String
	 */
	public function getExperimental() {
		return $this->section->getExperimental();
	}

	/**
	 * @return String
	 */
	public function getCompatibility() {
		return $this->section->getCompatibility();
	}

	/**
	 * @return ArrayList
	 */
	public function getModifiers() {
		$modifiers = $this->section->getModifiers();
		$list = new ArrayList();

		foreach($modifiers as $modifier) {
			$list->push(new KSSModifier($modifier, $this));
		}

		return $list;
	}

	/**
	 * @return ArrayList
	 */
	public function getParameters() {
		$parameters = $this->section->getParameters();
		$list = new ArrayList();

		foreach($parameters as $parameter) {
			$list->push(new KSSParameter($parameter));
		}

		return $list;
	}

	/**
	 * Section reference formatted suitably for internal linking.
	 * @return String
	 */
	public function getReference($trimmed = false) {
		$reference = $this->section->getReference($trimmed);
		return "section-" . str_replace(".", "-", $reference);
	}

	/**
	 * @return boolean
	 */
	public function hasReference() {
		return $this->section->hasReference();
	}

	/**
	 * Get a Template: comment if defined in the section comment.
	 * @return String The name of the template.
	 */
	public function getTemplateComment() {
		$description = $this->section->getDescription();
		$templateComment = null;

		// get the Template: section
		if($description) {
            $commentSections = explode("\n\n", $description);

            foreach($commentSections as $commentSection) {
            	if(preg_match('/^\s*Template:/i', $commentSection)) {
            		$templateComment = $commentSection;
                	break;
            	}
            }
        }

		return $templateComment;
	}

	/**
	 * Returns the section template if defined, rendered with the current controller.
	 * @return HTMLText
	 */
	public function getTemplate() {
		$template = null;

        if($templateComment = $this->getTemplateComment()) {
        	$template = trim(preg_replace('/^\s*Template:/i', '', $templateComment));
        	$template = Controller::curr()->renderWith($template);
        }

		return $template;
	}

	/**
	 * Checks if the current section is the active route.
	 * @return Boolean
	 */
	public function getActive() {
		return $this->request->param('Action') == $this->getReference();
	}

	/**
	 * Returns the link to this section formatted on the StyleGuideController.
	 * @return String
	 */
	public function getLink() {
		return StyleGuideController::getLink($this->getReference());
	}

}
