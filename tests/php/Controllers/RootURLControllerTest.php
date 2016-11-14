<?php

namespace SilverStripe\CMS\Tests\Controllers;

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\CMS\Controllers\RootURLController;
use SilverStripe\Core\Config\Config;
use SilverStripe\Dev\SapphireTest;

class RootURLControllerTest extends SapphireTest {
	protected static $fixture_file = 'RootURLControllerTest.yml';

	public function testGetHomepageLink() {
		$default = $this->objFromFixture('Page', 'home');

		SiteTree::config()->nested_urls = false;
		$this->assertEquals('home', RootURLController::get_homepage_link());
		Config::inst()->update(SiteTree::class, 'nested_urls', true);
		$this->assertEquals('home', RootURLController::get_homepage_link());
	}

}
