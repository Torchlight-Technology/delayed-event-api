# delayed-event-api
A PHP library to interact with TTG's delayed events service

Install with composer

```
composer require torchlighttechnology/delayed-event-api:"~2.0"
```

Usage in your project

```php
use torchlighttechnology\DelayedEventAPI;

$api = new DelayedEventAPI('DELAYEDEVENTS_URL');
```

# Events

## Create a new event

```php
$response = $api->create_event(
	$callback_uri,	// URL string
	$parameters,	// json string e.x. {"email":"test@test.com"}
	$fire_date	// date string in YYYY-MM-DD HH:MM:SS format
);
```

## Remove event

```php
$response = $api->remove_event(
	$parameters	// json string e.x. {"email":"test@test.com"}
);
```
