<?php

namespace SilverStripe\CMS\Tests\Controllers\CMSMainTest;

use Page;
use SilverStripe\Dev\TestOnly;
use SilverStripe\ORM\HiddenClass;

class TestHiddenClass extends Page implements TestOnly, HiddenClass
{
	private static $table_name = 'CMSMainTest_HiddenClass';
}
