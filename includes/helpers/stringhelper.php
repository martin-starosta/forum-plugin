<?php
/**
 * Filename:  stringhelper.php
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
class StringHelper {

	/**
	 * Function creates SEO friendly text.
	 *
	 * @param String $text Text to changed to SEO friendly text.
	 * @return Text ready to be used in URL.
	 */
	public static function seo_friendly_text( $text ) {
		$text = strtolower( $text );

		$text = remove_accents( $text );

		$text = sanitize_title_with_dashes( $text );

		return $text;
	}

	public static function get_simplexmls_as_strings( $simple_xmls ) {
		$out = [];
		foreach ( $simple_xmls as $simple_xml ) {
			array_push( $out, (string) $simple_xml );
		};
		return $out;
	}
}
