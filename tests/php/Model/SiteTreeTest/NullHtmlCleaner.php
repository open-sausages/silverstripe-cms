<?php

namespace SilverStripe\CMS\Tests\Model\SiteTreeTest;

use SilverStripe\View\Parsers\HTMLCleaner;

class NullHtmlCleaner extends HTMLCleaner
{
	public function cleanHTML($html)
	{
		return $html;
	}
}
