<?php

namespace torchlighttechnology;

require 'Error.php';

/**
 * TTG Delayed Event PHP Client
 * @author waffles
 */

class DelayedEventAPI
{
	const HTTP_POST = 'POST';
	const HTTP_GET = 'GET';
	const HTTP_PUT = 'PUT';
	const HTTP_DELETE = 'DELETE';

	protected $username;
	protected $password;
	protected $api_host;

	public function __construct($username, $password, $api_host)
	{
		$this->username = $username;
		$this->password = $password;
		$this->api_host = $api_host;
	}

	/**
	 * Get All Events
	 *
	 * @return array API response object.
	 */

	public function get_events()
	{
		$endpoint = 'events.json';
		return $this->api_request($endpoint, self::HTTP_GET);
	}

	/**
	 * View Specific Event
	 *
	 * @param int $event_id event id
	 * @return array API response object.
	 */

	public function view_event($event_id)
	{
		$endpoint = 'events/'.$event_id.'.json';
		return $this->api_request($endpoint, self::HTTP_GET);
	}

	/**
	 * Create Event
	 *
	 * @param string $name event name
	 * @return array API response object.
	 */

	public function create_event($name)
	{
		$endpoint = 'events/add.json';
		$payload = array(
			'name' => $name
		);

		return $this->api_request($endpoint, self::HTTP_POST, $payload);
	}

	/**
	 * Update Event
	 *
	 * @param int $event_id event id to update
	 * @param string $name event name
	 * @return array API respons object.
	 */

	public function update_event($event_id, $name)
	{
		$endpoint = 'events/edit/'.$event_id.'.json';
		$payload = array(
			'name' => $name
		);

		return $this->api_request($endpoint, self::HTTP_PUT, $payload);
	}

	/**
	 * Delete Event
	 *
	 * @param int $event_id event id to delete
	 * @return array API response object.
	 */

	public function delete_event($event_id)
	{
		$endpoint = 'events/delete/'.$event_id.'.json';
		return $this->api_request($endpoint, self::HTTP_DELETE);
	}

	/**
	 * Get All Delayed Events
	 *
	 * @return array API response object.
	 */

	public function get_delayed_events()
	{
		$endpoint = 'delayed-events.json';
		return $this->api_request($endpoint, self::HTTP_GET);
	}

	/**
	 * View Specific Delayed Event
	 *
	 * @param int $delayed_event_id delayed event id
	 * @return array API response object.
	 */

	public function view_delayed_event($delayed_event_id)
	{
		$endpoint = 'delayed-events/'.$delayed_event_id.'.json';
		return $this->api_request($endpoint, self::HTTP_GET);
	}

	/**
	 * Create Delayed Event
	 *
	 * @param int $event_id event id to associate the delayed event to
	 * @param array $params an array of fields to send along when delayed event fires
	 * @param date/time $fire_date the date/time of when the delayed event should fire
	 * @return array API response object.
	 */

	public function create_delayed_event($event_id, $params, $fire_date)
	{
		$endpoint = 'delayed-events/add.json';
		$payload = array(
			'event_id' => $event_id,
			'parameters' => json_encode($params),
			'fire_date' => $fire_date
		);

		return $this->api_request($endpoint, self::HTTP_POST, $payload);
	}

	/**
	 * Update Delayed Event
	 *
	 * @param int $delayed_event_id delayed event id
	 * @param int $event_id event id associated with this delayed event
	 * @param array $params an array of fields to send along when delayed event fires
	 * @param date/time $fire_date the date/time of when the delayed event should fire
	 * @return array API response object.
	 */

	public function update_delayed_event($delayed_event_id, $event_id, $params, $fire_date)
	{
		$endpoint = 'delayed-events/edit/'.$delayed_event_id.'.json';
		$payload = array(
			'event_id' => $event_id,
			'parameters' => json_encode($params),
			'fire_date' => $fire_date
		);

		return $this->api_request($endpoint, self::HTTP_PUT, $payload);
	}

	/**
	 * Delete Delayed Event
	 *
	 * @param int $delayed_event_id delayed event id
	 * @return array API response object.
	 */

	public function delete_delayed_event($delayed_event_id)
	{
		$endpoint = 'delayed-events/delete/'.$delayed_event_id.'.json';
		return $this->api_request($endpoint, self::HTTP_DELETE);
	}

	protected function build_path($endpoint)
	{
		$path = sprintf('%s', $endpoint);

		$path = sprintf('%s%s',
			$this->api_host,
			$path
		);

		return $path;
	}

	protected function api_request($endpoint, $request = 'POST', $payload = null)
	{
		$path = $this->build_path($endpoint);

		$ch = curl_init($path);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request);

		$payload_string = null;
		if ($payload) {
			$payload_string = json_encode($payload);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $payload_string);
		}

		if ($payload && ($request == 'POST' || $request == 'PUT')) {
			$http_headers = array(
				'Content-Type: application/json',
				'Content-Length: '.strlen($payload_string),
				'authorization: Basic '. base64_encode($this->username.':'.$this->password)
			);
		} else {
			$http_headers = array(
				'authorization: Basic '. base64_encode($this->username.':'.$this->password)
			);
		}

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $http_headers);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

		$code = null;
		try {
			$result = curl_exec($ch);
			$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$response = json_decode($result);

			if ($code != 200) {
				throw new API_Error('Request was not successful', $code, $result, $response);
			}
		} catch (API_Error $e) {
			$response = (object) array(
				'code' => $code,
				'status' => 'error',
				'success' => false,
				'exception' => $e
			);
		}

		curl_close($ch);

		return $response;
	}
}