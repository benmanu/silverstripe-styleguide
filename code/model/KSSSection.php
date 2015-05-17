<?php

use StyleGuide\Section;

class KSSSection extends Section {

	private static $casting = array(
		'Title' 		=> 'Varchar',
		'Description' 	=> 'HTMLText',
		'Markup' 		=> 'HTMLText',
		'MarkupNormal'  => 'HTMLText',
		'Deprecated' 	=> 'Varchar',
		'Compatibility' => 'Varchar',
		'Section'		=> 'Varchar',
		'Reference' 	=> 'Varchar'
	);

	/**
     * Returns the title of the section
     *
     * @return string
     */
	public function getTitle() {
		$title = '';

        $titleComment = $this->getTitleComment();
        if (preg_match('/^\s*#+\s*(.+)/', $titleComment, $matches)) {
            $title = $matches[1];
        } elseif (self::isReferenceNumeric($this->getReference())) {
            return $this->getReference();
        } else {
            $reference = $this->getReferenceParts();
            return end($reference);
        }

        return $title;
	}

	/**
     * Returns the description for the section
     *
     * @return string
     */
	public function getDescription() {
		$descriptionSections = array();

		foreach ($this->getCommentSections() as $commentSection) {
            // Anything that is not the section comment or modifiers comment
            // must be the description comment
            if ($commentSection != $this->getReferenceComment()
                && $commentSection != $this->getTitleComment()
                && $commentSection != $this->getMarkupComment()
                && $commentSection != $this->getDeprecatedComment()
                && $commentSection != $this->getExperimentalComment()
                && $commentSection != $this->getCompatibilityComment()
                && $commentSection != $this->getModifiersComment()
                && $commentSection != $this->getParametersComment()
                && $commentSection != $this->getTemplateComment()
            ) {
                $descriptionSections[] = $commentSection;
            }
        }

		$description = implode("\n\n", $descriptionSections);

        return Parsedown::instance()->text($description);
	}

	/**
     * Returns the markup defined in the section
     *
     * @return string
     */
	public function getMarkup() {
        if($markupComment = $this->getMarkupComment()) {
            return trim(preg_replace('/^\s*Markup:/i', '', $markupComment));
        }
	}

	/**
     * Returns the markup for the normal element (without modifierclass)
     *
     * @param string $replacement Replacement for $modifierClass variable
     * @return void
     */
	public function getMarkupNormal($replacement = '') {
        return str_replace('$modifierClass', $replacement, $this->getMarkup());
	}

	/**
     * Returns a boolean value regarding the presence of markup in the kss-block
     *
     * @return boolean
     */
    public function hasMarkup() {
		return $this->getMarkup() !== null;
	}

    /**
     * Returns the deprecation notice defined in the section
     *
     * @return string
     */
	public function getDeprecated() {
        if ($deprecatedComment = $this->getDeprecatedComment()) {
            return trim(preg_replace('/^\s*Deprecated:/i', '', $deprecatedComment));
        }
	}

	/**
     * Returns the experimental notice defined in the section
     *
     * @return string
     */
	public function getExperimental() {
		if($experimentalComment = $this->getExperimentalComment()) {
            return trim(preg_replace('/^\s*Experimental:/i', '', $experimentalComment));
        }
	}

	/**
     * Returns the compatibility notice defined in the section
     *
     * @return string
     */
	public function getCompatibility() {
		if($compatibilityComment = $this->getCompatibilityComment()) {
            return trim($compatibilityComment);
        }
	}

