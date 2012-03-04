<?php

/**
 * Shows a list of new files in the content
 */

class NewFilesPage extends Page {

	public static function on_file_notification($file, $event) {
		$page = DataObject::get_one('NewFilesPage');
		if ($page) {
			$page->Content = $page->Content . "<br/>" . $file->Filename  . " was {$event}d on " . SS_Datetime::now()->Nice();
			$page->write();
		}
	}
}

class NewFilesPage_Controller extends Page_Controller {

}

