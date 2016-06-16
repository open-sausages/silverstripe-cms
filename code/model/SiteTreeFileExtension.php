<?php

/**
 * Extension applied to {@see File} object to track links to {@see SiteTree} records.
 *
 * {@see SiteTreeLinkTracking} for the extension applied to {@see SiteTree}
 *
 * Note that since both SiteTree and File are versioned, LinkTracking and ImageTracking will
 * only be enabled for the Stage record.
 *
 * @property File $owner
 *
 * @package cms
 * @subpackage model
 */

use SilverStripe\ORM\Versioning\Versioned;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DataExtension;
class SiteTreeFileExtension extends DataExtension {

	private static $belongs_many_many = array(
		'BackLinkTracking' => 'SiteTree.ImageTracking' // {@see SiteTreeLinkTracking}
	);

	/**
	 * Images tracked by pages are owned by those pages
	 *
	 * @config
	 * @var array
	 */
	private static $owned_by = array(
		'BackLinkTracking'
	);

	public function updateCMSFields(FieldList $fields) {
		$fields->insertAfter(
			ReadonlyField::create(
				'BackLinkCount',
				_t('AssetTableField.BACKLINKCOUNT', 'Used on:'),
				$this->BackLinkTracking()->Count() . ' ' . _t('AssetTableField.PAGES', 'page(s)')
			)
				->addExtraClass('cms-description-toggle')
				->setDescription($this->BackLinkHTMLList()),
			'LastEdited'
		);
	}

	/**
	 * Generate an HTML list which provides links to where a file is used.
	 *
	 * @return string
	 */
	public function BackLinkHTMLList() {
		$html = '<em>' . _t(
			'SiteTreeFileExtension.BACKLINK_LIST_DESCRIPTION',
			'This list shows all pages where the file has been added through a WYSIWYG editor.'
		) . '</em>';

		$html .= '<ul>';
		foreach ($this->BackLinkTracking() as $backLink) {
			// Add the page link and CMS link
			$html .= sprintf(
				'<li><a href="%s" target="_blank">%s</a> &ndash; <a href="%s">%s</a></li>',
				Convert::raw2att($backLink->Link()),
				Convert::raw2xml($backLink->MenuTitle),
				Convert::raw2att($backLink->CMSEditLink()),
				_t('SiteTreeFileExtension.EDIT', 'Edit')
			);
		}
		$html .= '</ul>';

		return $html;
	}

	/**
	 * Extend through {@link updateBackLinkTracking()} in your own {@link Extension}.
	 *
	 * @return ManyManyList
	 */
	public function BackLinkTracking() {
		if(class_exists("Subsite")){
			$rememberSubsiteFilter = Subsite::$disable_subsite_filter;
			Subsite::disable_subsite_filter(true);
		}

		$links = $this->owner->getManyManyComponents('BackLinkTracking');
		$this->owner->extend('updateBackLinkTracking', $links);

		if(class_exists("Subsite")){
			Subsite::disable_subsite_filter($rememberSubsiteFilter);
		}

		return $links;
	}

	/**
	 * @todo Unnecessary shortcut for AssetTableField, coupled with cms module.
	 *
	 * @return integer
	 */
	public function BackLinkTrackingCount() {
		$pages = $this->owner->BackLinkTracking();
		if($pages) {
			return $pages->Count();
		} else {
			return 0;
		}
	}

	/**
	 * Updates link tracking in the current stage.
	 */
	public function onAfterDelete() {
		// Skip live stage
		if(Versioned::get_stage() === Versioned::LIVE) {
			return;
		}

		// We query the explicit ID list, because BackLinkTracking will get modified after the stage
		// site does its thing
		$brokenPageIDs = $this->owner->BackLinkTracking()->column("ID");
		if($brokenPageIDs) {
			// This will syncLinkTracking on the same stage as this file
			$brokenPages = DataObject::get('SiteTree')->byIDs($brokenPageIDs);
			foreach($brokenPages as $brokenPage) {
				$brokenPage->write();
			}
		}
	}
}