	/**
     * Returns the modifiers used in the section
     *
     * @return array
     */
	public function getModifiers() {
		$lastIndent = null;
        $modifiers = new ArrayList();

        if($modiferComment = $this->getModifiersComment()) {
            $modifierLines = explode("\n", $modiferComment);
            foreach($modifierLines as $line) {
                if(empty($line)) {
                    continue;
                }

                preg_match('/^\s*/', $line, $matches);
                $indent = strlen($matches[0]);

                if($lastIndent && $indent > $lastIndent) {
                    $modifier = end($modifiers);
                    $modifier->setDescription($modifier->getDescription() + trim($line));
                } else {
                    $lineParts = explode(' - ', $line);

                    $name = trim(array_shift($lineParts));

                    $description = '';
                    if (!empty($lineParts)) {
                        $description = trim(implode(' - ', $lineParts));
                    }
                    $modifier = new KSSModifier($name, $description, $this);

                    // If the CSS has a markup, pass it to the modifier for the example HTML
                    if($markup = $this->getMarkup()) {
                        $modifier->setMarkup($markup);
                    }
                    $modifiers->push($modifier);
                }
            }
        }

        return $modifiers;
	}

	/**
     * Returns the $parameters used in the section
     *
     * @return array
     */
	public function getParameters() {
		$lastIndent = null;
        $parameters = new ArrayList();

        if($parameterComment = $this->getParametersComment()) {
            $parameterLines = explode("\n", $parameterComment);
            foreach($parameterLines as $line) {
                if(empty($line)) {
                    continue;
                }

                $lineParts = explode(' - ', $line);

                $name = trim(array_shift($lineParts));

                $description = '';
                if(!empty($lineParts)) {
                    $description = trim(implode(' - ', $lineParts));
                }
                $parameter = new KSSParameter($name, $description, $this);

                $parameters->push($parameter);
            }
        }

        return $parameters;
	}

	/**
	 * Returns the section template if defined, rendered with the current controller.
	 * 
	 * @return HTMLText
	 */
	public function getTemplate() {
		$template = null;

        if($templateComment = $this->getTemplateComment()) {
        	$template = trim(preg_replace('/^\s*Template:/i', '', $templateComment));
            $template = $this->getRenderedTemplate($template);
        }

		return $template;
	}

	/**
     * Returns the reference number for the section
     *
     * @param boolean $trimmed OPTIONAL
     *
     * @return string
     */
	public function getReference($trimmed = false) {
		$reference = null;
        $referenceComment = $this->getReferenceComment();
        $referenceComment = preg_replace('/\.$/', '', $referenceComment);

        if(preg_match('/^\s*Styleguide\s+(.*)/i', $referenceComment, $matches)) {
            $reference = trim($matches[1]);
        }

        return ($trimmed && $reference !== null)
            ? self::trimReference($reference)
            : $reference;
	}

    /**
     * Returns the reference dot delimited
     *
     * @return string
     */
    protected function getReferenceDotDelimited() {
        return self::normalizeReference($this->getReference());
    }

	/**
     * Checks if the Section has a reference
     *
     * @return boolean
     */
	public function hasReference() {
		return $this->getReference() !== null;
	}

	/**
     * Checks to see if a reference is numeric
     *
     * @param string
     *
     * @return boolean
     */
    public static function isReferenceNumeric($reference) {
        return !preg_match('/[^\d\.]/', $reference);
    }

    /**
     * Returns the references as an array of its parts
     *
     * @return array
     */
    public function getReferenceParts() {
        return explode('.', $this->getReferenceDotDelimited());
    }

    /**
     * Trims off all trailing zeros and periods on a reference
     *
     * @param string $reference
     *
     * @return string
     */
    public static function trimReference($reference) {
        if (substr($reference, -1) == '.' || substr($reference, -1) == '-') {
            $reference = trim(substr($reference, 0, -1));
        }
        while (preg_match('/(\.0+)$/', $reference, $matches)) {
            $reference = substr($reference, 0, strlen($matches[1]) * -1);
        }
        return $reference;
    }

    /**
     * Normalizes references so all delimiters are standardized
     *
     * @param string $reference
     *
     * @return string
     */
    public static function normalizeReference($reference) {
        return preg_replace('/\s*-\s*/', '.', $reference);
    }

