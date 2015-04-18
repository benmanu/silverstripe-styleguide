<?php
class StyleGuideController extends ContentController {

	protected $service;

	private static $sg_service = '';

	private static $css_base = array();

	private static $css_files = array();

	public function init() {
		parent::init();

		$this->setService($this->config()->sg_service, $this->config()->css_base);
		$this->setRequirements();	
	}

	/**
	 * Set the styleguide service on init.
	 */
	public function setService($name, $url) {
		$this->service = Injector::inst()->create($name);
		$this->service->setURL($url);
	}

	public function setRequirements() {
		// styleguide requirements
		Requirements::css(STYLEGUIDE_BASE . '/css/bootstrap.min.css');
		Requirements::css(STYLEGUIDE_BASE . '/css/styleguide.css');
		
		Requirements::javascript('//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.js');
		Requirements::javascript('//google-code-prettify.googlecode.com/svn/loader/run_prettify.js');
		Requirements::javascript(STYLEGUIDE_BASE . '/javascript/bootstrap.min.js');

		// theme requirements
		if($files = $this->config()->css_files) {
			foreach($files as $file) {
				Requirements::css($file);
			}
		}
	}

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

	public function getSubNavigation() {
		return $this->getSections();
	}

	public function getSections() {
		$sections = null;

		if($action = $this->request->param('Action')) {
			if($action == 'all') {
				$sections = $this->service->getSections();
			} elseif($action !== 'ish') {
				$sections = $this->service->getSectionChildren($action);
			
				// add the parent
				$sections->unshift($this->service->getSection($action));
			}
		}

		return $sections;
	}

	public function isHome() {
		return $this->request->param('Action') == '';
	}

	public function isAll() {
		return $this->request->param('Action') == 'all';
	}

	public function Link($action = null) {
		return self::getLink($action);
	}

	public static function getLink($action = null) {
	    return Controller::join_links('style-guide', $action);
	}

}
