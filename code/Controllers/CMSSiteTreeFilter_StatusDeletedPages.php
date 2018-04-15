<?php

namespace SilverStripe\CMS\Controllers;

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\ORM\ListInterface;
use SilverStripe\Versioned\Versioned;

/**
 * Filters pages which have a status "Deleted".
 */
class CMSSiteTreeFilter_StatusDeletedPages extends CMSSiteTreeFilter
{

    /**
     * @var string
     */
    protected $childrenMethod = "AllHistoricalChildren";

    /**
     * @var string
     */
    protected $numChildrenMethod = 'numHistoricalChildren';

    public static function title()
    {
        return _t(__CLASS__ . '.Title', 'Archived pages');
    }

    /**
     * Filters out all pages who's status is set to "Deleted".
     *
     * @see {@link SiteTree::getStatusFlags()}
     * @return ListInterface
     */
    public function getFilteredPages()
    {
        $pages = Versioned::get_including_deleted(SiteTree::class);
        $pages = $this->applyDefaultFilters($pages);

        $pages = $pages->filterByCallback(function (SiteTree $page) {
            // Doesn't exist on either stage or live
            return $page->isArchived();
        });
        return $pages;
    }
}
