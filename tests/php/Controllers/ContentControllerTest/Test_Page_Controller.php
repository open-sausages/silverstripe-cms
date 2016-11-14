<?php

namespace SilverStripe\CMS\Tests\Controllers\ContentControllerTest;

use Page_Controller;
use SilverStripe\Dev\TestOnly;

class Test_Page_Controller extends Page_Controller implements TestOnly
{

	private static $allowed_actions = array(
		'second_index'
	);

	public function index()
	{
		return $this->Title;
	}

	public function second_index()
	{
		return $this->index();
	}

}
