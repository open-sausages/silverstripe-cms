<?php

namespace SilverStripe\CMS\Tests\Model\SiteTreeTest;

use Page;
use SilverStripe\CMS\Tests\Model\SiteTreeTest\ExtensionA;
use SilverStripe\CMS\Tests\Model\SiteTreeTest\ExtensionB;
use SilverStripe\Dev\TestOnly;

class SiteTreeTest_ClassD extends Page implements TestOnly
{
	private static $table_name = 'SiteTreeTest_ClassD';

	// Only allows this class, no children classes
	private static $allowed_children = array(
		'*SilverStripe\\CMS\\Tests\\Model\\SiteTreeTest\\ClassC'
	);

	private static $extensions = [
		ExtensionA::class,
		ExtensionB::class,
	];

	public $canEditValue = null;

	public function canEdit($member = null)
	{
		return isset($this->canEditValue)
			? $this->canEditValue
			: parent::canEdit($member);
	}
}
