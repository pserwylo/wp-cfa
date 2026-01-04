<?php
namespace WP_CFA;

use WP_Mock\Tools\TestCase;

class UtilsTest extends TestCase {

	public function test_cfa_day_from_attributes_valid_values() {
		$this->assertEquals(Utils::day_from_attributes([]), 0);
		$this->assertEquals(Utils::day_from_attributes(['day' => 'today']), 0);
		$this->assertEquals(Utils::day_from_attributes(['day' => 'tomorrow']), 1);
		$this->assertEquals(Utils::day_from_attributes(['day' => 0]), 0);
		$this->assertEquals(Utils::day_from_attributes(['day' => 1]), 1);
		$this->assertEquals(Utils::day_from_attributes(['day' => 2]), 2);
		$this->assertEquals(Utils::day_from_attributes(['day' => 3]), 3);
	}

	/**
	 * @expectedExceptoin PHPUnit\
	 */
	public function test_day_from_attributes_invalid_values() {
		\WP_Mock::userFunction('_doing_it_wrong')->once();
		Utils::day_from_attributes(['day' => 4]);
		$this->assertConditionsMet();
	}

	// TODO: Mock RSS value returned.
/*	public function test_shortcode_fire_danger_rating() {
		$this->assertEquals(
			'<span>central: 0</span>',
			Shortcode::fire_danger_rating(['district' => 'central'], '<span>central: 0</span>'),
		);

		$this->assertEquals(
			'<span>central: 0</span>',
			Shortcode::fire_danger_rating(['district' => 'central', 'day' => 'today'], '<span>central: 0</span>'),
		);

		$this->assertEquals(
			'<span>central: 1</span>',
			Shortcode::fire_danger_rating(['district' => 'central', 'day' => 'tomorrow'], '<span>central: 0</span>'),
		);

		$this->assertEquals(
			'<span class="rating rating-tomorrow">central: 1</span>',
			Shortcode::fire_danger_rating(['district' => 'central', 'day' => 'tomorrow', 'className' => 'rating rating-tomorrow'], '<span>central: 0</span>'),
		);

		$this->assertEquals(
			'<span style="margin: 10px" class="rating rating-tomorrow">central: 1</span>',
			Shortcode::fire_danger_rating(['district' => 'central', 'day' => 'tomorrow', 'className' => 'rating rating-tomorrow', 'style' => 'margin: 10px'], '<span>central: 0</span>'),
		);
	}*/

	public function test_district_to_id() {
		$this->assertEquals('central', Utils::district_to_id('central'));
		$this->assertEquals('central', Utils::district_to_id('Central'));
		$this->assertEquals('northerncountry', Utils::district_to_id('Northern Country'));
	}
}
