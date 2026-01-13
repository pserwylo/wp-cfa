<?php

namespace WP_CFA;

class Settings {
	private static string $id = 'wp_cfa';

	public static string $setting_name_district = 'wp_cfa_district';

	public static string $setting_name_weather_station = 'wp_cfa_weather_station';

	public static string $setting_name_forecast_area = 'wp_cfa_forecast_area';

	public static array $bom_forecast_areas = [
		'VIC_PT001' => 'Aireys Inlet',
		'VIC_PT084' => 'Albury-Wodonga',
		'VIC_PT002' => 'Ararat',
		'VIC_PT003' => 'Avalon',
		'VIC_PT004' => 'Bairnsdale',
		'VIC_PT005' => 'Ballarat',
		'VIC_PT006' => 'Beechworth',
		'VIC_PT007' => 'Benalla',
		'VIC_PT008' => 'Bendigo',
		'VIC_PT151' => 'Birchip',
		'VIC_PT010' => 'Cape Otway',
		'VIC_PT011' => 'Casterton',
		'VIC_PT012' => 'Castlemaine',
		'VIC_PT013' => 'Cerberus',
		'VIC_PT194' => 'Cobram',
		'VIC_PT014' => 'Colac',
		'VIC_PT016' => 'Corryong',
		'VIC_PT017' => 'Cranbourne',
		'VIC_PT203' => 'Dandenong',
		'VIC_PT204' => 'Drouin',
		'VIC_PT019' => 'Echuca',
		'VIC_PT089' => 'Edenhope',
		'VIC_PT022' => 'Falls Creek',
		'VIC_PT023' => 'Frankston',
		'VIC_PT025' => 'Geelong',
		'VIC_PT139' => 'Gisborne',
		'VIC_PT027' => 'Hamilton',
		'VIC_PT028' => 'Heywood',
		'VIC_PT029' => 'Horsham',
		'VIC_PT031' => 'Kerang',
		'VIC_PT033' => 'Kyabram',
		'VIC_PT140' => 'Kyneton',
		'VIC_PT034' => 'Lake Eildon',
		'VIC_PT167' => 'Lake Mountain',
		'VIC_PT035' => 'Lakes Entrance',
		'VIC_PT036' => 'Latrobe Valley',
		'VIC_PT037' => 'Laverton',
		'VIC_PT141' => 'Leongatha',
		'VIC_PT040' => 'Mallacoota',
		'VIC_PT169' => 'Mansfield',
		'VIC_PT041' => 'Maryborough',
		'VIC_PT042' => 'Melbourne',
		'VIC_PT143' => 'Melton',
		'VIC_PT043' => 'Mildura',
		'VIC_PT163' => 'Moe',
		'VIC_PT044' => 'Moorabbin',
		'VIC_PT110' => 'Mornington',
		'VIC_PT045' => 'Mortlake',
		'VIC_PT097' => 'Mount Baw Baw',
		'VIC_PT046' => 'Mount Buller',
		'VIC_PT047' => 'Mount Dandenong',
		'VIC_PT048' => 'Mount Hotham',
		'VIC_PT051' => 'Nhill',
		'VIC_PT053' => 'Omeo',
		'VIC_PT054' => 'Orbost',
		'VIC_PT055' => 'Ouyen',
		'VIC_PT144' => 'Pakenham',
		'VIC_PT056' => 'Phillip Island',
		'VIC_PT059' => 'Port Fairy',
		'VIC_PT060' => 'Portland',
		'VIC_PT061' => 'Redesdale',
		'VIC_PT062' => 'Rhyll',
		'VIC_PT063' => 'Rutherglen',
		'VIC_PT064' => 'Sale',
		'VIC_PT065' => 'Scoresby',
		'VIC_PT066' => 'Seymour',
		'VIC_PT068' => 'Shepparton',
		'VIC_PT069' => 'Stawell',
		'VIC_PT146' => 'Sunbury',
		'VIC_PT071' => 'Swan Hill',
		'VIC_PT072' => 'Tatura',
		'VIC_PT134' => 'Torquay',
		'VIC_PT205' => 'Traralgon',
		'VIC_PT073' => 'Tullamarine',
		'VIC_PT075' => 'Wangaratta',
		'VIC_PT076' => 'Warracknabeal',
		'VIC_PT148' => 'Warragul',
		'VIC_PT077' => 'Warrnambool',
		'VIC_PT078' => 'Watsonia',
		'VIC_PT079' => 'Weeaproinah',
		'VIC_PT080' => 'Wilsons Promontory',
		'VIC_PT081' => 'Wonthaggi',
		'VIC_PT082' => 'Yarra Glen',
		'VIC_PT083' => 'Yarrawonga',
	];

