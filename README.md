# delayed-event-api
A PHP library to interact with TTG's task server's delayed events

Install with composer

```
composer require torchlighttechnology/delayed-event-api:"~1.0"
```

Usage in your project

```php
use torchlighttechnology\DelayedEventAPI;

$api = new DelayedEventAPI(
	'USERNAME',
	'PASSWORD',
	'TASKSERVER_URL'
);
```

# Events

## Get events

```php
$response = $api->get_events();
```

## Get specific event by name

```php
$response = $api->find_event_by_name(
	$event_name // string event name
);
```

## View event by ID

```php
$response = $api->view_event(
	$event_id // int event id of the event to view
);
```

## Create new event

```php
$response = $api->create_event(
	$event_name // string event name
);
```

## Update event

```php
$response = $api->update_event(
	$event_id // int event id to update
	$event_name // string event name
);
```

## Delete event

```php
$response = $api->delete_event(
	$event_id // int event id to delete
);
```

# Delayed Events

## Get delayed events

```php
$response = $api->get_delayed_events();
```

## View delayed event by ID

```php
$response = $api->view_delayed_event(
	$delayed_event_id // int delayed event id to view
);
```

## Create delayed event

```php
$response = $api->create_delayed_event(
	$event_id // int event id to associate the delayed event to
	$params // array of parameters that will be converted to a json object that will be attached to this delayed event
	$fire_date // datetime of when the delayed event should be fired
);
```

## Update delayed event

```php
$response = $api->update_delayed_event(
	$delayed_event_id // int delayed event id to update
	$event_id // int event id associated with this delayed event
	$params // array of parameters that will be converted to a json object that will be attached to this delayed event
	$fire_date // datetime of when the delayed event should be fired
);
```

## Deleted delayed event

```php
$response = $api->delete_delayed_event(
	$delayed_event_id // int delayed event id to delete
);
```