<?php
/**
 * A flexible and generic File event Listener system
 *
 * Authors:
 *		Frank Winkelmann <spliff.splendor@gmail.com>
 * 		Luke Hudson <lukeletters@gmail.com>
 *
 * Allows you to register for notifications about changes to File/Image objects
 * and their subclasses.
 *
 * Usage:
 * 	FileListenerDecorator::register_listener(ClassName, static_method_name, event_type);
 *
 * Event Types:
 *
 *		create
 * 		update
 * 		delete
 * 		all
 *
 * Your class provides a static method which will receive the updated record
 * AFTER writing.
 * Example:
 *
 * 		public static function on_file_notify($file, $event)
 *
 * This will be called for each changed File (or subclass) object, with $event
 * set to the relevant event type (as shown above)
 *
 * This static method may inspect the changedFields and decide how to process
 * the changes.
 *
 * CAVEAT:  Dont write() File objects within your static method, results are
 * unknown!
 */

class FileListenerDecorator extends DataObjectDecorator {
	protected static $listeners = array();

	/**
	 * Register your class to receive File event notifications.
	 * 
	 * @param string $className -- your class
	 * @param string $callback  -- Name of static method to call
	 * @param string $eventType -- One of the Event Types listed above (create/update/delete/all)
	 */
	public static function register_listener($className, $callback, $eventType = 'all') {
		self::$listeners[$className] = $callback;
		if ($eventType != 'all') {
			self::$listeners[$className][$eventType] = $callback;
		} else {
			foreach(explode(' ','create update delete') as $type) {
				self::$listeners[$className][$type] = $callback;
			}
		}
	}


	/**
	 * Fire the notifcations to registered listeners
	 * @param File $file
	 * @param string $event 
	 */
	protected function notify($file, $event) {
		foreach(self::$listeners as $class => $method) {
			$ret = call_user_func(array($class, $method), $file, $event);
			// TODO: do we do anything with return value?
		}
	}

	/**
	 * Called when files are created by upload.
	 * Fire 'create' event.
	 */
	public function onAfterUpload() {
		$parent = class_parents($this);
		if (is_array($parent)) {
			$parent = array_shift($parent);
			if (method_exists($parent, 'onAfterUpload')) {
				parent::onAfterUpload();
			}
		}
		$this->notify($this->owner, 'create');
	}


	/**
	 * Called when files are removed.
	 * Fire 'delete' event.
	 */
	public function onAfterDelete() {
		parent::onAfterDelete();
		$this->notify($this->owner, 'delete');
	}


	/**
	 * Called when files are updated.
	 * Fire 'update' event.
	 */
	public function onAfterWrite() {
		parent::onAfterWrite();
		$this->notify($this->owner, 'update');
	}
}
