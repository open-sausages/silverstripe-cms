<?php

namespace SilverStripe\CMS\Tests\Model\SiteTreeTest;

use Page;
use SilverStripe\Dev\TestOnly;

class NotRoot extends Page implements TestOnly
{
	private static $table_name = 'SiteTreeTest_NotRoot';
	private static $can_be_root = false;
}
