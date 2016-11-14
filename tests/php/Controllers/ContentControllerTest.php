<?php

namespace SilverStripe\CMS\Tests\Controllers;

use SilverStripe\Dev\Debug;
use SilverStripe\ORM\Versioning\Versioned;
use SilverStripe\CMS\Controllers\RootURLController;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\Core\Config\Config;
use SilverStripe\Control\Director;
use SilverStripe\Control\HTTPResponse_Exception;
use SilverStripe\Dev\FunctionalTest;
use Page;

class ContentControllerTest extends FunctionalTest {

	protected static $fixture_file = 'ContentControllerTest.yml';

	protected static $use_draft_site = true;

	protected static $disable_themes = true;

	protected $extraDataObjects = [
		ContentControllerTest\Test_Page::class,
		ContentControllerTest\TestPage::class,
		ContentControllerTest\TestPageWithoutController::class,
	];

	/**
	 * Test that nested pages, basic actions, and nested/non-nested URL switching works properly
	 */

	public function testNestedPages() {
		RootURLController::reset();
		Config::inst()->update(SiteTree::class, 'nested_urls', true);

		$this->assertEquals('Home Page', $this->get('/')->getBody());
		$this->assertEquals('Home Page', $this->get('/home/index/')->getBody());
		$this->assertEquals('Home Page', $this->get('/home/second-index/')->getBody());

		$this->assertEquals('Second Level Page', $this->get('/home/second-level/')->getBody());
		$this->assertEquals('Second Level Page', $this->get('/home/second-level/index/')->getBody());
		$this->assertEquals('Second Level Page', $this->get('/home/second-level/second-index/')->getBody());

		$this->assertEquals('Third Level Page', $this->get('/home/second-level/third-level/')->getBody());
		$this->assertEquals('Third Level Page', $this->get('/home/second-level/third-level/index/')->getBody());
		$this->assertEquals('Third Level Page', $this->get('/home/second-level/third-level/second-index/')->getBody());

		RootURLController::reset();
		SiteTree::config()->nested_urls = false;

		$this->assertEquals('Home Page', $this->get('/')->getBody());
		$this->assertEquals('Home Page', $this->get('/home/')->getBody());
		$this->assertEquals('Home Page', $this->get('/home/second-index/')->getBody());

		$this->assertEquals('Second Level Page', $this->get('/second-level/')->getBody());
		$this->assertEquals('Second Level Page', $this->get('/second-level/index/')->getBody());
		$this->assertEquals('Second Level Page', $this->get('/second-level/second-index/')->getBody());

		$this->assertEquals('Third Level Page', $this->get('/third-level/')->getBody());
		$this->assertEquals('Third Level Page', $this->get('/third-level/index/')->getBody());
		$this->assertEquals('Third Level Page', $this->get('/third-level/second-index/')->getBody());
	}

	/**
	 * Tests {@link ContentController::ChildrenOf()}
	 */
	public function testChildrenOf() {
		$controller = new ContentController();

		Config::inst()->update(SiteTree::class, 'nested_urls', true);

		$this->assertEquals(1, $controller->ChildrenOf('/')->Count());
		$this->assertEquals(1, $controller->ChildrenOf('/home/')->Count());
		$this->assertEquals(2, $controller->ChildrenOf('/home/second-level/')->Count());
		$this->assertEquals(0, $controller->ChildrenOf('/home/second-level/third-level/')->Count());

		SiteTree::config()->nested_urls = false;

		$this->assertEquals(1, $controller->ChildrenOf('/')->Count());
		$this->assertEquals(1, $controller->ChildrenOf('/home/')->Count());
		$this->assertEquals(2, $controller->ChildrenOf('/second-level/')->Count());
		$this->assertEquals(0, $controller->ChildrenOf('/third-level/')->Count());
	}

