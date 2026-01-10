<?php

namespace WP_CFA;

use Mockery;
use WP_Mock;
use WP_Mock\Tools\TestCase;

/**
 * @package Wp_Cfa
 */

class ShortcodeTest extends TestCase {
	private $mockBom;

	public function __construct( ...$args ) {
		parent::__construct( ...$args );
		$bomData       = json_decode( file_get_contents( __DIR__ . '/../data/bom-observations.json' ), true );
		$this->mockBom = Mockery::mock( BomObservations::class )->makePartial();
		$this->mockBom->allows( 'fetch_bom_observation_json' )->andReturn( $bomData );
	}

	public function setUp(): void {
		parent::setUp();

		WP_Mock::userFunction( 'get_option' )->andReturnUsing( function ( $option ) {
			if ( $option === Settings::$setting_name_district ) {
				return 'central';
			} elseif ( $option === Settings::$setting_name_weather_station ) {
				return 'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.95867.json';
			} else {
				return '';
			}
		} );
		WP_Mock::userFunction( 'plugins_url' )->andReturnArg( 0 );
		WP_Mock::userFunction( 'esc_attr' )->andReturnArg( 0 );
		WP_Mock::userFunction( 'esc_url' )->andReturnArg( 0 );
		WP_Mock::userFunction( 'esc_html' )->andReturnArg( 0 );
	}

	public function test_fire_danger_rating_text(): void {
		$shortcode = Mockery::mock( '\WP_CFA\Shortcode' )->makePartial();
		$shortcode->allows( 'rating_string_for_district' )->andReturn( 'high' );

		$actual   = $shortcode->fire_danger_rating_text( [] );
		$expected = '<span class="wp-cfa-rating wp-cfa-rating--high">HIGH</span>';
		$this->assertEquals( $expected, $actual );
	}

	public function test_fire_danger_rating_text_styled(): void {
		$shortcode = Mockery::mock( '\WP_CFA\Shortcode' )->makePartial();
		$shortcode->allows( 'rating_string_for_district' )->andReturn( 'high' );

		$actual   = $shortcode->fire_danger_rating_text( [ 'class' => 'test-case' ] );
		$expected = '<span class="wp-cfa-rating wp-cfa-rating--high test-case">HIGH</span>';
		$this->assertEquals( $expected, $actual );
	}

	public function test_fire_danger_rating_image_url(): void {
		$shortcode = Mockery::mock( '\WP_CFA\Shortcode' )->makePartial();
		$shortcode->allows( 'rating_string_for_district' )->andReturn( 'catastrophic' );

		$actual   = $shortcode->fire_danger_rating_image_url( [] );
		$expected = 'public/images/rating-catastrophic.png';
		$this->assertEquals( $expected, $actual );
	}

	public function test_fire_danger_rating_image_tag(): void {
		$shortcode = Mockery::mock( '\WP_CFA\Shortcode' )->makePartial();
		$shortcode->allows( 'rating_string_for_district' )->andReturn( 'extreme' );

		$actual   = $shortcode->fire_danger_rating_image_tag( [] );
		$expected = '<img src="public/images/rating-extreme.png" />';
		$this->assertEquals( $expected, $actual );
	}

	public function test_date_time(): void {
		$shortcode = new Shortcode();

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
		$shortcode = new Shortcode( $this->mockBom );
		$actual    = $shortcode->weather_observation_temperature_string( [ 'style' => 'border: solid 2px black;' ] );
		$this->assertEquals( '<span style="border: solid 2px black;">14.1°</span>', $actual );
	}

	public function test_weather_observation_temperature_rounded(): void {
		$shortcode = new Shortcode( $this->mockBom );
		$actual    = $shortcode->weather_observation_temperature_rounded_string( [ 'class' => 'rounded' ] );
		$this->assertEquals( '<span class="rounded">14°</span>', $actual );
	}

	public function test_weather_observation_temperature_number(): void {
		$shortcode = new Shortcode( $this->mockBom );
		$actual    = $shortcode->weather_observation_temperature_number( [] );
		$this->assertEquals( '14.1', $actual );
	}
}
