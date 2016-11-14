<?php

namespace SilverStripe\CMS\Tests\Model\SiteTreeTest;

use SilverStripe\Dev\TestOnly;
use SilverStripe\ORM\DataExtension;

class TestExtension extends DataExtension implements TestOnly
{
	public function augmentValidURLSegment()
	{
		return false;
	}

}
