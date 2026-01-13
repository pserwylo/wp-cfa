<?php

namespace WP_CFA;

use Mockery;
use WP_Mock;
use WP_Mock\Tools\TestCase;

if ( !defined( 'HOUR_IN_SECONDS' ) ) {
	define( 'HOUR_IN_SECONDS', 60 * 60 );
}

if ( !defined( 'MINUTE_IN_SECONDS' ) ) {
	define( 'MINUTE_IN_SECONDS', 60 );
}

/**
 * @package Wp_Cfa
 */

class RSSTest extends TestCase {
	public function setUp(): void {
		parent::setUp();
		WP_Mock::userFunction( 'get_transient' )->andReturn( null );
		WP_Mock::userFunction( 'set_transient' );
	}

	public function test_parse_rss_html(): void {
		$multipleTotalFireBans = <<<html
<p>Today, Wed, 7 Jan 2026 has been declared a day of Total Fire Ban in the Wimmera, South West and Central (includes Melbourne and Geelong) district(s) of Victoria. No fires can be lit or be allowed to remain alight in the open air from 12:01 AM on Wed, 7 Jan 2026 until 11:59 PM Wed, 7 Jan 2026.</p><p>Central: YES - TOTAL FIRE BAN IN FORCE<br>East Gippsland: NO - RESTRICTIONS MAY APPLY<br>Mallee: NO - RESTRICTIONS MAY APPLY<br>North Central: NO - RESTRICTIONS MAY APPLY<br>North East: NO - RESTRICTIONS MAY APPLY<br>Northern Country: NO - RESTRICTIONS MAY APPLY<br>South West: YES - TOTAL FIRE BAN IN FORCE<br>West and South Gippsland: NO - RESTRICTIONS MAY APPLY<br>Wimmera: YES - TOTAL FIRE BAN IN FORCE<br></p><p>Fire Danger Ratings<br/>Bureau of Meteorology forecast issued at: Wednesday, 07 January 2026 05:30 AM</p><p>Central: EXTREME<br>East Gippsland: HIGH<br>Mallee: HIGH<br>North Central: HIGH<br>North East: HIGH<br>Northern Country: HIGH<br>South West: EXTREME<br>West and South Gippsland: HIGH<br>Wimmera: EXTREME<br></p>
html;

		$this->assertSame(
			[
				'date'      => '2026-01-07',
				'districts' => [
					'central'               => [ 'tfb' => true, 'rating' => 'extreme' ],
					'eastgippsland'         => [ 'tfb' => false, 'rating' => 'high' ],
					'mallee'                => [ 'tfb' => false, 'rating' => 'high' ],
					'northcentral'          => [ 'tfb' => false, 'rating' => 'high' ],
					'northeast'             => [ 'tfb' => false, 'rating' => 'high' ],
					'northerncountry'       => [ 'tfb' => false, 'rating' => 'high' ],
					'southwest'             => [ 'tfb' => true, 'rating' => 'extreme' ],
					'westandsouthgippsland' => [ 'tfb' => false, 'rating' => 'high' ],
					'wimmera'               => [ 'tfb' => true, 'rating' => 'extreme' ],
				],
			],
			FdrRssFeed::parse_rss_item( 'Wednesday, 07 January 2026', $multipleTotalFireBans ),
		);

		$noTfb = <<<html
<p>Tomorrow, Thu, 8 Jan 2026 is not currently a day of Total Fire Ban.</p><p>Central: NO - RESTRICTIONS MAY APPLY<br>East Gippsland: NO - RESTRICTIONS MAY APPLY<br>Mallee: NO - RESTRICTIONS MAY APPLY<br>North Central: NO - RESTRICTIONS MAY APPLY<br>North East: NO - RESTRICTIONS MAY APPLY<br>Northern Country: NO - RESTRICTIONS MAY APPLY<br>South West: NO - RESTRICTIONS MAY APPLY<br>West and South Gippsland: NO - RESTRICTIONS MAY APPLY<br>Wimmera: NO - RESTRICTIONS MAY APPLY<br></p><p>Fire Danger Ratings<br/>Bureau of Meteorology forecast issued at: Wednesday, 07 January 2026 05:30 AM</p><p>Central: MODERATE<br>East Gippsland: HIGH<br>Mallee: HIGH<br>North Central: HIGH<br>North East: EXTREME<br>Northern Country: EXTREME<br>South West: MODERATE<br>West and South Gippsland: HIGH<br>Wimmera: HIGH<br></p>
html;

		$this->assertEquals(
			[
				'date'      => '2026-01-08',
				'districts' => [
					'central'               => [ 'tfb' => false, 'rating' => 'moderate' ],
					'eastgippsland'         => [ 'tfb' => false, 'rating' => 'high' ],
					'mallee'                => [ 'tfb' => false, 'rating' => 'high' ],
					'northcentral'          => [ 'tfb' => false, 'rating' => 'high' ],
					'northeast'             => [ 'tfb' => false, 'rating' => 'extreme' ],
					'northerncountry'       => [ 'tfb' => false, 'rating' => 'extreme' ],
					'southwest'             => [ 'tfb' => false, 'rating' => 'moderate' ],
					'westandsouthgippsland' => [ 'tfb' => false, 'rating' => 'high' ],
					'wimmera'               => [ 'tfb' => false, 'rating' => 'high' ],
				],
			],
			FdrRssFeed::parse_rss_item( 'Thursday, 08 January 2026', $noTfb ),
		);
	}

