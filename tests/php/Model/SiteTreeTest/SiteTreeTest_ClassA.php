<?php

namespace SilverStripe\CMS\Tests\Model\SiteTreeTest;

use Page;
use SilverStripe\CMS\Tests\Model\SiteTreeTest\SiteTreeTest_ClassB;
use SilverStripe\Dev\TestOnly;

class SiteTreeTest_ClassA extends Page implements TestOnly
{
	private static $table_name = 'SiteTreeTest_ClassA';

	private static $need_permission = ['ADMIN', 'CMS_ACCESS_CMSMain'];

	private static $allowed_children = [
		SiteTreeTest_ClassB::class
	];
}
