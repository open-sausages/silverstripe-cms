<?php

namespace SilverStripe\CMS\Tests\Controllers\ContentControllerTest;

use Page_Controller;
use SilverStripe\Dev\TestOnly;

class TestPage_Controller extends Page_Controller implements TestOnly
{
	private static $allowed_actions = array(
		"test",
		"testwithouttemplate"
	);

	function testwithouttemplate()
	{
		return array();
	}
}
