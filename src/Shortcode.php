<?php

namespace WP_CFA;

class Shortcode
{
	static function date_time($attributes) {
		if (!isset($attributes['format']) || !$attributes['format']) {
			_doing_it_wrong(
				__FUNCTION__,
				'No format provided to cfa_date_time.',
				'1.0.0'
			);
		}

		$format = $attributes['format'];

		$dateTime = @date($format);
		$tag = self::add_style_and_class_to_tag('span', $attributes);
		return "<$tag>$dateTime</$tag>";
	}

	private static function add_style_and_class_to_tag($tag, $attributes) {
		$class = $attributes['class'] ?? '';
		$style = $attributes['style'] ?? '';

		if ($style) {
			$tag .= ' style="' . esc_attr($style) . '"';
		}

		if ($class) {
			$tag .= ' class="' . esc_attr($class) . '"';
		}

		return $tag;
	}

	private static function rating_string_for_district($districtId, $day) {
		$content = FdrRssFeed::fire_danger_rating_feed();
		return $content[$day]['districts'][$districtId]['rating'];
	}

	public static function fire_danger_rating_image_url($attributes): string
	{
		$district = Utils::district_from_attributes($attributes);
		$day = Utils::day_from_attributes($attributes);

		if (!$district) {
			return '';
		}

		$rating = self::rating_string_for_district($district, $day);

		return plugins_url("public/images/rating-{$rating}.png", __DIR__);
	}

	public static function fire_danger_rating_image_tag($attributes): string
	{
		$url = self::fire_danger_rating_image_url($attributes);

		if (!$url) {
			return '';
		}

		$tag = self::add_style_and_class_to_tag('img', $attributes);

		return '<' . $tag . ' src="' . esc_url($url) . '" />';
	}

    public static function fire_danger_rating_text($attributes): string
    {
        $district = Utils::district_from_attributes($attributes);
        $day = Utils::day_from_attributes($attributes);

        if (!$district) {
            return '';
        }

		$rating = strtoupper(self::rating_string_for_district($district, $day));
		$ratingClass = 'wp-cfa-rating wp-cfa-rating--' . strtolower($rating);

		$attributes['class'] = $attributes['class'] ? $attributes['class'] . ' ' . $ratingClass : $ratingClass;
		if (!isset($attributes['class'])) {
			$attributes['class'] = '';
		}
		$tag = self::add_style_and_class_to_tag('span', $attributes);

        return "<$tag>$rating</span>";
    }

	static function weather_observation_temperature_number($attributes)
	{
		$observation = BomObservations::get_latest_observation();
		$temp = $observation['air_temp'] ?? false;

		if ($temp === false) {
			return '';
		}

		$tag = self::add_style_and_class_to_tag('span', $attributes);
		return "<$tag>$temp</$tag>";
	}

	static function weather_observation_temperature_string($attributes)
	{
		$observation = BomObservations::get_latest_observation();
		$temp = $observation['air_temp'] ?? false;

		if ($temp === false) {
			return '';
		}

		$string = $temp . '°';
		$tag = self::add_style_and_class_to_tag('span', $attributes);
		return "<$tag>$string</$tag>";
	}

	static function weather_observation_temperature_rounded_string($attributes)
	{
		$observation = BomObservations::get_latest_observation();
		$temp = $observation['air_temp'] ?? false;

		if ($temp === false) {
			return '';
		}

		$string = round($temp) . '°';
		$tag = self::add_style_and_class_to_tag('span', $attributes);
		return "<$tag>$string</$tag>";
	}
}
