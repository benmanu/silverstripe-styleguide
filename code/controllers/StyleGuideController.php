<?php
/**
 * StyleGuideController
 *
 * @package StyleGuide
 */
class StyleGuideController extends ContentController {

	/**
     * @var StyleGuide service
     */
	protected $styleguide_service;

	/**
     * @var ArrayData
     */
	protected $fixture;

	/**
     * @var PageService
     */
	protected $pageService;

	/**
     * @config
     */
	private static $service = '';

	/**
     * @config
     */
	private static $paths = array();

	/**
     * @config
     */
	private static $css_files = array();

	/**
     * @config
     */
	private static $js_files = array();

	public function init() {
		parent::init();

		if(!$this->config()->service) {
			$this->httpError(404);
		}

		$this->setService($this->config()->service);

		$this->pageService = new StyleGuide\PageService($this);

		// redirect to the first action route
		if(!$this->request->param('Action')) {
			$page = $this->pageService->getPages()->first();
			$this->redirect($page->Link);
		}

		// if no template set on the action route then redirect to the first child
		if(!$this->request->param('ChildAction') && !$this->pageService->getTemplate()) {
			$page = $this->pageService->getActivePage();
			if(isset($page->Children)) {
				$childPage = $page->Children->first();
				$this->redirect($childPage->Link);
			}
		}

		// set the service
		$this->setRequirements();

		// load the fixture file
		$this->loadFixture();
	}

	public function index() {
		// render the set template for the route
		if($template = $this->pageService->getTemplate()) {
			return $this->renderWith(array(
                $template,
                'StyleGuideController'
            ));
		}
		return $this->renderWith(array('StyleGuideController'));
	}

	/**
	 * Set the styleguide service on init.
	 * @param String $name 		The name of the styleguide service.
	 * @param String $paths  	Project base url.
	 */
	public function setService($name, $paths = null) {
		if(!$paths) {
			// set the paths as the document root
			$paths = array();
			if(is_array($this->config()->paths)) {
				foreach($this->config()->paths as $path) {
					$paths[] = Director::BaseFolder() . "/" . $path;
				}
			} elseif(is_string($this->config()->paths)) {
				$paths[] = Director::BaseFolder() . "/" . $this->config()->paths;
			}
		}

		$this->styleguide_service = Injector::inst()->create($name);
		$this->styleguide_service->setURL($paths);
	}

	/**
	 * Set the styleguides css and js requirements.
	 */
	public function setRequirements() {
		// theme requirements
		if($files = $this->config()->css_files) {
			foreach($files as $file) {
				Requirements::css($file);
			}
		}

		if($files = $this->config()->js_files) {
			foreach($files as $file) {
				Requirements::javascript($file);
			}
		}

		// styleguide requirements
		Requirements::css(STYLEGUIDE_BASE . '/dist/css/screen.css');
		Requirements::javascript(STYLEGUIDE_BASE . '/dist/js/core.js');
		Requirements::javascript('//cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js?skin=desert');
	}

	/**
	 * Load a yml fixture file if defined into an {@link StyleGuideFixtureFactory}.
	 * Used to populate templates.
	 */
	public function loadFixture() {
		$path = project() . '/styleguide/fixture.yml';
		if(StyleGuide\YamlParser::hasYaml($path)) {
			$parser = new StyleGuide\YamlParser($path);
	        $this->fixture = $parser->get('Template');
	    }
	}

	public function getFixture() {
		return $this->fixture;
	}

	public function getNavigation() {
		return $this->pageService->getPages();
	}

	/**
	 * Return sections for sub-navigation.
	 * @return ArrayList
	 */
	public function getSubNavigation() {
		return $this->getSections();
	}

	/**
	 * Return sections filtered by the current url action.
	 * @return ArrayList
	 */
	public function getSections() {
		$sections = null;

		if($action = $this->request->param('ChildAction')) {
			if($action == 'all') {
				$sections = $this->styleguide_service->getSections();
			} else {
				$sections = $this->styleguide_service->getSectionChildren($action);
				$sections->unshift($this->styleguide_service->getSection($action));
			}
		}

		return $sections;
	}

	/**
	 * Get a link to the controller with optional action parameter.
	 * @param String $action
	 * @param String $childAction
	 * @return String
	 */
	public function Link($action = null, $childAction = null) {
		return self::getLink($action, $childAction);
	}

	/**
	 * Create a string suitable for a link in the style guide.
	 * @param  String $action
	 * @param  String $childAction
	 * @return String
	 */
	public static function getLink($action = null, $childAction = null) {
	    return Controller::join_links('sg', $action, $childAction);
	}

}
