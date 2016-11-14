<?php

namespace SilverStripe\CMS\Tests\Model\SiteTreeTest;

use Page;
use SilverStripe\CMS\Tests\Model\SiteTreeTest\ClassC;
use SilverStripe\Dev\TestOnly;

class SiteTreeTest_ClassB extends Page implements TestOnly
{
	private static $table_name = 'SiteTreeTest_ClassB';

	// Also allowed subclasses
	private static $allowed_children = [ClassC::class];
}
