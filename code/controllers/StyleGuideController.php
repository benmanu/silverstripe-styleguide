<?php
class StyleGuideController extends ContentController {

	/**
     * @var StyleGuide service
     */
	protected $service;

	/**
     * @config
     */
	private static $sg_service = '';

	/**
     * @config
     */
	private static $css_base = array();

	/**
     * @config
     */
	private static $css_files = array();

	public function init() {
		parent::init();

		$this->setService($this->config()->sg_service, Director::BaseFolder() . "/" . $this->config()->css_base);
		$this->setRequirements();	
	}

	/**
	 * Set the styleguide service on init.
	 * @param String $name The name of the styleguide service.
	 * @param String $url  Project base url.
	 */
	public function setService($name, $url) {
		$this->service = Injector::inst()->create($name);
		$this->service->setURL($url);
	}

	/**
	 * Set the styleguides css and js requirements.
	 */
	public function setRequirements() {
		// styleguide requirements
		Requirements::css(STYLEGUIDE_BASE . '/dist/css/bootstrap.min.css');
		Requirements::css(STYLEGUIDE_BASE . '/dist/css/styleguide.css');
		
		Requirements::javascript(STYLEGUIDE_BASE . '/dist/js/core.js');
		Requirements::javascript('//google-code-prettify.googlecode.com/svn/loader/run_prettify.js?skin=desert');

		// theme requirements
		if($files = $this->config()->css_files) {
			foreach($files as $file) {
				Requirements::css($file);
			}
		}
	}

	/**
	 * Get the main navigation to top level sections with additional `Home` and `All` links.
	 * @return ArrayList
	 */
	public function getNavigation() {
		$navigation = $this->service->getNavigation();

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
				$sections = $this->service->getSections();
			} else {
				$sections = $this->service->getSectionChildren($action);
				$sections->unshift($this->service->getSection($action)); // add the parent
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
