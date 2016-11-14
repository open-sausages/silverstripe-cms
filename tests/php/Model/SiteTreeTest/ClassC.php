<?php

namespace SilverStripe\CMS\Tests\Model\SiteTreeTest;

use Page;
use SilverStripe\Dev\TestOnly;

class ClassC extends Page implements TestOnly
{
	private static $allowed_children = array();

	private static $table_name = 'SiteTreeTest_ClassC';
}
