<?php

namespace SilverStripe\CMS\Tests\Model\SiteTreeTest;

use Page;
use SilverStripe\Dev\TestOnly;
use SilverStripe\ORM\HiddenClass;

class ClassE extends Page implements TestOnly, HiddenClass
{
	private static $table_name = 'SiteTreeTest_ClassE';
}