    /**
     * Checks to see if a section belongs to a specified reference
     *
     * @param string $reference
     *
     * @return boolean
     */
    public function belongsToReference($reference) {
        $reference = self::trimReference($reference);
        $reference = self::normalizeReference($reference);
        return stripos($this->getReferenceDotDelimited() . '.', $reference . '.') === 0;
    }

    /**
     * Helper method for calculating the depth of the instantiated section
     *
     * @return int
     */
    public function getDepth() {
        return self::calcDepth($this->getReferenceDotDelimited());
    }

    /**
     * Calculates and returns the depth of a section reference
     *
     * @param string $reference
     *
     * @return int
     */
    public static function calcDepth($reference) {
        $reference = self::trimReference($reference);
        $reference = self::normalizeReference($reference);
        return substr_count($reference, '.');
    }

    /**
     * Helper method for calculating the score of the instantiated section
     *
     * @return int
     */
    public function getDepthScore() {
        return self::calcDepthScore($this->getReferenceDotDelimited());
    }

    /**
     * Calculates and returns the depth score for the section. Useful for sorting
     * sections correctly by their section reference numbers
     *
     * @return int|null
     */
    public static function calcDepthScore($reference) {
        if (!self::isReferenceNumeric($reference)) {
            return null;
        }
        $reference = self::trimReference($reference);
        $sectionParts = explode('.', $reference);
        $score = 0;
        foreach ($sectionParts as $level => $part) {
            $score += $part * (1 / pow(10, $level));
        }
        return $score;
    }

    /**
     * Function to help sort sections by depth and then depth score or alphabetically
     *
     * @param Section $a
     * @param Section $b
     *
     * @return int
     */
    public static function depthSort(Section $a, Section $b) {
        if ($a->getDepth() == $b->getDepth()) {
            return self::alphaDepthScoreSort($a, $b);
        }
        return $a->getDepth() > $b->getDepth();
    }

    /**
     * Function to help sort sections by their depth score
     *
     * @param Section $a
     * @param Section $b
     *
     * @return int
     */
    public static function depthScoreSort(Section $a, Section $b) {
        return $a->getDepthScore() > $b->getDepthScore();
    }

    /**
     * Function to help sort sections either by their depth score if numeric or
     * alphabetically if non-numeric.
     *
     * @param Section $a
     * @param Section $b
     *
     * @return int
     */
    public static function alphaDepthScoreSort(Section $a, Section $b) {
        $aNumeric = self::isReferenceNumeric($a->getReference());
        $bNumeric = self::isReferenceNumeric($b->getReference());

        if($aNumeric && $bNumeric) {
            return self::depthScoreSort($a, $b);
        } elseif ($aNumeric) {
            return -1;
        } elseif ($bNumeric) {
            return 1;
        } else {
            return strnatcmp(
                $a->getReferenceDotDelimited(),
                $b->getReferenceDotDelimited()
            );
        }
    }

    /**
     * Returns the comment block used when creating the section as an array of
     * paragraphs within the comment block
     *
     * @return array
     */
    protected function getCommentSections() {
        return explode("\n\n", $this->rawComment);
    }

    /**
     * Gets the title part of the KSS Comment Block
     *
     * @return string
     */
    protected function getTitleComment()
    {
        $titleComment = null;

        foreach ($this->getCommentSections() as $commentSection) {
            // Identify the title by the # markdown header syntax
            if (preg_match('/^\s*#/i', $commentSection)) {
                $titleComment = $commentSection;
                break;
            }
        }

        return $titleComment;
    }

    /**
     * Returns the part of the KSS Comment Block that contains the markup
     *
     * @return string
     */
    protected function getMarkupComment() {
        $markupComment = null;

        foreach ($this->getCommentSections() as $commentSection) {
            // Identify the markup comment by the Markup: marker
            if (preg_match('/^\s*Markup:/i', $commentSection)) {
                $markupComment = $commentSection;
                break;
            }
        }

        return $markupComment;
    }

