<?php
/**
 * Plugin Name:     WP CFA
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     Tools to show fire danger rating and weather observations on your brigades wordpress website.
 * Author:          Peter Serwylo
 * Author URI:      YOUR SITE HERE
 * Text Domain:     wp-cfa
 * Domain Path:     /languages
 * Version:         1.0.0
 *
 * @package         Wp_Cfa
 */


require_once __DIR__ . '/vendor/autoload.php';

add_shortcode('cfa_fire_danger_rating_text', [\WP_CFA\Shortcode::class, 'fire_danger_rating_text']);
add_shortcode('cfa_fire_danger_rating_image_url', [\WP_CFA\Shortcode::class, 'fire_danger_rating_image_url']);
add_shortcode('cfa_fire_danger_rating_image_tag', [\WP_CFA\Shortcode::class, 'fire_danger_rating_image_tag']);
add_shortcode('cfa_weather_observation_temperature', [\WP_CFA\Shortcode::class, 'weather_observation_temperature_string']);
add_shortcode('cfa_weather_observation_temperature_rounded', [\WP_CFA\Shortcode::class, 'weather_observation_temperature_rounded_string']);
add_shortcode('cfa_weather_observation_temperature_number', [\WP_CFA\Shortcode::class, 'weather_observation_temperature_number']);
add_shortcode('cfa_date_time', [\WP_CFA\Shortcode::class, 'date_time']);
add_action('admin_init', [\WP_CFA\Settings::class, 'init']);
add_action('admin_menu', [\WP_CFA\Settings::class, 'init_menu']);