	public static $bom_weather_stations = [
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94846.json' => 'Aireys Inlet',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.95896.json' => 'Albury',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94834.json' => 'Ararat',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94854.json' => 'Avalon',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94912.json' => 'Bairnsdale',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.99820.json' => 'Ballan (CFA)',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94852.json' => 'Ballarat',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94835.json' => 'Ben Nevis',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94884.json' => 'Benalla',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94855.json' => 'Bendigo',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94826.json' => 'Cape Nelson',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94842.json' => 'Cape Otway',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.95825.json' => 'Casterton',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94898.json' => 'Cerberus',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94839.json' => 'Charlton',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94864.json' => 'Coldstream',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94914.json' => 'Combienbar',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.99813.json' => 'Cressy (CFA)',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.95822.json' => 'Dartmoor',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.95907.json' => 'East Sale Airport',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94861.json' => 'Echuca',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.95832.json' => 'Edenhope',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94881.json' => 'Eildon Fire Tower',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.95866.json' => 'Essendon Airport',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94903.json' => 'Falls Creek',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.95872.json' => 'Fawkner Beacon',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94872.json' => 'Ferny Creek',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94876.json' => 'Frankston (Ballam Park)',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94871.json' => 'Frankston Beach',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94933.json' => 'Gabo Island',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94857.json' => 'Geelong Racecourse',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94913.json' => 'Gelantipy',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.99826.json' => 'Gerangamete (CFA)',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.99822.json' => 'Glenburn (CFA)',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94829.json' => 'Hamilton',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94949.json' => 'Hogan Island',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94838.json' => 'Hopetoun Airport',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.95839.json' => 'Horsham',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94878.json' => 'Hunters Hill',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94844.json' => 'Kerang',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94860.json' => 'Kilmore Gap',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.95833.json' => 'Kyabram',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94888.json' => 'Lake Dartmouth',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94882.json' => 'Lake Eildon',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94891.json' => 'Latrobe Valley',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94865.json' => 'Laverton',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.95835.json' => 'Longerenong',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94935.json' => 'Mallacoota',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94874.json' => 'Mangalore',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94849.json' => 'Maryborough',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.95936.json' => 'Melbourne (Olympic Park)',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94866.json' => 'Melbourne Airport',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94693.json' => 'Mildura',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94870.json' => 'Moorabbin Airport',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94840.json' => 'Mortlake',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.95901.json' => 'Mount Baw Baw',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94894.json' => 'Mount Buller',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.95845.json' => 'Mount Gellibrand',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94906.json' => 'Mount Hotham',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94905.json' => 'Mount Hotham Airport',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.95913.json' => 'Mount Moornapa',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94930.json' => 'Mount Nowa Nowa',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94833.json' => 'Mount William',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.99827.json' => 'Mt Burnett (CFA)',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94827.json' => 'Nhill Aerodrome',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94908.json' => 'Omeo',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.95918.json' => 'Orbost',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.95941.json' => 'Point Cook',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94847.json' => 'Point Wilson',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94830.json' => 'Port Fairy',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94828.json' => 'Portland Airport',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.95826.json' => 'Portland Harbour',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94886.json' => 'Pound Creek',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94856.json' => 'Puckapunyal West (Defence)',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94858.json' => 'Puckapunyal-Lyon Hill (Defence)',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94859.json' => 'Redesdale',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94892.json' => 'Rhyll',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.95837.json' => 'Rutherglen',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.95867.json' => 'Scoresby',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94863.json' => 'She Oaks',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94875.json' => 'Shepparton',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94853.json' => 'South Channel Island',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.95864.json' => 'St Kilda Harbour RMYS',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94836.json' => 'Stawell',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94843.json' => 'Swan Hill',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.95836.json' => 'Tatura',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.99821.json' => 'Trentham East (CFA)',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.99900.json' => 'Victoria Portable AWS M (CFA)',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.99901.json' => 'Victoria Portable AWS N (CFA)',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.95874.json' => 'Viewbank',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.95831.json' => 'Walpeup',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94889.json' => 'Wangaratta',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94920.json' => 'Warracknabeal Airport',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.99806.json' => 'Warragul (Nilma North)',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94837.json' => 'Warrnambool',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.95840.json' => 'Westmere',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94893.json' => 'Wilsons Promontory',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.99815.json' => 'Wycheproof (CFA)',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.95890.json' => 'Yarram Airport',
		'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94862.json' => 'Yarrawonga',
	];

