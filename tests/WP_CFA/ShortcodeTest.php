<?php

namespace WP_CFA;

use Mockery;
use WP_Mock;
use WP_Mock\Tools\TestCase;

/**
 * @package Wp_Cfa
 */

class ShortcodeTest extends TestCase {
	private $mockBomObservations;

	private $mockBomForecasts;

	public function __construct( ...$args ) {
		parent::__construct( ...$args );
		$observationData           = json_decode( file_get_contents( __DIR__ . '/../data/bom-observations.json' ), true );
		$this->mockBomObservations = Mockery::mock( BomObservations::class )->makePartial();
		$this->mockBomObservations->allows( 'fetch_bom_observation_json' )->andReturn( $observationData );

		$forecastData           = file_get_contents( __DIR__ . '/../data/IDV10753.ftp-forecasts.xml' );
		$this->mockBomForecasts = Mockery::mock( BomForecasts::class )->makePartial();
		$this->mockBomForecasts->allows( 'fetch_bom_forecast_xml_string' )->andReturn( $forecastData );
	}

	private static function mock_get_option( $option, $value ): void {
		WP_Mock::userFunction( 'get_option' )->andReturnUsing( function ( $o ) use ( $option, $value ) {
			if ( $option === $o ) {
				return $value;
			}

			return null;
		} );
	}

	public function setUp(): void {
		parent::setUp();
		WP_Mock::userFunction( 'plugins_url' )->andReturnArg( 0 );
		WP_Mock::userFunction( 'esc_attr' )->andReturnArg( 0 );
		WP_Mock::userFunction( 'esc_url' )->andReturnArg( 0 );
		WP_Mock::userFunction( 'esc_html' )->andReturnArg( 0 );
		WP_Mock::userFunction( 'do_shortcode' )->andReturnUsing( function ( $value ) { return $value; } );
	}

	public function test_fire_danger_rating_class(): void {
		self::mock_get_option( Settings::$setting_name_district, 'central' );
		$shortcode = Mockery::mock( '\WP_CFA\Shortcode' )->makePartial();
		$shortcode->allows( 'rating_string_for_district' )->andReturn( 'high' );

		$actual   = $shortcode->fire_danger_rating_class( [] );
		$expected = 'wp-cfa-rating--high';
		$this->assertEquals( $expected, $actual );
	}

	public function test_fire_danger_rating_text(): void {
		self::mock_get_option( Settings::$setting_name_district, 'central' );
		$shortcode = Mockery::mock( '\WP_CFA\Shortcode' )->makePartial();
		$shortcode->allows( 'rating_string_for_district' )->andReturn( 'high' );

		$actual   = $shortcode->fire_danger_rating_text( [] );
		$expected = '<span class="wp-cfa-rating wp-cfa-rating--high">HIGH</span>';
		$this->assertEquals( $expected, $actual );
	}

	public function test_fire_danger_rating_text_styled(): void {
		self::mock_get_option( Settings::$setting_name_district, 'central' );
		$shortcode = Mockery::mock( '\WP_CFA\Shortcode' )->makePartial();
		$shortcode->allows( 'rating_string_for_district' )->andReturn( 'high' );

		$actual   = $shortcode->fire_danger_rating_text( [ 'class' => 'test-case' ] );
		$expected = '<span class="wp-cfa-rating wp-cfa-rating--high test-case">HIGH</span>';
		$this->assertEquals( $expected, $actual );
	}

	public function test_fire_danger_rating_image_url(): void {
		self::mock_get_option( Settings::$setting_name_district, 'central' );
		$shortcode = Mockery::mock( '\WP_CFA\Shortcode' )->makePartial();
		$shortcode->allows( 'rating_string_for_district' )->andReturn( 'catastrophic' );

		$actual   = $shortcode->fire_danger_rating_image_url( [] );
		$expected = 'public/images/rating-catastrophic.png';
		$this->assertEquals( $expected, $actual );
	}

	public function test_fire_danger_rating_image_tag(): void {
		self::mock_get_option( Settings::$setting_name_district, 'central' );
		$shortcode = Mockery::mock( '\WP_CFA\Shortcode' )->makePartial();
		$shortcode->allows( 'rating_string_for_district' )->andReturn( 'extreme' );

		$actual   = $shortcode->fire_danger_rating_image_tag( [] );
		$expected = '<img src="public/images/rating-extreme.png" />';
		$this->assertEquals( $expected, $actual );
	}

	public function test_date_time(): void {
		$shortcode = new Shortcode();
		WP_Mock::userFunction( 'wp_date' )
			->once()
			->andReturnUsing(
				function ( $format, $timestamp ) {
					return date( $format, $timestamp );
				},
			);

		$actual   = $shortcode->date_time( [ 'format' => 'd-M-y', 'timestamp' => '1768043893', 'class' => 'date' ] );
		$expected = '<span class="date">10-Jan-26</span>';
		$this->assertEquals( $expected, $actual );
	}

	public function test_date_time_no_format(): void {
		$shortcode = new Shortcode();

		WP_Mock::userFunction( '_doing_it_wrong' )->once();

		$actual   = $shortcode->date_time( [] );
		$expected = '';
		$this->assertEquals( $expected, $actual );
		$this->assertConditionsMet();
	}

	public function test_weather_observation_temperature(): void {
		$shortcode = new Shortcode( $this->mockBomObservations );
		$actual    = $shortcode->weather_observation_temperature_string( [ 'style' => 'border: solid 2px black;' ] );
		$this->assertEquals( '<span style="border: solid 2px black;">14.1°</span>', $actual );
	}

