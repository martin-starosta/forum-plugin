<?php
/**
 * Filename:  datehelper.php
 *
 * @author    Martin Starosta<martin.starosta83@gmail.com>
 * @copyright 2017 Mayorsoft.eu
 * @license   GPL
 * @package   FP/Helpers
 * @see       https://kamforum.sk
 */

/**
 * Helper functions for handling strings
 */
class DateHelper {

	public static function is_older_than_days( $date, $number_of_days ) {
		$now = new \DateTime();
		return $date->diff($now)->days > $number_of_days;
	}
}
