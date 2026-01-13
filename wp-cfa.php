<?php

use WP_CFA\Settings;
use WP_CFA\Shortcode;

/**
 * Plugin Name:     WP CFA
 * Plugin URI:      https://github.com/pserwylo/wp-cfa/
 * Description:     Tools to show fire danger rating and weather observations on your brigades wordpress website.
 * Author:          Peter Serwylo
 * Author URI:      https://peter.serwylo.com
 * Text Domain:     wp-cfa
 * Domain Path:     /languages
 * Version:         1.0.0
 *
 * @package         Wp_Cfa
 */

require_once __DIR__ . '/vendor/autoload.php';

$shortcode = new Shortcode();
add_shortcode( 'cfa_fire_danger_rating_class', [ $shortcode, 'fire_danger_rating_class' ] );
add_shortcode( 'cfa_fire_danger_rating_text', [ $shortcode, 'fire_danger_rating_text' ] );
add_shortcode( 'cfa_fire_danger_rating_image_url', [ $shortcode, 'fire_danger_rating_image_url' ] );
add_shortcode( 'cfa_fire_danger_rating_image_tag', [ $shortcode, 'fire_danger_rating_image_tag' ] );
add_shortcode( 'cfa_weather_observation_temperature', [ $shortcode, 'weather_observation_temperature_string' ] );
add_shortcode( 'cfa_weather_observation_temperature_rounded', [ $shortcode, 'weather_observation_temperature_rounded_string' ] );
add_shortcode( 'cfa_weather_observation_temperature_number', [ $shortcode, 'weather_observation_temperature_number' ] );
add_shortcode( 'cfa_weather_forecast_min', [ $shortcode, 'forecast_min' ] );
add_shortcode( 'cfa_weather_forecast_min_empty', [ $shortcode, 'forecast_min_empty' ] );
add_shortcode( 'cfa_weather_forecast_min_exists', [ $shortcode, 'forecast_min_exists' ] );
add_shortcode( 'cfa_weather_forecast_max', [ $shortcode, 'forecast_max' ] );
add_shortcode( 'cfa_weather_forecast_max_empty', [ $shortcode, 'forecast_max_empty' ] );
add_shortcode( 'cfa_weather_forecast_max_exists', [ $shortcode, 'forecast_max_exists' ] );
add_shortcode( 'cfa_weather_forecast_icon_url', [ $shortcode, 'forecast_icon_url' ] );
add_shortcode( 'cfa_date_time', [ $shortcode, 'date_time' ] );
add_action( 'admin_init', [ Settings::class, 'init' ] );
add_action( 'admin_menu', [ Settings::class, 'init_menu' ] );
