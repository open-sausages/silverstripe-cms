<?php

namespace SilverStripe\CMS\Tests\Controllers\CMSMainTest;

use Page;
use SilverStripe\Dev\TestOnly;

class NotRoot extends Page implements TestOnly
{
	private static $table_name = 'CMSMainTest_NotRoot';

	private static $can_be_root = false;
}
