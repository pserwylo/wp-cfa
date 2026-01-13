<?php

$html  = file_get_contents( 'https://reg.bom.gov.au/info/forecast_icons.shtml' );
$tegex = '/<img src="\/weather-services\/images\/symbols\/large\/mobile\/(.+?)(-night)?.png/';

$matches = [];

// Capture each of the table cells using the regex above.
preg_match_all( $tegex, $html, $matches, PREG_SET_ORDER, );

// https://reg.bom.gov.au/weather-services/images/symbols/large/mobile/light-showers.png.
$baseUrl = 'https://reg.bom.gov.au/fwo';
$icons   = [];
foreach ( $matches as $match ) {
	$name  = $match[1];
	$night = isset( $match[2] );

	if ( !$night ) {
		$icons[] = $name;
	}
}

print_r( $icons );
exit;

$output = "static \$stations = [\n";
foreach ( $stations as $url => $name ) {
	$output .= "  '$url' => '$name',\n";
}
$output .= "]\n";

echo $output;
