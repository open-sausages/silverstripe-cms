<?php

namespace SilverStripe\CMS\Tests\Model\SiteTreeTest;

use Page_Controller;
use SilverStripe\Dev\TestOnly;

class Conflicted_Controller extends Page_Controller implements TestOnly
{
	private static $allowed_actions = array(
		'conflicted-action'
	);

	public function hasActionTemplate($template)
	{
		if ($template == 'conflicted-template') {
			return true;
		} else {
			return parent::hasActionTemplate($template);
		}
	}

}
