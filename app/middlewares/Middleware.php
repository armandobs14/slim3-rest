<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use \Models\Access as Access;
use \Models\ExceptionModel as ExceptionModel;

/**
 *
 */
class Middleware
{
	/**
	 * Validate user from headers
	 */
	public static function auth($request, $response, $next)
	{
		$token = $request->getHeader('x-auth-token')[0];
		$userid = $request->getHeader('x-auth-uid')[0];

		$access = \Access::find($userid);
		if (!$token || !$userid) {
			return $response->withStatus(400);
		} else if ($token == $access->token) {
			$response = $next($request, $response);
		} else {
			return $response->withStatus(401);
		}

		return $response;
	}

	/**
	 * Force json header
	 */
	public static function json_encode($request, $response, $next)
	{
		$response = $next($request, $response);
		$response = $response->withHeader('Content-Type', 'application/json');
		return $response;
	}

	/**
	 * Decode json input
	 */
	public static function json_decode($request, $response, $next)
	{
		// add media parser
		$request->registerMediaTypeParser(
			"application/json",
			function ($input) {
				return json_decode($input, true);
			}
		);

		return $next($request, $response);
	}

	/**
	 * Exception handler
	 */
	public  static function exception_handler($request, $response, $next)
	{
		try {
			$response = $next($request, $response);
		} catch (Exception $e) {
			$response = $response->withStatus(500);
			// $response = $response->write("Ops! Por favor tente mais tarde.");
			// $response = $response->write($e->getMessage());

			// Saving exception
		} finally {
			return $response;
		}
	}

	/**
	 *  API metadata
	 */
	public static function metadata($request, $response, $next)
	{
		/**
		 *   Only want to process JSON response on outbound Middleware
		 */
		$response = $next($request, $response);
		/**
		 *   interrogate Body of response to see if valid JSON
		 */
		$headers = $response->getHeaders();
		$body = $response->getBody();
		// IF body not JSON then return original response
		if (empty(json_decode($body))) return $response;
		/**
		 * reset response/body to response to prepend with while(1); to json
		 *   SEE:
		 *   @link http://stackoverflow.com/questions/2669690/why-does-google-prepend-while1-to-their-json-responses
		 */
		$response = $response->withBody(
			new Body(fopen('php://temp', 'r+'))
		);

		$data = new stdClass();
		$data->meta = (object) [
			'version' => API_VERSION,
			'status' => $response->getStatusCode()
		];

		$data->data = json_decode($body);

		// re-write body with prefaced while(1);
		$response->write(json_encode($data));
		// reset header
		$response = $response->withAddedHeader('Content-Type', 'application/json');
		return $response;
	}
}
