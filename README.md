# delayed-event-api
A PHP library to interact with TTG's task server's delayed events

Install with composer

```
composer require torchlighttechnology/delayed-event-api:"~2.0"
```

Usage in your project

```php
use torchlighttechnology\DelayedEventAPI;

$api = new DelayedEventAPI(
	'DELAYEDEVENTS_URL',
	'CALLBACK_URI',
	'PARAMETERS',
	'FIRE_DATE'
);
```

# Events

## Create a new event

```php
$response = $api->create_event();
```

## Fire events

```php
$response = $api->fire_events();
```

## Remove event

```php
$response = $api->remove_event();
```
