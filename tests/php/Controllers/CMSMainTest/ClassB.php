<?php

namespace SilverStripe\CMS\Tests\Controllers\CMSMainTest;

use Page;
use SilverStripe\Dev\TestOnly;
use SilverStripe\ORM\ValidationException;

class ClassB extends Page implements TestOnly
{
	private static $table_name = 'CMSMainTest_ClassB';

	protected function onBeforeWrite()
	{
		parent::onBeforeWrite();

		if ($this->ClassName !== self::class) {
			throw new ValidationException("Class saved with incorrect ClassName");
		}
	}

}
