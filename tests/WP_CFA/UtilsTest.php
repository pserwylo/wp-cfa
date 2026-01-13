<?php

namespace WP_CFA;

use WP_Mock;
use WP_Mock\Tools\TestCase;

class UtilsTest extends TestCase {
	public function test_cfa_day_from_attributes_valid_values(): void {
		$this->assertEquals( 0, Utils::day_from_attributes( [] ) );
		$this->assertEquals( 0, Utils::day_from_attributes( [ 'day' => 'today' ] ) );
		$this->assertEquals( 1, Utils::day_from_attributes( [ 'day' => 'tomorrow' ] ) );
		$this->assertEquals( 0, Utils::day_from_attributes( [ 'day' => '0' ] ) );
		$this->assertEquals( 1, Utils::day_from_attributes( [ 'day' => '1' ] ) );
		$this->assertEquals( 2, Utils::day_from_attributes( [ 'day' => '2' ] ) );
		$this->assertEquals( 3, Utils::day_from_attributes( [ 'day' => '3' ] ) );
	}

	/**
	 * @expectedExceptoin PHPUnit\
	 */
	public function test_day_from_attributes_invalid_values(): void {
		WP_Mock::userFunction( '_doing_it_wrong' )->once();
		Utils::day_from_attributes( [ 'day' => '4' ] );
		$this->assertConditionsMet();
	}

	public function test_district_to_id(): void {
		$this->assertEquals( 'central', Utils::district_to_id( 'central' ) );
		$this->assertEquals( 'central', Utils::district_to_id( 'Central' ) );
		$this->assertEquals( 'northerncountry', Utils::district_to_id( 'Northern Country' ) );
	}
}