	public function test_weather_observation_temperature_rounded(): void {
		$shortcode = new Shortcode( $this->mockBomObservations );
		$actual    = $shortcode->weather_observation_temperature_rounded_string( [ 'class' => 'rounded' ] );
		$this->assertEquals( '<span class="rounded">14°</span>', $actual );
	}

	public function test_weather_observation_temperature_number(): void {
		$shortcode = new Shortcode( $this->mockBomObservations );
		$actual    = $shortcode->weather_observation_temperature_number( [] );
		$this->assertEquals( '14.1', $actual );
	}

	public function test_weather_forecast_min(): void {
		self::mock_get_option( Settings::$setting_name_forecast_area, 'VIC_PT065' );
		$shortcode = new Shortcode( $this->mockBomObservations, $this->mockBomForecasts );
		$this->assertEquals( null, $shortcode->forecast_min( [] ) );
		$this->assertEquals( '<span>13°</span>', $shortcode->forecast_min( [ 'day' => 'tomorrow' ] ) );
	}

	public function test_weather_forecast_max(): void {
		self::mock_get_option( Settings::$setting_name_forecast_area, 'VIC_PT065' );
		$shortcode = new Shortcode( $this->mockBomObservations, $this->mockBomForecasts );
		$this->assertEquals( '<span>26°</span>', $shortcode->forecast_max( [] ) );
		$this->assertEquals( '<span>25°</span>', $shortcode->forecast_max( [ 'day' => 'tomorrow' ] ) );
	}

	public function test_weather_forecast_max_empty(): void {
		// VIC_PT065 in the test data manually comments out today's max temperature.
		self::mock_get_option( Settings::$setting_name_forecast_area, 'VIC_PT071' );
		$shortcode = new Shortcode( $this->mockBomObservations, $this->mockBomForecasts );

		// No max value for the remainder of the day today (i.e. it is empty), so will render contents.
		$this->assertEquals( 'CONTENT', $shortcode->forecast_max_empty( [], 'CONTENT' ) );

		// Tomorrow does have a max value, so don't render (it is not empty).
		$this->assertEquals( '', $shortcode->forecast_max_empty( [ 'day' => 'tomorrow' ], 'CONTENT' ) );
	}

	public function test_weather_forecast_min_empty(): void {
		self::mock_get_option( Settings::$setting_name_forecast_area, 'VIC_PT065' );
		$shortcode = new Shortcode( $this->mockBomObservations, $this->mockBomForecasts );

		// No min value for the remainder of the day today (i.e. it is empty), so will render blank.
		$this->assertEquals( 'CONTENT', $shortcode->forecast_min_empty( [], 'CONTENT' ) );

		// Tomorrow does have a min value, so don't render (it is not empty).
		$this->assertEquals( '', $shortcode->forecast_min_empty( [ 'day' => 'tomorrow' ], 'CONTENT' ) );
	}

	public function test_weather_forecast_max_exists(): void {
		// VIC_PT065 in the test data manually comments out today's max temperature.
		self::mock_get_option( Settings::$setting_name_forecast_area, 'VIC_PT071' );
		$shortcode = new Shortcode( $this->mockBomObservations, $this->mockBomForecasts );

		// No max value for the remainder of the day today, so will NOT render contents.
		$this->assertEquals( '', $shortcode->forecast_max_exists( [], 'CONTENT' ) );

		// Tomorrow does have a max value, so render contents.
		$this->assertEquals( 'CONTENT', $shortcode->forecast_max_exists( [ 'day' => 'tomorrow' ], 'CONTENT' ) );
	}

	public function test_weather_forecast_min_not_empty(): void {
		self::mock_get_option( Settings::$setting_name_forecast_area, 'VIC_PT065' );
		$shortcode = new Shortcode( $this->mockBomObservations, $this->mockBomForecasts );

		// No min value for the remainder of the day today, so will NOT render contents.
		$this->assertEquals( '', $shortcode->forecast_min_exists( [], 'CONTENT' ) );

		// Tomorrow does have a min value, so render contents.
		$this->assertEquals( 'CONTENT', $shortcode->forecast_min_exists( [ 'day' => 'tomorrow' ], 'CONTENT' ) );
	}

	public function test_weather_forecast_icon_url(): void {
		self::mock_get_option( Settings::$setting_name_forecast_area, 'VIC_PT065' );
		$shortcode = new Shortcode( $this->mockBomObservations, $this->mockBomForecasts );

		// Icon codes are 1, 4, 16, and 11.
		// These correspond to Sunny, Cloudy, Storm, and Showers.
		// See: https://reg.bom.gov.au/info/forecast_icons.shtml.

		$icon1  = 'https://reg.bom.gov.au/weather-services/images/symbols/large/mobile/sunny.png';
		$icon4  = 'https://reg.bom.gov.au/weather-services/images/symbols/large/mobile/cloudy.png';
		$icon16 = 'https://reg.bom.gov.au/weather-services/images/symbols/large/mobile/storm.png';
		$icon11 = 'https://reg.bom.gov.au/weather-services/images/symbols/large/mobile/showers.png';

		$this->assertEquals( $icon1, $shortcode->forecast_icon_url( [] ) );
		$this->assertEquals( $icon4, $shortcode->forecast_icon_url( [ 'day' => 'tomorrow' ] ) );
		$this->assertEquals( $icon16, $shortcode->forecast_icon_url( [ 'day' => '2' ] ) );
		$this->assertEquals( $icon11, $shortcode->forecast_icon_url( [ 'day' => '3' ] ) );
	}
}
