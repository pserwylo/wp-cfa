<?php

namespace WP_CFA;

use function _doing_it_wrong;

class Shortcode {
	private $bom;

	public function __construct( BomObservations $bom = new BomObservations() ) {
		$this->bom = $bom;
	}

	public function date_time( $attributes ): string {
		if ( !isset( $attributes['format'] ) || !$attributes['format'] ) {
			_doing_it_wrong(
				__FUNCTION__,
				'No format provided to cfa_date_time.',
				'1.0.0',
			);
			return '';
		}

		// No real need to accept timestamp, but useful for testing.
		$timestamp = $attributes['timestamp'] ?? time();

		$format = $attributes['format'];

		$dateTime = @date( $format, $timestamp );
		$tag      = self::add_style_and_class_to_tag( 'span', $attributes );
		return "<$tag>$dateTime</span>";
	}

	public static function add_style_and_class_to_tag( $tag, $attributes ) {
		$class = $attributes['class'] ?? '';
		$style = $attributes['style'] ?? '';

		if ( $style ) {
			$tag .= ' style="' . esc_attr( trim( $style ) ) . '"';
		}

		if ( $class ) {
			$tag .= ' class="' . esc_attr( trim( $class ) ) . '"';
		}

		return $tag;
	}

	public function rating_string_for_district( $districtId, $day ) {
		$content = FdrRssFeed::fire_danger_rating_feed();
		return $content[ $day ]['districts'][ $districtId ]['rating'];
	}

	public function fire_danger_rating_image_url( $attributes ): string {
		$district = Utils::district_from_attributes( $attributes );
		$day      = Utils::day_from_attributes( $attributes );

		if ( !$district ) {
			return '';
		}

		$rating = $this->rating_string_for_district( $district, $day );

		return plugins_url( "public/images/rating-{$rating}.png", __DIR__ );
	}

	public function fire_danger_rating_image_tag( $attributes ): string {
		$url = self::fire_danger_rating_image_url( $attributes );

		if ( !$url ) {
			return '';
		}

		$tag = self::add_style_and_class_to_tag( 'img', $attributes );

		return '<' . $tag . ' src="' . esc_url( $url ) . '" />';
	}

	public function fire_danger_rating_text( $attributes ): string {
		$district = Utils::district_from_attributes( $attributes );
		$day      = Utils::day_from_attributes( $attributes );

		if ( !$district ) {
			return '';
		}

		$rating              = strtoupper( $this->rating_string_for_district( $district, $day ) );
		$attributes['class'] = 'wp-cfa-rating wp-cfa-rating--' . esc_attr( strtolower( $rating ) ) . ' ' . esc_attr( $attributes['class'] ?? '' );

		$tag = self::add_style_and_class_to_tag( 'span', $attributes );

		return "<$tag>$rating</span>";
	}

	public function weather_observation_temperature_number(): string {
		$observation = $this->bom->get_latest_observation();
		$temp        = $observation['air_temp'] ?? false;

		return false === $temp ? '' : $temp;
	}

	public function weather_observation_temperature_string( $attributes ): string {
		$observation = $this->bom->get_latest_observation();
		$temp        = $observation['air_temp'] ?? false;

		if ( false === $temp ) {
			return '';
		}

		$string = $temp . '°';
		$tag    = self::add_style_and_class_to_tag( 'span', $attributes );
		return "<$tag>$string</span>";
	}

	public function weather_observation_temperature_rounded_string( $attributes ): string {
		$observation = $this->bom->get_latest_observation();
		$temp        = $observation['air_temp'] ?? false;

		if ( false === $temp ) {
			return '';
		}

		$string = round( $temp ) . '°';
		$tag    = self::add_style_and_class_to_tag( 'span', $attributes );
		return "<$tag>$string</span>";
	}
}