	public function testDeepNestedURLs() {
		Config::inst()->update(SiteTree::class, 'nested_urls', true);

		$page = new Page();
		$page->URLSegment = 'base-page';
		$page->write();

		for($i = 0; $i < 10; $i++) {
			$parentID = $page->ID;

			$page = new ContentControllerTest\Test_Page();
			$page->ParentID = $parentID;
			$page->Title      = "Page Level $i";
			$page->URLSegment = "level-$i";
			$page->write();

			$relativeLink = Director::makeRelative($page->Link());
			$this->assertEquals($page->Title, $this->get($relativeLink)->getBody());
		}


		SiteTree::config()->nested_urls = false;
	}

	public function testViewDraft(){

		// test when user does not have permission, should get login form
		$this->logInWithPermission('EDITOR');
		try {
			$response = $this->get('/contact/?stage=Stage');
		} catch(HTTPResponse_Exception $responseException) {
			$response = $responseException->getResponse();
		}

		$this->assertEquals('403', $response->getstatusCode());


		// test when user does have permission, should show page title and header ok.
		$this->logInWithPermission('ADMIN');
		$this->assertEquals('200', $this->get('/contact/?stage=Stage')->getstatusCode());


	}

	public function testLinkShortcodes() {
		$linkedPage = new SiteTree();
		$linkedPage->URLSegment = 'linked-page';
		$linkedPage->write();
		$linkedPage->copyVersionToStage(Versioned::DRAFT, Versioned::LIVE);

		$page = new SiteTree();
		$page->URLSegment = 'linking-page';
		$page->Content = sprintf('<a href="[sitetree_link,id=%s]">Testlink</a>', $linkedPage->ID);
		$page->write();
		$page->copyVersionToStage(Versioned::DRAFT, Versioned::LIVE);

		$this->assertContains(
			sprintf('<a href="%s">Testlink</a>', $linkedPage->Link()),
			$this->get($page->RelativeLink())->getBody(),
			'"sitetree_link" shortcodes get parsed properly'
		);
	}


	/**
	 * Tests that {@link ContentController::getViewer()} chooses the correct templates.
	 *
	 * @covers ContentController::getViewer()
	**/
	public function testGetViewer() {

		$self = $this;
		$this->useTestTheme(__DIR__, 'controllertest', function() use ($self) {

			// Test a page without a controller (ContentControllerTest_PageWithoutController.ss)
			$page = new ContentControllerTest\TestPageWithoutController();
			$page->URLSegment = "test";
			$page->write();
			$page->copyVersionToStage(Versioned::DRAFT, Versioned::LIVE);

			$response = $self->get($page->RelativeLink());
			$self->assertEquals("TestPageWithoutController", trim($response->getBody()));

			// // This should fall over to user Page.ss
			$page = new ContentControllerTest\TestPage();
			$page->URLSegment = "test";
			$page->write();
			$page->copyVersionToStage(Versioned::DRAFT, Versioned::LIVE);

			$response = $self->get($page->RelativeLink());
			$self->assertEquals("Page", trim($response->getBody()));


			// Test that the action template is rendered.
			$page = new ContentControllerTest\TestPage();
			$page->URLSegment = "page-without-controller";
			$page->write();
			$page->copyVersionToStage(Versioned::DRAFT, Versioned::LIVE);

			$response = $self->get($page->RelativeLink("test"));
			$self->assertEquals("TestPage_test", trim($response->getBody()));

			// Test that an action without a template will default to the index template, which is
			// to say the default Page.ss template
			$response = $self->get($page->RelativeLink("testwithouttemplate"));
			$self->assertEquals("Page", trim($response->getBody()));

			// Test that an action with a template will render the both action template *and* the
			// correct parent template
			$controller = new ContentController($page);
			$viewer = $controller->getViewer('test');
			$templateList = [
				ContentControllerTest\TestPage::class . '_test',
				'Page'
			];
			Debug::dump($viewer->templates());
			$self->assertEquals(
				__DIR__ . '/themes/controllertest/templates/'.ContentControllerTest\TestPage::class.'_test.ss',
				$viewer->templates()['main']
			);
		});
	}

}

// For testing templates
