<?php
/**
 * Publish items batch action.
 *
 * @package cms
 * @subpackage batchaction
 */

use SilverStripe\ORM\SS_List;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\Versioning\Versioned;
use SilverStripe\Security\Permission;

class CMSBatchAction_Publish extends CMSBatchAction {
	public function getActionTitle() {
		return _t('CMSBatchActions.PUBLISH_PAGES', 'Publish');
	}

	public function run(SS_List $pages) {
		return $this->batchaction($pages, 'publishRecursive',
			_t('CMSBatchActions.PUBLISHED_PAGES', 'Published %d pages, %d failures')
		);
	}

	public function applicablePages($ids) {
		return $this->applicablePagesHelper($ids, 'canPublish', true, false);
	}
}

/**
 * Unpublish items batch action.
 *
 * @package cms
 * @subpackage batchaction
 */
class CMSBatchAction_Unpublish extends CMSBatchAction {
	public function getActionTitle() {
		return _t('CMSBatchActions.UNPUBLISH_PAGES', 'Unpublish');
	}

	public function run(SS_List $pages) {
		return $this->batchaction($pages, 'doUnpublish',
			_t('CMSBatchActions.UNPUBLISHED_PAGES', 'Unpublished %d pages')
		);
	}

	public function applicablePages($ids) {
		return $this->applicablePagesHelper($ids, 'canUnpublish', false, true);
	}
}

/**
 * Archives a page, removing it from both live and stage
 *
 * @package cms
 * @subpackage batchaction
 */
class CMSBatchAction_Archive extends CMSBatchAction {

	public function getActionTitle() {
		return _t('CMSBatchActions.ARCHIVE', 'Archive');
	}

	public function run(SS_List $pages) {
		return $this->batchaction($pages, 'doArchive',
			_t('CMSBatchActions.ARCHIVED_PAGES', 'Archived %d pages')
		);
	}

	public function applicablePages($ids) {
		return $this->applicablePagesHelper($ids, 'canArchive', true, true);
	}

}

/**
 * Batch restore of pages
 * @package cms
 * @subpackage batchaction
 */
class CMSBatchAction_Restore extends CMSBatchAction {

	public function getActionTitle() {
		return _t('CMSBatchActions.RESTORE', 'Restore');
	}

	public function run(SS_List $pages) {
		// Sort pages by depth
		$pageArray = $pages->toArray();
		// because of https://bugs.php.net/bug.php?id=50688
		foreach($pageArray as $page) {
			$page->getPageLevel();
		}
		usort($pageArray, function($a, $b) {
			return $a->getPageLevel() - $b->getPageLevel();
		});
		$pages = new ArrayList($pageArray);

		// Restore
		return $this->batchaction($pages, 'doRestoreToStage',
			_t('CMSBatchActions.RESTORED_PAGES', 'Restored %d pages')
		);
	}

	/**
	 * {@see SiteTree::canEdit()}
	 *
	 * @param array $ids
	 * @return bool
	 */
	public function applicablePages($ids) {
		// Basic permission check based on SiteTree::canEdit
		if(!Permission::check(array("ADMIN", "SITETREE_EDIT_ALL"))) {
			return array();
		}

		// Get pages that exist in stage and remove them from the restore-able set
		$stageIDs = Versioned::get_by_stage($this->managedClass, 'Stage')->column('ID');
		return array_values(array_diff($ids, $stageIDs));
	}
}

/**
 * Delete items batch action.
 *
 * @package cms
 * @subpackage batchaction
 */
class CMSBatchAction_Delete extends CMSBatchAction {
	public function getActionTitle() {
		return _t('CMSBatchActions.DELETE_DRAFT_PAGES', 'Delete from draft site');
	}

	public function run(SS_List $pages) {
		$status = array(
			'modified'=>array(),
			'deleted'=>array(),
			'error'=>array()
		);

		foreach($pages as $page) {
			$id = $page->ID;

			// Perform the action
			if($page->canDelete()) $page->delete();
			else $status['error'][$page->ID] = true;

			// check to see if the record exists on the live site,
			// if it doesn't remove the tree node
			$liveRecord = Versioned::get_one_by_stage( 'SiteTree', 'Live', array(
				'"SiteTree"."ID"' => $id
			));
			if($liveRecord) {
				$status['modified'][$liveRecord->ID] = array(
					'TreeTitle' => $liveRecord->TreeTitle,
				);
			} else {
				$status['deleted'][$id] = array();
			}

		}

		return $this->response(_t('CMSBatchActions.DELETED_DRAFT_PAGES', 'Deleted %d pages from draft site, %d failures'), $status);
	}

	public function applicablePages($ids) {
		return $this->applicablePagesHelper($ids, 'canDelete', true, false);
	}
}
