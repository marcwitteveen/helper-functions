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

use DateTime;
use DateTimeZone;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;

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
				return 	strip_tags($request[$param]);
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

	/**
	 * Test for WebP support
	 * @return boolean True if there is support else false
	 */
	public static function WebpSupport()
	{
		// Check if HTTP_ACCEPT is send
		if (isset($_SERVER['HTTP_ACCEPT'])) {
			if (strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false) {
	    		return true;
			} else {
				return false;
			}
		} else {
			return false;
		}		
	}

	public static function GetFolder() 
	{
		return strtolower(basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));
	}

	public static function ShowWhiteList($email_address, $whitelist_domain_names)
	{
		$host = strtolower(substr($email_address, strpos($email_address, '@') + 1));
		$domain = explode('.', $host)[0];
		$show_whitelist = in_array($domain, $whitelist_domain_names);
		return $show_whitelist;
	}

	public static function FixTime($time, $add_seconds = true)
	{

		switch(substr_count($time, ':')) {
			case 2:
				return $time;
			case 1:
				if ($add_seconds === true) {
	  				$time .= ":00";	
	  			}
	  			return $time;
	  		case 0:
	  			$tmp = @str_split($time, strlen($time)-2);
		  		$return_value = (string) $tmp[0] . ":" . $tmp[1];
		  		if ($add_seconds) {
		  			$return_value .= ":00";	
		  		}
		  		return $return_value;
		}
	}

	public static function GetStartAndEndTime($start_date, $start_time, $webinar_length, $webinar_units = "seconds")
	{
		
		$standardTimeZoneEst = "America/New_York";
		$standardTimeZonePst = "America/Los_Angeles";

		$completeDateTime = (string) sprintf("%s %s", $start_date, $start_time);

		$tzEst  = new CarbonTimeZone($standardTimeZoneEst);
		$tzPst  = new CarbonTimeZone($standardTimeZonePst);
		$dt     = new Carbon($completeDateTime, $tzEst);

		$return = [];
		$return["formated_webinar_date"] = $dt->format('l F j, Y');
		$return["est_time"] = $dt->format('ga');
		$return["est_abbreviated_name"] = strtoupper($tzEst->getAbbreviatedName());
		$return["webinar_countdown_clock"] = $dt->format('Y-m-d H:i:s');
		$return["time_converter_datetime"] = $dt->format("Ymd") . "T" . $dt->format('H');
		$return["webinar_month"] = $dt->englishMonth;
		$return["webinar_day"] = $dt->englishDayOfWeek;
		$return["webinar_day_number"] = $dt->day;
		$eventWebinarStartDateTime = $dt->format('Y-m-d H:i');
		// Calculate the webinar end time
		$dt->add($webinar_length, $webinar_units);
		$eventWebinarEndDateTime = $dt->format('Y-m-d H:i');
		$return["webinar_start"] = DateTime::createFromFormat('Y-m-d H:i', $eventWebinarStartDateTime, new DateTimeZone($standardTimeZoneEst));
		$return["webinar_end"] = DateTime::createFromFormat('Y-m-d H:i', $eventWebinarEndDateTime, new DateTimeZone($standardTimeZoneEst));
		// PST calculations
		$dt->sub($webinar_length, $webinar_units);
		$dt->setTimezone($tzPst);
		$return["pst_time"] = $dt->format('ga');
		$return["pst_abbreviated_name"] = strtoupper($tzPst->getAbbreviatedName());
		return $return;
	}

	public static function YourTimezoneUrl($title, $date_time)
	{
		$title = urlencode(strip_tags($title));
		$return = sprintf("https://www.timeanddate.com/worldclock/fixedtime.html?msg=%s&iso=%s&p1=250", $title, $date_time);
		return $return; 
	}

	public static function FacebookShareLink($url)
	{
		$return = (string) sprintf("https://www.facebook.com/sharer.php?u=%s", $url);
		return $return;
	}

	public static function LinkedInShareLink($url)
	{
		$return = (string) sprintf("https://www.linkedin.com/sharing/share-offsite/?url=%s", $url);
		return $return;
	}

	public static function TwitterShareLink($url, $text = "")
	{
		$return = (string) sprintf("https://twitter.com/intent/tweet?url=%s&text=%s", $url, $text);
		return $return;
	}

	public static function EmailShareLink($subject = "", $body = "")
	{
		$return = (string) sprintf("mailto:subject=%s&body=%s", $subject, $body);
		return $return;
	}
}