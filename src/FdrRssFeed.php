<?php

namespace WP_CFA;

class FdrRssFeed
{

	/**
	 * Extracts total fire ban flags and fire danger ratings from the description of the CFA RSS feed.
	 * Example description (I've formatted for clarity, but it typically comes on one line:
	 *
	 *   <p>
	 *       Today, Wed, 7 Jan 2026 has been declared a day of Total Fire Ban in the Wimmera, South West and Central (includes Melbourne and Geelong) district(s) of Victoria. No fires can be lit or be allowed to remain alight in the open air from 12:01 AM on Wed, 7 Jan 2026 until 11:59 PM Wed, 7 Jan 2026.
	 *   </p>
	 *   <p>
	 *       Central: YES - TOTAL FIRE BAN IN FORCE
	 *       <br>
	 *       East Gippsland: NO - RESTRICTIONS MAY APPLY
	 *       <br>
	 *       Mallee: NO - RESTRICTIONS MAY APPLY
	 *       <br>
	 *       North Central: NO - RESTRICTIONS MAY APPLY
	 *       <br>
	 *       North East: NO - RESTRICTIONS MAY APPLY
	 *       <br>
	 *       Northern Country: NO - RESTRICTIONS MAY APPLY
	 *       <br>
	 *       South West: YES - TOTAL FIRE BAN IN FORCE
	 *       <br>
	 *       West and South Gippsland: NO - RESTRICTIONS MAY APPLY
	 *       <br>
	 *       Wimmera: YES - TOTAL FIRE BAN IN FORCE
	 *       <br>
	 *   </p>
	 *   <p>
	 *       Fire Danger Ratings<br/>Bureau of Meteorology forecast issued at: Wednesday, 07 January 2026 05:30 AM
	 *   </p>
	 *   <p>
	 *       Central: EXTREME
	 *       <br>
	 *       East Gippsland: HIGH
	 *       <br>
	 *       Mallee: HIGH
	 *       <br>
	 *       North Central: HIGH
	 *       <br>
	 *       North East: HIGH
	 *       <br>
	 *       Northern Country: HIGH
	 *       <br>
	 *       South West: EXTREME
	 *       <br>
	 *       West and South Gippsland: HIGH
	 *       <br>
	 *       Wimmera: EXTREME
	 *       <br>
	 *   </p>
	 *
	 * And returns data in the format:
	 *
	 * [
	 *   'date' => '2025-01-07',
	 *   'districts' => [
	 *     'central' => ['tfb' => true, 'rating' => 'EXTREME'],
	 *     'eastgippsland' => [...],
	 *     ...
	 *   ]
	 * ]
	 */
    static function parse_rss_item($title, $html)
    {
        $dom = new \DOMDocument();

        // Suppress warnings for malformed HTML
        @$dom->loadHTML($html);

        $data = [];
        $paragraphs = $dom->getElementsByTagName('p');
        foreach ($paragraphs as $p) {
			if ($p->childNodes->length <= 1) {
				$data[] = $p->nodeValue;
			} else {
				$nodes = [];
				foreach ($p->childNodes as $node) {
					if ($node->nodeValue) {
						$nodes[] = $node->nodeValue;
					}
				}
				$data[] = $nodes;
			}
        }

        // Format is "Tuesday, 05 January 2025"
        $date = \DateTimeImmutable::createFromFormat('l, d F Y', $title)->format('Y-m-d');

		$tfbs = [];

		if (count($data) >= 1) {
			foreach ($data[1] as $tfbData) {
				$parts = explode(": ", strtolower($tfbData));
				$district = Utils::district_to_id($parts[0]);
				$tfb = str_starts_with($parts[1] ?? '', 'yes');
				$tfbs[$district] = $tfb;
			}
		}

		$ratings = [];
		if (count($data) >= 3) {
			foreach ($data[3] as $ratingData) {
				$parts = explode(": ", strtolower($ratingData));
				$district = Utils::district_to_id($parts[0]);
				$rating = $parts[1] ?? '';
				$ratings[$district] = $rating;
			}
		}

		$result = ['date' => $date, 'districts' => []];
		foreach(Utils::$districts as $districtId => $districtLabel) {
			$result['districts'][$districtId] = [
				'tfb' => $tfbs[$districtId],
				'rating' => $ratings[$districtId],
			];
		}

		return $result;
    }

	/**
	 * Reads the fire danger rating and total fireban forecast
	 * feed from https://www.cfa.vic.gov.au/cfa/rssfeed/tfbfdrforecast_rss.xml
	 * and for each of the next 4 days, returns an array containing tfb and ratings for each district.
	 */
    static function fire_danger_rating_feed()
    {
        $feed_url = "https://www.cfa.vic.gov.au/cfa/rssfeed/tfbfdrforecast_rss.xml";

        $rss = fetch_feed($feed_url);

        if (is_wp_error($rss)) {
            return [];
        }

        $items = $rss->get_items(0, 4);
        $info = [];
        foreach ($items as $item) {
            $info[] = self::parse_rss_item($item->get_title(), $item->get_description());
        }

        return $info;
    }
}
