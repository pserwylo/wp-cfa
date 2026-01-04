<?php
namespace WP_CFA;

use WP_Mock;
use WP_Mock\Tools\TestCase;

/**
 * @package Wp_Cfa
 */

class RSSTest extends TestCase {

	public function test_parse_rss_html() {
		// TODO: Why is 'district(s)' pluralised? If there are multiple do they all end up in this one string? Or in individual RSS feeds?
		$multipleTotalFireBans = <<<html
<p>Today, Wed, 7 Jan 2026 has been declared a day of Total Fire Ban in the Wimmera, South West and Central (includes Melbourne and Geelong) district(s) of Victoria. No fires can be lit or be allowed to remain alight in the open air from 12:01 AM on Wed, 7 Jan 2026 until 11:59 PM Wed, 7 Jan 2026.</p><p>Central: YES - TOTAL FIRE BAN IN FORCE<br>East Gippsland: NO - RESTRICTIONS MAY APPLY<br>Mallee: NO - RESTRICTIONS MAY APPLY<br>North Central: NO - RESTRICTIONS MAY APPLY<br>North East: NO - RESTRICTIONS MAY APPLY<br>Northern Country: NO - RESTRICTIONS MAY APPLY<br>South West: YES - TOTAL FIRE BAN IN FORCE<br>West and South Gippsland: NO - RESTRICTIONS MAY APPLY<br>Wimmera: YES - TOTAL FIRE BAN IN FORCE<br></p><p>Fire Danger Ratings<br/>Bureau of Meteorology forecast issued at: Wednesday, 07 January 2026 05:30 AM</p><p>Central: EXTREME<br>East Gippsland: HIGH<br>Mallee: HIGH<br>North Central: HIGH<br>North East: HIGH<br>Northern Country: HIGH<br>South West: EXTREME<br>West and South Gippsland: HIGH<br>Wimmera: EXTREME<br></p>
html;

		$this->assertSame(
			[
				'date' => '2026-01-07',
				'districts' => [
					'central' => ['tfb' => true, 'rating' => 'EXTREME'],
					'eastgippsland' => ['tfb' => false, 'rating' => 'HIGH'],
					'mallee' => ['tfb' => false, 'rating' => 'HIGH'],
					'northcentral' => ['tfb' => false, 'rating' => 'HIGH'],
					'northeast' => ['tfb' => false, 'rating' => 'HIGH'],
					'northerncountry' => ['tfb' => false, 'rating' => 'HIGH'],
        			'southwest' => ['tfb' => true, 'rating' => 'EXTREME'],
        			'westandsouthgippsland' => ['tfb' => false, 'rating' => 'HIGH'],
					'wimmera' => ['tfb' => true, 'rating' => 'EXTREME'],
				]
			],
			FdrRssFeed::parse_rss_item('Wednesday, 07 January 2026', $multipleTotalFireBans),
		);

		$noTfb = <<<html
<p>Tomorrow, Thu, 8 Jan 2026 is not currently a day of Total Fire Ban.</p><p>Central: NO - RESTRICTIONS MAY APPLY<br>East Gippsland: NO - RESTRICTIONS MAY APPLY<br>Mallee: NO - RESTRICTIONS MAY APPLY<br>North Central: NO - RESTRICTIONS MAY APPLY<br>North East: NO - RESTRICTIONS MAY APPLY<br>Northern Country: NO - RESTRICTIONS MAY APPLY<br>South West: NO - RESTRICTIONS MAY APPLY<br>West and South Gippsland: NO - RESTRICTIONS MAY APPLY<br>Wimmera: NO - RESTRICTIONS MAY APPLY<br></p><p>Fire Danger Ratings<br/>Bureau of Meteorology forecast issued at: Wednesday, 07 January 2026 05:30 AM</p><p>Central: MODERATE<br>East Gippsland: HIGH<br>Mallee: HIGH<br>North Central: HIGH<br>North East: EXTREME<br>Northern Country: EXTREME<br>South West: MODERATE<br>West and South Gippsland: HIGH<br>Wimmera: HIGH<br></p>
html;

		$this->assertEquals(
			[
				'date' => '2026-01-08',
				'districts' => [
					'central' => ['tfb' => false, 'rating' => 'MODERATE'],
					'eastgippsland' => ['tfb' => false, 'rating' => 'HIGH'],
					'mallee' => ['tfb' => false, 'rating' => 'HIGH'],
					'northcentral' => ['tfb' => false, 'rating' => 'HIGH'],
					'northeast' => ['tfb' => false, 'rating' => 'EXTREME'],
					'northerncountry' => ['tfb' => false, 'rating' => 'EXTREME'],
					'southwest' => ['tfb' => false, 'rating' => 'MODERATE'],
					'westandsouthgippsland' => ['tfb' => false, 'rating' => 'HIGH'],
					'wimmera' => ['tfb' => false, 'rating' => 'HIGH'],
				]
			],
			FdrRssFeed::parse_rss_item('Thursday, 08 January 2026', $noTfb),
		);
	}

}
