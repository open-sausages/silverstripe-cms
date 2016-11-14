<?php

namespace SilverStripe\CMS\Tests\Model\SiteTreeTest;

use SilverStripe\Dev\TestOnly;
use SilverStripe\ORM\DataExtension;

/**
 * An extension that can even deny actions to admins
 */
class AdminDeniedExtension extends DataExtension implements TestOnly
{
	public function canCreate($member)
	{
		return false;
	}

	public function canEdit($member)
	{
		return false;
	}

	public function canDelete($member)
	{
		return false;
	}

	public function canAddChildren()
	{
		return false;
	}

	public function canView()
	{
		return false;
	}
}
