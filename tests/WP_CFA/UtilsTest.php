<?php

namespace WP_CFA;

use WP_Mock;
use WP_Mock\Tools\TestCase;

class UtilsTest extends TestCase {
	public function test_cfa_day_from_attributes_valid_values(): void {
		$this->assertEquals( Utils::day_from_attributes( [] ), 0 );
		$this->assertEquals( Utils::day_from_attributes( [ 'day' => 'today' ] ), 0 );
		$this->assertEquals( Utils::day_from_attributes( [ 'day' => 'tomorrow' ] ), 1 );
		$this->assertEquals( Utils::day_from_attributes( [ 'day' => 0 ] ), 0 );
		$this->assertEquals( Utils::day_from_attributes( [ 'day' => 1 ] ), 1 );
		$this->assertEquals( Utils::day_from_attributes( [ 'day' => 2 ] ), 2 );
		$this->assertEquals( Utils::day_from_attributes( [ 'day' => 3 ] ), 3 );
	}

	/**
	 * @expectedExceptoin PHPUnit\
	 */
	public function test_day_from_attributes_invalid_values(): void {
		WP_Mock::userFunction( '_doing_it_wrong' )->once();
		Utils::day_from_attributes( [ 'day' => 4 ] );
		$this->assertConditionsMet();
	}

	public function test_district_to_id(): void {
		$this->assertEquals( 'central', Utils::district_to_id( 'central' ) );
		$this->assertEquals( 'central', Utils::district_to_id( 'Central' ) );
		$this->assertEquals( 'northerncountry', Utils::district_to_id( 'Northern Country' ) );
	}
}
