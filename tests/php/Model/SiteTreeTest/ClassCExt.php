<?php

namespace SilverStripe\CMS\Tests\Model\SiteTreeTest;

use SilverStripe\CMS\Tests\Model\SiteTreeTest;
use SilverStripe\CMS\Tests\Model\SiteTreeTest\ClassC;
use SilverStripe\Dev\TestOnly;

class ClassCExt extends ClassC implements TestOnly
{
	private static $table_name = 'SiteTreeTest_ClassCext';

	// Override SiteTreeTest_ClassC definitions
	private static $allowed_children = [
		SiteTreeTest\SiteTreeTest_ClassB::class
	];
}
