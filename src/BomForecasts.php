<?php

namespace WP_CFA;

use DateTimeImmutable;
use WP_Exception;

use function ftp_close;
use function wp_trigger_error;

class BomForecasts {
	private static string $iconBaseUrl = 'https://reg.bom.gov.au/weather-services/images/symbols/large';

	private static array $icons = [
		1  => 'sunny',
		2  => 'clear',
		3  => 'partly-cloudy',
		4  => 'cloudy',
		6  => 'haze',
		8  => 'light-rain',
		9  => 'wind',
		10 => 'fog',
		11 => 'showers',
		12 => 'rain',
		13 => 'dust',
		14 => 'frost',
		15 => 'snow',
		16 => 'storm',
		17 => 'light-showers',
		18 => 'heavy-showers',
		19 => 'tropicalcyclone',
	];

	/**
	 * @throws WP_Exception
	 */
	public function fetch_bom_forecast_xml_string(): string|null {
		$cached = get_transient( 'wp_cfa_bom_forecast_xml' );
		if ( $cached ) {
			return $cached;
		}

		if ( !extension_loaded( 'ftp' ) ) {
			_doing_it_wrong(
				__FUNCTION__,
				'PHP FTP module is not installed. Unable to fetch forecast from BOM FTP server.',
				'1.0.0',
			);
			return null;
		}

		$ftp = ftp_connect( 'ftp.bom.gov.au' );

		if ( !$ftp ) {
			wp_trigger_error( __FUNCTION__, 'Unable to connect to ftp.bom.gov.au' );
			return null;
		}

		if ( !ftp_login( $ftp, 'anonymous', '' ) ) {
			wp_trigger_error( __FUNCTION__, 'Unable to login to ftp.bom.gov.au' );
			ftp_close( $ftp );
			return null;
		}

		if ( !ftp_pasv( $ftp, true ) ) {
			wp_trigger_error( __FUNCTION__, 'Unable to pasv to ftp host' );
			return null;
		}

		ob_start();
		$result = ftp_get( $ftp, 'php://output', '/anon/gen/fwo/IDV10753.xml' );
		$string = ob_get_clean();

		if ( !$result || !$string ) {
			wp_trigger_error( __FUNCTION__, 'FTP get failed for BOM forecasts' );
			return null;
		}

		ftp_close( $ftp );

		set_transient( 'wp_cfa_bom_forecast_xml', $string, HOUR_IN_SECONDS );
		return $string;
	}

	public function process_forecast_xml( string $xmlString ): array {
		$xml   = simplexml_load_string( $xmlString );
		$areas = [];

		foreach ( $xml->forecast->area as $area ) {
			$forecasts = [];
			foreach ( $area->{'forecast-period'} as $period ) {
				$icon           = $period->xpath( './element[@type="forecast_icon_code"]' );
				$max            = $period->xpath( './element[@type="air_temperature_maximum"]' );
				$min            = $period->xpath( './element[@type="air_temperature_minimum"]' );
				$precis         = $period->xpath( './text[@type="precis"]' );
				$rainProb       = $period->xpath( './text[@type="probability_of_precipitation"]' );
				$rainRange      = $period->xpath( './element[@type="precipitation_range"]' );
				$startTimeLocal = (string) $period['start-time-local'];

				$iconCode    = count( $icon ) ? (int)$icon[0] : null;
				$forecasts[] = [
					'date'              => DateTimeImmutable::createFromFormat( 'X-m-d\\TH:i:sP', $startTimeLocal )->format( 'Y-m-d' ), // Polyfill DateTimeImmutable::ISO8601_EXPANDED.
					'icon'              => $iconCode,
					'iconColourUrl'     => $iconCode ? self::$iconBaseUrl . '/' . self::$icons[ $iconCode ] . '.png' : null,
					'iconMonochromeUrl' => $iconCode ? self::$iconBaseUrl . '/mobile/' . self::$icons[ $iconCode ] . '.png' : null,
					'max'               => count( $max ) ? (int)$max[0] : null,
					'min'               => count( $min ) ? (int)$min[0] : null,
					'precis'            => count( $precis ) ? (string)$precis[0] : null,
					'rainProb'          => count( $rainProb ) ? (string)$rainProb[0] : null,
					'rainRange'         => count( $rainRange ) ? (string)$rainRange[0] : null,
				];
			}

			$aac         = (string) $area['aac'];
			$description = (string) $area['description'];

			if ( $forecasts ) {
				$areas[ $aac ] = [
					'forecasts'   => $forecasts,
					'description' => $description,
				];
			}
		}

		return $areas;
	}

	public function get_forecasts() {
		$xml = $this->fetch_bom_forecast_xml_string();
		if ( !$xml ) {
			return [];
		}

		return $this->process_forecast_xml( $xml );
	}

	/**
	 * @throws WP_Exception
	 */
	public function get_area_forecasts(): array|null {
		$area = Settings::get_forecast_area();

		if ( !$area ) {
			_doing_it_wrong(
				__FUNCTION__,
				'No forecast area selected yet. Go to Wordpress -> Admin -> Settings -> General -> WP CFA and select a weather station.',
				'1.0.0',
			);
			return null;
		}

		$forecasts = $this->get_forecasts();

		if ( !isset( $forecasts[ $area ] ) ) {
			wp_trigger_error(
				__FUNCTION__,
				'Unable to find forecast for "' . $area . '".',
			);
			return null;
		}

		return $forecasts[ $area ]['forecasts'];
	}

	public function clear_transient(): void {
		delete_transient( 'wp_cfa_bom_forecast_json' );
	}
}
