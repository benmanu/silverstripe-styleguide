<?php
/**
 * Section
 *
 * A Comment Block that represents a single section.
 */
namespace StyleGuide;

class Section extends \ViewableData
{

    /**
     * The raw Comment Block
     *
     * @var string
     */
    protected $rawComment = '';

    /**
     * The file where the Comment Block came from
     *
     * @var \SplFileObject
     */
    protected $file = null;

    /**
     * Creates a section with the Comment Block and source file
     *
     * @param string $comment
     * @param \SplFileObject $file
     */
    public function __construct($comment = '', \SplFileObject $file = null)
    {
        $this->rawComment = $comment;
        $this->file = $file;
    }

    /**
     * Returns the source filename for where the comment block was located
     *
     * @return string
     */
    public function getFilename()
    {
        if ($this->file === null) {
            return '';
        }

        return $this->file->getFilename();
    }

    /**
     * Render a SilverStripe template with fixture data if set.
     * @param  String $template The name of the template.
     * @return String           HTMLText string of the rendered template.
     */
    public function getRenderedTemplate($template)
    {
        $controller = \Controller::curr();
        $factory = $controller->getFactory();

        // if the factory is set and the fixture object exists render the template with
        // the object.
        if ($factory) {
            if ($obj = $factory->get('Template', $template)) {
                return $obj->renderWith($template);
            }
        }

        return $controller->renderWith($template);
    }
}
