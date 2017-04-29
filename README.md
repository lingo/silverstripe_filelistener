# SilverStripe FileListener

Allows you to register for notifications about changes to File or Image objects and their subclasses.

## Usage

```php
     FileListenerDecorator::register_listener(ClassName, static_method_name, event_type);
```

## Event Types:

- update
- delete
- all

Your class provides a static method which will receive the updated record AFTER writing. Example:

```php
    public static function on_file_notify($file, $event)
```

This will be called for each changed `File` (or subclass) object, with `$event` set to the relevant event type (as shown above)

This static method may inspect the `changedFields` and decide how to process the changes.

**CAVEAT:** Dont write() File objects within your static method, results are unknown!