<?php
/**
 * @package cms
 * @subpackage tests
 */

class CmsReportsTest extends SapphireTest {

	protected static $fixture_file = 'CmsReportsTest.yml';

	private static $daysAgo = 14;
	
	public function setUp() {
		parent::setUp();

		// set the dates by hand: impossible to set via yml
		$afterThreshold = strtotime('-'.(self::$daysAgo-1).' days', strtotime('31-06-2009 00:00:00'));
		$beforeThreshold = strtotime('-'.(self::$daysAgo+1).' days', strtotime('31-06-2009 00:00:00'));

		$after = $this->objFromFixture('SiteTree', 'after');
		$before = $this->objFromFixture('SiteTree', 'before');
		
		DB::query("UPDATE \"SiteTree\" SET \"Created\"='2009-01-01 00:00:00', \"LastEdited\"='".date('Y-m-d H:i:s', $afterThreshold)."' WHERE \"ID\"='".$after->ID."'");
		DB::query("UPDATE \"SiteTree\" SET \"Created\"='2009-01-01 00:00:00', \"LastEdited\"='".date('Y-m-d H:i:s', $beforeThreshold)."' WHERE \"ID\"='".$before->ID."'");
	}

	public function testRecentlyEdited() {
		SS_Datetime::set_mock_now('31-06-2009 00:00:00');

		$after = $this->objFromFixture('SiteTree', 'after');
		$before = $this->objFromFixture('SiteTree', 'before');

		$r = new RecentlyEditedReport();
		
		// check if contains only elements not older than $daysAgo days
		$this->assertNotNull($r->records(array()));
		$this->assertContains($after->ID, $r->records(array())->column('ID'));
		$this->assertNotContains($before->ID, $r->records(array())->column('ID'));
		
		SS_DateTime::clear_mock_now();
	}
}
