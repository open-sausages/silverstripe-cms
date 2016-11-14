<?php

namespace SilverStripe\CMS\Tests\Model\SiteTreeTest;

use Page;
use SilverStripe\Dev\TestOnly;

class PageNode extends Page implements TestOnly
{
	private static $table_name = 'SiteTreeTest_PageNode';
}
