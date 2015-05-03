<?php
class StyleGuideController extends ContentController {

	/**
     * @var StyleGuide service
     */
	protected $styleguide_service;

	/**
     * @var StyleGuideFixtureFactory
     */
	protected $factory;

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

		// set the paths as the document root
		$paths = array();
		if(is_array($this->config()->paths)) {
			foreach($this->config()->paths as $path) {
				$paths[] = Director::BaseFolder() . "/" . $path;
			}
		} elseif(is_string($this->config()->paths)) {
			$paths[] = Director::BaseFolder() . "/" . $this->config()->paths;
		}

		// set the service
		$this->setService($this->config()->service, $paths);
		$this->setRequirements();

		// load the fixture file
		$this->loadFixture();
	}

	/**
	 * Set the styleguide service on init.
	 * @param String $name The name of the styleguide service.
	 * @param String $url  Project base url.
	 */
	public function setService($name, $url) {
		$this->styleguide_service = Injector::inst()->create($name);
		$this->styleguide_service->setURL($url);
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
		Requirements::javascript('//google-code-prettify.googlecode.com/svn/loader/run_prettify.js?skin=desert');
	}

	/**
	 * Load a yml fixture file if defined into an {@link StyleGuideFixtureFactory}.
	 * Used to populate templates.
	 */
	public function loadFixture() {
		$fixtureFile = 'mysite/_config/styleguide.yml';

		$realFile = realpath(BASE_PATH.'/'.$fixtureFile);
		$baseDir = realpath(Director::baseFolder());
		if(!$realFile || !file_exists($realFile)) {
			return;
		} else if(substr($realFile,0,strlen($baseDir)) != $baseDir) {
			return;
		} else if(substr($realFile,-4) != '.yml') {
			return;
		}

		$factory = Injector::inst()->create('StyleGuideFixtureFactory');
		$blueprint = Injector::inst()->create('StyleGuideBlueprint', 'StyleGuide', 'StyleGuide');
		$factory->define('StyleGuide', $blueprint);
		$fixture = Injector::inst()->create('YamlFixture', $fixtureFile);
		$fixture->writeInto($factory);

		$this->factory = $factory;
	}

	public function getFactory() {
		return $this->factory;
	}

	/**
	 * Get the main navigation to top level sections with additional `Home` and `All` links.
	 * @return ArrayList
	 */
	public function getNavigation() {
		$navigation = $this->styleguide_service->getNavigation();

		foreach($navigation as $section) {
			$section->request = $this->request;
		}
		
		// add the home link.
		$navigation->unshift(new ArrayData(array(
			'Link' => $this->Link(),
			'Title' => 'Home',
			'Description' => 'View the style-guide home.',
			'Active' => $this->isHome()
		)));

		// add the all link.
		$navigation->push(new ArrayData(array(
			'Link' => $this->Link('all'),
			'Title' => 'All',
			'Description' => 'View all style-guide sections.',
			'Active' => $this->isAll()
		)));

		return $navigation;
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

		if($action = $this->request->param('Action')) {
			if($action == 'all') {
				$sections = $this->styleguide_service->getSections();
			} else {
				$sections = $this->styleguide_service->getSectionChildren($action);
				$sections->unshift($this->styleguide_service->getSection($action)); // add the parent
			}
		}

		return $sections;
	}

	/**
	 * Check if on the `home` route.
	 * @return boolean
	 */
	public function isHome() {
		return $this->request->param('Action') == '';
	}

	/**
	 * Check if on the `All` route.
	 * @return boolean
	 */
	public function isAll() {
		return $this->request->param('Action') == 'all';
	}

	/**
	 * Get a link to the controller with optional action parameter.
	 * @param String $action
	 */
	public function Link($action = null) {
		return self::getLink($action);
	}

	/**
	 * Create a string suitable for a link in the style guide.
	 * @param  String $action
	 * @return String
	 */
	public static function getLink($action = null) {
	    return Controller::join_links('style-guide', $action);
	}

}
