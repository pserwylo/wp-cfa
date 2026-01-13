<?php

namespace WP_CFA;

class BomObservations {
	public function get_latest_observation() {
		$json = $this->fetch_bom_observation_json();
		return $json['observations']['data'][0] ?? [];
	}

	/**
	 * Reads the BOM weather observations JSON for the configured weather station.
	 */
	public function fetch_bom_observation_json() {
		$cached = get_transient( 'wp_cfa_bom_observation_json' );
		if ( $cached ) {
			return $cached;
		}

		$stationUrl = Settings::get_weather_station();

		if ( !$stationUrl ) {
			_doing_it_wrong(
				__FUNCTION__,
				'No weather station selected yet. Go to Wordpress -> Admin -> Settings -> General -> WP CFA and select a weather station.',
				'1.0.0',
			);
			return [];
		}

		$response = wp_remote_get( $stationUrl );
		if ( is_wp_error( $response ) ) {
			wp_trigger_error( __FUNCTION__, 'Error retrieving BOM weather feed from ' . $stationUrl . ': ' . $response->get_error_message() );
			return [];
		}

		$body = wp_remote_retrieve_body( $response );
		if ( !$body ) {
			return [];
		}

		$json = json_decode( $body, true );
		set_transient( 'wp_cfa_bom_observation_json', $json, 10 * MINUTE_IN_SECONDS );

		return $json;
	}

	public function clear_transient(): void {
		delete_transient( 'wp_cfa_bom_observation_json' );
	}
}
