<?php

/**
 * Decorates {@see File} with ErrorPage support
 */

use SilverStripe\ORM\DataExtension;
class ErrorPageFileExtension extends DataExtension {

	/**
	 * Used by {@see File::handle_shortcode}
	 *
	 * @param int $statusCode HTTP Error code
	 * @return DataObject Substitute object suitable for handling the given error code
	 */
	public function getErrorRecordFor($statusCode) {
		return ErrorPage::get()->filter("ErrorCode", $statusCode)->first();
	}

}
