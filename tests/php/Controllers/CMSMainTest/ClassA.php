<?php

namespace SilverStripe\CMS\Tests\Controllers\CMSMainTest;

use Page;
use SilverStripe\CMS\Tests\Controllers\CMSMainTest\ClassB;
use SilverStripe\Dev\TestOnly;
use SilverStripe\ORM\ValidationException;

class ClassA extends Page implements TestOnly
{
	private static $table_name = 'CMSMainTest_ClassA';

	private static $allowed_children = [
		ClassB::class
	];

	protected function onBeforeWrite()
	{
		parent::onBeforeWrite();

		if ($this->ClassName !== self::class) {
			throw new ValidationException("Class saved with incorrect ClassName");
		}
	}
}