    /**
     * Returns the part of the KSS Comment Block that contains the deprecated
     * notice
     *
     * @return string
     */
    protected function getDeprecatedComment() {
        $deprecatedComment = null;

        foreach ($this->getCommentSections() as $commentSection) {
            // Identify the deprecation notice by the Deprecated: marker
            if (preg_match('/^\s*Deprecated:/i', $commentSection)) {
                $deprecatedComment = $commentSection;
                break;
            }
        }

        return $deprecatedComment;
    }

    /**
     * Returns the part of the KSS Comment Block that contains the experimental
     * notice
     *
     * @return string
     */
    protected function getExperimentalComment() {
        $experimentalComment = null;

        foreach ($this->getCommentSections() as $commentSection) {
            // Identify the experimental notice by the Experimental: marker
            if (preg_match('/^\s*Experimental:/i', $commentSection)) {
                $experimentalComment = $commentSection;
                break;
            }
        }

        return $experimentalComment;
    }

    /**
     * Returns the part of the KSS Comment Block that contains the compatibility
     * notice
     *
     * @return string
     */
    protected function getCompatibilityComment() {
        $compatibilityComment = null;

        foreach ($this->getCommentSections() as $commentSection) {
            // Compatible in IE6+, Firefox 2+, Safari 4+.
            // Compatibility: IE6+, Firefox 2+, Safari 4+.
            // Compatibility untested.
            if (preg_match('/^\s*Compatib(le|ility):?\s+/i', $commentSection)) {
                $compatibilityComment = $commentSection;
                break;
            }
        }

        return $compatibilityComment;
    }

    /**
     * Gets the part of the KSS Comment Block that contains the section reference
     *
     * @return string
     */
    protected function getReferenceComment() {
        $referenceComment = null;
        $commentSections = $this->getCommentSections();
        $lastLine = end($commentSections);

        if (preg_match('/^\s*Styleguide \w/i', $lastLine) ||
            preg_match('/^\s*No styleguide reference/i', $lastLine)
        ) {
            $referenceComment = $lastLine;
        }

        return $referenceComment;
    }

    /**
     * Returns the part of the KSS Comment Block that contains the modifiers
     *
     * @return string
     */
    protected function getModifiersComment() {
        $modifiersComment = null;

        foreach ($this->getCommentSections() as $commentSection) {
            // Assume that the modifiers section starts with either a class or a
            // pseudo class
            if (preg_match('/^\s*(?:\.|:)/', $commentSection)) {
                $modifiersComment = $commentSection;
                break;
            }
        }

        return $modifiersComment;
    }

    /**
     * Returns the part of the KSS Comment Block that contains the $parameters
     *
     * @return string
     */
    protected function getParametersComment() {
        $parametersComment = null;

        foreach ($this->getCommentSections() as $commentSection) {
            // Assume that the parameters section starts with $,%,@
            if (preg_match('/^\s*(\$|@|%)/', $commentSection)) {
                $parametersComment = $commentSection;
                break;
            }
        }

        return $parametersComment;
    }

	/**
	 * Returns the part of the KSS Comment Block that contains the template
	 * 
	 * @return String
	 */
	protected function getTemplateComment() {
		$templateComment = null;

        foreach ($this->getCommentSections() as $commentSection) {
            // Assume that the parameters section starts with $,%,@
            if (preg_match('/^\s*Template:/i', $commentSection)) {
                $templateComment = $commentSection;
                break;
            }
        }

		return $templateComment;
	}

	/**
	 * Checks if the current section is the active route.
	 * @return Boolean
	 */
	public function getActive() {
		return $this->request->param('Action') == $this->getReferenceID();
	}

    /**
     * Get the section reference formatted for url use.
     * @return String
     */
    public function getReferenceID() {
        return "section-" . str_replace(".", "-", $this->getReference());
    }

	/**
	 * Returns the link to this section formatted on the StyleGuideController.
	 * @return String
	 */
	public function getLink() {
		return $this->getReferenceID();
	}

}
