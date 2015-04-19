<?php
class KSSSection extends ViewableData {

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

	public function getTitle() {
		return $this->section->getTitle();
	}

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

	public function getMarkup() {
		return $this->section->getMarkup();
	}

	public function getMarkupNormal($replacement = '') {
		return $this->section->getMarkupNormal($replacement);
	}

	public function getDeprecated() {
		return $this->section->getDeprecated();
	}

	public function getExperimental() {
		return $this->section->getExperimental();
	}

	public function getCompatibility() {
		return $this->section->getCompatibility();
	}

	public function getModifiers() {
		$modifiers = $this->section->getModifiers();
		$list = new ArrayList();

		foreach($modifiers as $modifier) {
			$list->push(new KSSModifier($modifier));
		}

		return $list;
	}

	public function getParameters() {
		$parameters = $this->section->getParameters();
		$list = new ArrayList();

		foreach($parameters as $parameter) {
			$list->push(new KSSParameter($parameter));
		}

		return $list;
	}

	public function getReference($trimmed = false) {
		return $this->section->getReference($trimmed);
	}

	public function hasReference() {
		return $this->section->hasReference();
	}

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

	public function getTemplate() {
		$template = null;

        if($templateComment = $this->getTemplateComment()) {
        	$template = trim(preg_replace('/^\s*Template:/i', '', $templateComment));
        	$template = Controller::curr()->renderWith($template);
        }

		return $template;
	}

	public function getActive() {
		return $this->request->param('Action') == $this->getReference();
	}

	public function getLink() {
		return StyleGuideController::getLink($this->getReference());
	}

}
