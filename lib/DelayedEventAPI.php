<?php

namespace torchlighttechnology;

require 'Error.php';

/**
 * TTG New Delayed Event PHP Client
 * @author slobo
 */

class DelayedEventAPI
{
	const HTTP_POST = 'POST';
	const HTTP_GET = 'GET';

	protected $api_host;
	protected $callback_uri;
	protected $parameters;
	protected $fire_date;

	public function __construct($api_host, $callback_uri = null, $parameters = null, $fire_date = null)
	{
		$this->api_host = $api_host;
		$this->callback_uri = $callback_uri;
		$this->parameters = $parameters;
		$this->fire_date = $fire_date;
	}


	/**
	 * Create Event
	 *
	 * @return array API response object.
	 */
	public function create_event()
	{
		$endpoint = '/delayed-events/create';
		$payload = array(
			'callback_uri' => $this->callback_uri,
			'parameters' => $this->parameters,
			'fire_date' => $this->format_date($this->fire_date)
		);

		return $this->api_request($endpoint, self::HTTP_POST, $payload, 'create');
	}


	/**
	 * Format Date
	 * converts the date string (YYYY-MM-DD HH:MM:SS) to array
	 *
	 * @return array [year, month, day, hour, minute]
	 */
	private function format_date($date)
	{
		return [
			'year' => date('Y', strtotime($date)),
			'month' => date('m', strtotime($date)),
			'day' => date('d', strtotime($date)),
			'hour' => date('H', strtotime($date)),
			'minute' => date('i', strtotime($date))
		];
	}


	/**
	 * Fire Events
	 *
	 * @return array API response object.
	 */
	public function fire_events()
	{
		$endpoint = '/delayed-events/fire-events';
		return $this->api_request($endpoint, self::HTTP_GET);
	}


	/**
	 * Remove Delayed Event
	 *
	 * @return array API response object.
	 */
	public function remove_event()
	{
		$endpoint = '/delayed-events/remove';
		return $this->api_request($endpoint, self::HTTP_POST, $this->parameters, 'remove');
	}


	protected function api_request($endpoint, $request = 'POST', $payload = null, $action = null)
	{
		$path = $this->api_host.$endpoint;
		$http_headers = null;

		switch ($action) {
			case 'create':
				$http_headers = ['Content-Type: application/x-www-form-urlencoded'];
				$payload = http_build_query($payload);
				break;

			case 'remove':
				$http_headers = [
					'Content-Type: application/json',
					'Content-Length: '.strlen($payload)
				];
				break;
		}

		$ch = curl_init($path);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request);
		if (!empty($http_headers)) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $http_headers);
		}
		if (!empty($payload)) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
		}
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
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
