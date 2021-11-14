<?php 

/**
 * This file is part of the HelperFunctions package.
 * 
 * (c) Marc Witteveen <marc.witteveen@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MarcWitteveen\HelperFunctions;

/**
 * Helper functions
 */
class HelperFunctions {


	public static function DayOfTheWeek() {
		$days = array(
			'Sunday',
			'Monday',
			'Tuesday',
			'Wednesday',
			'Thursday',
			'Friday',
			'Saturday',
		);
		$dayNumber = (integer) date('w');
		return $days[$dayNumber];
	}

	public static function Day($timezone = 'America/New_York') {
		$objCurrentTime = new DateTime();
		$objCurrentTime->setTimezone(new DateTimeZone($timezone));
		return $objCurrentTime->format("l");
	}

	public static function GetParameter($request, $parameters) 
	{
		foreach($parameters as $param) {
			if (array_key_exists($param,$request)) {
				return 	$request[$param];
			}	
		}
		return "";
	}

	public static function GetPageUrl($return = null) 
	{
		$protocol = 'http://';
		if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
			  $protocol = 'https://';
			} else {
			  $protocol = 'http://';
			}

		$requestUrl = str_replace("index.php", "", $_SERVER['REQUEST_URI']);
		$requestUrl = strtok($requestUrl, '?');

		$dataset = [];
		$dataset["url"] = $protocol . $_SERVER['SERVER_NAME'] . $requestUrl;
		$dataset["nonsecure"] = "http://" . $_SERVER['SERVER_NAME'] . $requestUrl;
		$dataset["secure"] = "https://" . $_SERVER['SERVER_NAME'] . $requestUrl;

		if (empty($return)) {
			return $dataset;	
		} else {
			return $dataset[$return];
		}
	}

	public static function WebpSupport()
	{
		if (strpos( $_SERVER['HTTP_ACCEPT'], 'image/webp') !== false) {
	    	return true;
		} else {
			return false;
		}
	}

	public static function getFolder() 
	{
		return strtolower(basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));
	}
}