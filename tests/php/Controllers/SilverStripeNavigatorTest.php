<?php

namespace SilverStripe\CMS\Tests\Controllers;

use SilverStripe\CMS\Controllers\SilverStripeNavigatorItem_StageLink;
use SilverStripe\Security\Member;
use SilverStripe\CMS\Controllers\SilverStripeNavigator;
use SilverStripe\Dev\SapphireTest;

class SilverStripeNavigatorTest extends SapphireTest {

	protected static $fixture_file = 'CMSMainTest.yml';

	public function testGetItems() {
		$page = $this->objFromFixture('Page', 'page1');
		$navigator = new SilverStripeNavigator($page);

		$items = $navigator->getItems();
		$classes = array_map('get_class', $items->toArray());
		$this->assertContains(SilverStripeNavigatorItem_StageLink::class, $classes,
			'Adds default classes'
		);

		$this->assertContains(SilverStripeNavigatorTest\TestItem::class, $classes,
			'Autodiscovers new classes'
		);
	}

	public function testCanView() {
		$page = $this->objFromFixture('Page', 'page1');
		$admin = $this->objFromFixture(Member::class, 'admin');
		$navigator = new SilverStripeNavigator($page);

		// TODO Shouldn't be necessary but SapphireTest logs in as ADMIN by default
		$this->logInWithPermission('CMS_ACCESS_CMSMain');
		$items = $navigator->getItems();
		$classes = array_map('get_class', $items->toArray());
		$this->assertNotContains(SilverStripeNavigatorTest\ProtectedTestItem::class, $classes);

		$this->logInWithPermission('ADMIN');
		$items = $navigator->getItems();
		$classes = array_map('get_class', $items->toArray());
		$this->assertContains(SilverStripeNavigatorTest\ProtectedTestItem::class, $classes);
	}

}
