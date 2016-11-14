<?php

namespace SilverStripe\CMS\Tests\Model\SiteTreeTest;

use Page;
use SilverStripe\Dev\TestOnly;

class AdminDenied extends Page implements TestOnly
{
	private static $table_name = 'SiteTreeTest_AdminDenied';

	private static $extensions = [
		AdminDeniedExtension::class
	];
}
