<?php

namespace SilverStripe\CMS\Tests\Controllers;

use SilverStripe\CMS\Search\SearchForm;
use SilverStripe\ORM\Connect\MySQLSchemaManager;
use SilverStripe\ORM\Versioning\Versioned;
use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\ORM\Search\FulltextSearchable;
use SilverStripe\Core\Config\Config;
use SilverStripe\Assets\File;
use SilverStripe\Dev\SapphireTest;
use Page;

class ContentControllerSearchExtensionTest extends SapphireTest {

	public function testCustomSearchFormClassesToTest() {
		$page = new Page();
		$page->URLSegment = 'whatever';
		$page->Content = 'oh really?';
		$page->write();
		$page->copyVersionToStage(Versioned::DRAFT, Versioned::LIVE);
		$controller = new ContentController($page);
		$form = $controller->SearchForm();

		if (get_class($form) == SearchForm::class) {
			$this->assertEquals(
				array(File::class),
				$form->getClassesToSearch()
			);
		}
	}

	public function setUpOnce() {
		parent::setUpOnce();

		FulltextSearchable::enable(File::class);
	}

	/**
	 * FulltextSearchable::enable() leaves behind remains that don't get cleaned up
	 * properly at the end of the test. This becomes apparent when a later test tries to
	 * ALTER TABLE File and add fulltext indexes with the InnoDB table type.
	 */
	public function tearDownOnce() {
		parent::tearDownOnce();

		Config::inst()->update(
			File::class,
			'create_table_options',
			array(MySQLSchemaManager::ID => 'ENGINE=InnoDB')
		);
		File::remove_extension(FulltextSearchable::class);
	}

}
