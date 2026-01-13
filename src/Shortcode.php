<?php

namespace WP_CFA;

use function _doing_it_wrong;

class Shortcode {
	private $observations;

	private $forecasts;

	public function __construct( BomObservations $observations = new BomObservations(), BomForecasts $forecasts = new BomForecasts() ) {
		$this->observations = $observations;
		$this->forecasts    = $forecasts;
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

		$dateTime = @wp_date( $format, $timestamp );
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

	public function fire_danger_rating_class( $attributes ): string {
		$district = Utils::district_from_attributes( $attributes );
		$day      = Utils::day_from_attributes( $attributes );

		if ( !$district ) {
			return '';
		}

		$rating = strtoupper( $this->rating_string_for_district( $district, $day ) );
		return 'wp-cfa-rating--' . esc_attr( strtolower( $rating ) );
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
		$observation = $this->observations->get_latest_observation();
		$temp        = $observation['air_temp'] ?? false;

		return false === $temp ? '' : $temp;
	}

	public function weather_observation_temperature_string( $attributes ): string {
		$observation = $this->observations->get_latest_observation();
		$temp        = $observation['air_temp'] ?? false;

		if ( false === $temp ) {
			return '';
		}

		$string = $temp . '°';
		$tag    = self::add_style_and_class_to_tag( 'span', $attributes );
		return "<$tag>$string</span>";
	}

	public function weather_observation_temperature_rounded_string( $attributes ): string {
		$observation = $this->observations->get_latest_observation();
		$temp        = $observation['air_temp'] ?? false;

		if ( false === $temp ) {
			return '';
		}

		$string = round( $temp ) . '°';
		$tag    = self::add_style_and_class_to_tag( 'span', $attributes );
		return "<$tag>$string</span>";
	}

	public function forecast_max_exists( $attributes, $content ): string {
		$max = $this->get_area_forecasts_value( $attributes, 'max' );
		if ( null !== $max && 0 < strlen( $max ) ) {
			return do_shortcode( $content );
		}

		return '';
	}

	public function forecast_max_empty( $attributes, $content ): string {
		$max = $this->get_area_forecasts_value( $attributes, 'max' );
		if ( null === $max || 0 === strlen( $max ) ) {
			return do_shortcode( $content );
		}

		return '';
	}

	public function forecast_max( $attributes ): string {
		$max = $this->get_area_forecasts_value( $attributes, 'max' );
		if ( null === $max ) {
			return '';
		}

		$tag = self::add_style_and_class_to_tag( 'span', $attributes );
		return "<$tag>" . esc_html( $max ) . '°</span>';
	}

	public function forecast_min_exists( $attributes, $content ): string {
		$min = $this->get_area_forecasts_value( $attributes, 'min' );
		if ( null !== $min || 0 < strlen( $min ) ) {
			return do_shortcode( $content );
		}

		return '';
	}

	public function forecast_min_empty( $attributes, $content ): string {
		$min = $this->get_area_forecasts_value( $attributes, 'min' );
		if ( null === $min || 0 === strlen( $min ) ) {
			return do_shortcode( $content );
		}

		return '';
	}

	public function forecast_min( $attributes ): string {
		$min = $this->get_area_forecasts_value( $attributes, 'min' );
		if ( null === $min ) {
			return '';
		}

		$tag = self::add_style_and_class_to_tag( 'span', $attributes );
		return "<$tag>" . esc_html( $min ) . '°</span>';
	}

	public function forecast_icon_url( $attributes ): string {
		$url = $this->get_area_forecasts_value( $attributes, 'iconMonochromeUrl' );
		if ( null === $url ) {
			return '';
		}

		return esc_url( $url );
	}

	protected function get_area_forecasts_value( $attributes, $key ) {
		$forecasts = $this->forecasts->get_area_forecasts();
		if ( null === $forecasts ) {
			return null;
		}

		$day              = Utils::day_from_attributes( $attributes );
		$indexedForecasts = array_values( $forecasts );
		$forecast         = $indexedForecasts[ $day ] ?? [];

		return $forecast[ $key ] ?? null;
	}
}
