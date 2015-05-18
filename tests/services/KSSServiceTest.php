<?php
class KSSServiceTest extends SapphireTest {

	protected $service;

	public function setUp() {
		parent::setUp();

		$this->service = new KSSService();
		$this->service->setURL(BASE_PATH . '/styleguide/tests/fixtures/css/');
    }

    public function tearDown() {
    	parent::tearDown();
    }

    public function testGetNavigation() {
    	$navigation = $this->service->getNavigation();
    	$this->assertEquals($navigation->count(), 2);
    }

	public function testGetSections() {
		$this->assertEquals($this->service->getSections()->count(), 4);
	}

	public function testGetSectionChildren() {
		$reference = '2.0';
    	$children = $this->service->getSectionChildren($reference);
    	$this->assertEquals($children->count(), 1);

    	foreach($children as $child) {
	    	$this->assertEquals(substr($child->getReference(), 0, 1), substr($reference, 0, 1));
	    }
	}

}