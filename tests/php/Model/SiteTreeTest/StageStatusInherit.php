<?php

namespace SilverStripe\CMS\Tests\Model\SiteTreeTest;

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Dev\TestOnly;

class StageStatusInherit extends SiteTree implements TestOnly
{
	private static $table_name = 'SiteTreeTest_StageStatusInherit';

	public function getStatusFlags($cached = true)
	{
		$flags = parent::getStatusFlags($cached);
		$flags['inherited-class'] = "InheritedTitle";
		return $flags;
	}
}
