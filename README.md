# SilverStripe FileListener

Allows you to register for notifications about changes to File or Image objects and their subclasses.

## Usage

```php
     FileListenerDecorator::register_listener(ClassName, static_method_name, event_type);
```

## Event Types:

You can register a listener for any of these events, or use `all` to listen to any event type.

- `create`
- `update`
- `delete`
- `all`

Your class provides a static method which will receive the updated record AFTER writing. Example:

```php
    public static function on_file_notify($file, $event)
```

This will be called for each changed `File` (or subclass) object, with `$event` set to the relevant event type (`create`|`update`|`delete`).

This static method may inspect the `changedFields` and decide how to process the changes.

**CAVEAT:** Do not write() any File objects within your static method, results are not guaranteed to be pretty!