<?php

namespace SilverStripe\CMS\Tests\Controllers\SilverStripeNavigatorTest;

use SilverStripe\CMS\Controllers\SilverStripeNavigatorItem;
use SilverStripe\Dev\TestOnly;
use SilverStripe\Security\Member;
use SilverStripe\Security\Permission;

class ProtectedTestItem extends SilverStripeNavigatorItem implements TestOnly
{
	public function getTitle()
	{
		return self::class;
	}

	public function getHTML()
	{
		return null;
	}

	public function canView($member = null)
	{
		if (!$member) {
			$member = Member::currentUser();
		}
		return Permission::checkMember($member, 'ADMIN');
	}
}
