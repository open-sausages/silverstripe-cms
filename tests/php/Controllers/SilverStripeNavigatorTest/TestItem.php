<?php

namespace SilverStripe\CMS\Tests\Controllers\SilverStripeNavigatorTest;

use SilverStripe\CMS\Controllers\SilverStripeNavigatorItem;
use SilverStripe\Dev\TestOnly;

class TestItem extends SilverStripeNavigatorItem implements TestOnly
{
	public function getTitle()
	{
		return self::class;
	}

	public function getHTML()
	{
		return null;
	}
}