	public static function init(): void {
		register_setting( self::$id, self::$setting_name_district, [
			'default'           => 'central',
			'sanitize_callback' => function ( $value ) {
				return isset( Utils::$districts[ $value ] ) ? $value : 'central';
			},
		] );

		register_setting( self::$id, self::$setting_name_forecast_area, [
			'default'           => 'VIC_PT042', // Melbourne.
			'sanitize_callback' => function ( $value ) {
				return isset( self::$bom_forecast_areas[ $value ] ) ? $value : 'VIC_PT042';
			},
		] );

		register_setting( self::$id, self::$setting_name_weather_station, [
			'default'           => 'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94866.json', // Melbourne (Olympic park).
			'sanitize_callback' => function ( $value ) {
				return isset( self::$bom_weather_stations[ $value ] ) ? $value : 'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.94866.json';
			},
		] );

		add_settings_field(
			self::$setting_name_district,
			'District',
			[ self::class, 'district_field_callback' ],
			self::$id,
		);

		add_settings_field(
			self::$setting_name_weather_station,
			'Weather Station',
			[ self::class, 'weather_station_field_callback' ],
			self::$id,
		);

		add_settings_field(
			self::$setting_name_forecast_area,
			'Forecast Area',
			[ self::class, 'forecast_area_field_callback' ],
			self::$id,
		);
	}

	public static function init_menu(): void {
		add_options_page(
			'WP CFA Settings',
			'WP CFA',
			'manage_options',
			self::$id,
			[ self::class, 'render_form' ],
		);
	}

	public static function get_district() {
		return get_option( self::$setting_name_district, 'central' );
	}

	public static function get_weather_station() {
		return get_option( self::$setting_name_weather_station, 'https://reg.bom.gov.au/fwo/IDV60801/IDV60801.95936.json' );
	}

	public static function get_forecast_area() {
		return get_option( self::$setting_name_forecast_area, 'VIC_PT042' );
	}

	public static function district_field_callback(): void {
		$settingValue = self::get_district();
		$settingName  = self::$setting_name_district;
		$options      = '';

		foreach ( Utils::$districts as $districtId => $districtLabel ) {
			$selected = $districtId === $settingValue ? 'selected="selected"' : '';
			$options .= "<option value='" . esc_attr( $districtId ) . "' {$selected}>" . esc_html( $districtLabel ) . '</option>';
		}
		echo <<<html
<select name="{$settingName}">
	{$options}
</select>
<p class="description">
	Required to ascertain the fire danger rating and total fire ban info. See <a href="https://www.cfa.vic.gov.au/warnings-restrictions/fire-bans-ratings-and-restrictions/total-fire-bans-fire-danger-ratings">Total Fire Bans &amp; Fire Danger Ratings</a> for details.
	This is cached for 1 hour.
</p>
html;
	}

	public static function weather_station_field_callback(): void {
		$settingValue = self::get_weather_station();
		$settingName  = self::$setting_name_weather_station;
		$options      = '';

		foreach ( self::$bom_weather_stations as $url => $name ) {
			$selected = $url === $settingValue ? 'selected="selected"' : '';
			$options .= "<option value='" . esc_url( $url ) . "' {$selected}>" . esc_html( $name ) . '</option>';
		}

		echo <<<html
<select name="{$settingName}">
	{$options}
</select>
<p class="description">
	The list of weather stations is from the
	<a href="https://www.bom.gov.au/vic/observations/vicall.shtml">list of Victorian weather stations on the BOM website</a>.
	Choose the closest to your brigade. This is cached for 10 minutes.
</p>
html;
	}

	public static function forecast_area_field_callback(): void {
		$settingValue = self::get_forecast_area();
		$settingName  = self::$setting_name_forecast_area;
		$options      = '';

		foreach ( self::$bom_forecast_areas as $aac => $name ) {
			$selected = $aac === $settingValue ? 'selected="selected"' : '';
			$options .= "<option value='" . esc_attr( $aac ) . "' {$selected}>" . esc_html( $name ) . '</option>';
		}

		echo <<<html
<select name="{$settingName}">
	{$options}
</select>
<p class="description">
	Forecast areas from the BOM. Doesn't exactly match up with the weather stations for observations, due to the data provided by the BOM.
	Choose the closest to your brigade. This is cached for 1 hour.
</p>
html;
	}

	public static function render_form(): void {
		if ( !current_user_can( 'manage_options' ) ) {
			return;
		}

		// Visiting settings is done so irregularly, that we may as well use it as an.
		// opportunity to force these to clear. Especially seeing as the user is probably.
		// here to change the forecast district or something else like that.
		( new BomObservations() )->clear_transient();
		( new BomForecasts() )->clear_transient();
		FdrRssFeed::clear_transient();

		settings_errors( self::$id );
		?>
		<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post">
			<table class="form-table" role="presentation">
			<?php
			settings_fields( self::$id );
			do_settings_fields( self::$id, 'default' );
			?>
			</table>
			<?php submit_button(); ?>
		</form>
		<?php
	}
}
