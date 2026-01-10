<?php
/**
 * Plugin Name:     Wp Cfa
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     PLUGIN DESCRIPTION HERE
 * Author:          YOUR NAME HERE
 * Author URI:      YOUR SITE HERE
 * Text Domain:     wp-cfa
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Wp_Cfa
 */

namespace WP_CFA;

class Utils {
	public static $districts = [
		'central'               => 'Central',
		'eastgippsland'         => 'East Gippsland',
		'mallee'                => 'Mallee',
		'northcentral'          => 'North Central',
		'northeast'             => 'North East',
		'northerncountry'       => 'Northern Country',
		'southwest'             => 'South West',
		'westandsouthgippsland' => 'West and South Gippsland',
		'wimmera'               => 'Wimmera',
	];

	public static function district_to_id( $district ): string {
		return strtolower( preg_replace( '/\s/', '', $district ) );
	}

	public static function day_from_attributes( $attributes ): int {
		$day = 'today';
		if ( key_exists( 'day', $attributes ) ) {
			$day = $attributes['day'];
		}

		$valid_forecast_days = [ 'today', 'tomorrow', 0, 1, 2, 3 ];
		if ( !in_array( $day, $valid_forecast_days, true ) ) {
			_doing_it_wrong(
				__FUNCTION__,
				'Invalid "day" provided: "' . $day . '". Valid values are: empty (defaults to "today"), "today", "tomorrow", 0, 1, 2, 3.',
				'1.0.0',
			);
			return 0;
		}

		if ( 'today' === $day ) {
			$day = 0;
		} elseif ( 'tomorrow' === $day ) {
			$day = 1;
		}

		return $day;
	}

	/**
	 * Attempts to get a valid "district" from the shortcode $attributes.
	 *
	 * @see self::$districts
	 */
	public static function district_from_attributes( $attributes ): string {
		$district = $attributes['district'] ?? Settings::get_district() ?? '';

		if ( !isset( self::$districts[ $district ] ) ) {
			\_doing_it_wrong(
				__FUNCTION__,
				'Invalid "district" provided: "' . $district . '". Valid values are: ' . join( ', ', array_keys( self::$districts ) ),
				'1.0.0',
			);
		}

		return $district;
	}
}