	public function test_fetch_rss_error(): void {
		WP_Mock::userFunction( 'fetch_feed' )->once();
		WP_Mock::userFunction( 'is_wp_error' )->once()->andReturn( true );
		$feed = FdrRssFeed::fire_danger_rating_feed();

		$this->assertEmpty( $feed );
		$this->assertConditionsMet();
	}

	public function test_fetch_rss(): void {
		$items = [];
		for ( $i = 0; 4 > $i; ++$i ) {
			$item = Mockery::mock( '\SimplePie\Item' );

			$item->allows( 'get_title' )
				->andReturn( 'Sunday, 11 January 2026' );

			$item->allows( 'get_description' )
				->andReturn( '<p>Tomorrow, Sun, 11 Jan 2026 has been declared a day of Total Fire Ban for the whole State of Victoria. No fires can be lit or be allowed to remain alight in the open air from 12:01 AM on Sun, 11 Jan 2026 until 11:59 PM Sun, 11 Jan 2026.</p><p>Central: YES - TOTAL FIRE BAN IN FORCE<br>East Gippsland: YES - TOTAL FIRE BAN IN FORCE<br>Mallee: YES - TOTAL FIRE BAN IN FORCE<br>North Central: YES - TOTAL FIRE BAN IN FORCE<br>North East: YES - TOTAL FIRE BAN IN FORCE<br>Northern Country: YES - TOTAL FIRE BAN IN FORCE<br>South West: YES - TOTAL FIRE BAN IN FORCE<br>West and South Gippsland: YES - TOTAL FIRE BAN IN FORCE<br>Wimmera: YES - TOTAL FIRE BAN IN FORCE<br></p><p>Fire Danger Ratings<br/>Bureau of Meteorology forecast issued at: Saturday, 10 January 2026 05:30 AM</p><p>Central: MODERATE<br>East Gippsland: MODERATE<br>Mallee: HIGH<br>North Central: HIGH<br>North East: HIGH<br>Northern Country: HIGH<br>South West: MODERATE<br>West and South Gippsland: MODERATE<br>Wimmera: HIGH<br></p>' );

			$items[] = $item;
		}

		$rss = Mockery::mock( '\SimplePie\SimplePie' );
		$rss->shouldReceive( 'get_items' )
			->with( 0, 4 )
			->once()
			->andReturn( $items );

		WP_Mock::userFunction( 'fetch_feed' )->once()->andReturn( $rss );
		WP_Mock::userFunction( 'is_wp_error' )->once()->andReturn( false );

		$feed = FdrRssFeed::fire_danger_rating_feed();

		$this->assertEquals(
			[
				'date'      => '2026-01-11',
				'districts' => [
					'central'               => [ 'tfb' => true, 'rating' => 'moderate' ],
					'eastgippsland'         => [ 'tfb' => true, 'rating' => 'moderate' ],
					'mallee'                => [ 'tfb' => true, 'rating' => 'high' ],
					'northcentral'          => [ 'tfb' => true, 'rating' => 'high' ],
					'northeast'             => [ 'tfb' => true, 'rating' => 'high' ],
					'northerncountry'       => [ 'tfb' => true, 'rating' => 'high' ],
					'southwest'             => [ 'tfb' => true, 'rating' => 'moderate' ],
					'westandsouthgippsland' => [ 'tfb' => true, 'rating' => 'moderate' ],
					'wimmera'               => [ 'tfb' => true, 'rating' => 'high' ],
				],
			],
			$feed[0],
		);
		$this->assertConditionsMet();
	}
}
