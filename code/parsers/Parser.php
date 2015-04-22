<?php
/**
 * Parser
 *
 * Accepts an array of directories and parses them stylesheet files
 */
namespace StyleGuide;

use Symfony\Component\Finder\Finder;

class Parser {

    /**
     * An array of the different comment sections found in the parsed directories.
     * @var array
     */
    protected $sections = array();

    /**
     * Parses specified directories for comments and adds any valid Sections found.
     * @param string|array $paths A string or array of the paths to scan for comments
     */
    public function __construct($paths) {
        $finder = new Finder();

        // Only accept css, sass, scss, less, and js files.
        $finder->files()->name('/\.(css|sass|scss|less|js)$/')->in($paths);

        foreach($finder as $fileInfo) {
            $file = new \splFileObject($fileInfo);
            $commentParser = new CommentParser($file);
            foreach($commentParser->getBlocks() as $commentBlock) {
                $this->addSection($commentBlock, $file);
            }
        }
    }

    /**
     * Adds a section to the Sections collection
     *
     * @param string $comment
     * @param \splFileObject $file
     */
    protected function addSection($comment, \splFileObject $file) {
        $section = new Section($comment, $file);
        $this->sections[] = $section;
    }

    /**
     * Returns an array of all the sections
     *
     * @return array
     */
    public function getSections() {
        return $this->sections;
    }

}
