<?php

$xmlString = file_get_contents( __DIR__ . '/../tests/data/IDV10753.ftp-forecasts.xml' ); // 'ftp://ftp.bom.gov.au/anon/gen/fwo/IDV10753.xml' );
$xml       = simplexml_load_string( $xmlString );
$areas     = [];

foreach ( $xml->forecast->area as $area ) {
	$hasForecast = false;
	foreach ( $area->{'forecast-period'} as $period ) {
		$hasForecast = true;
	}

	if ( $hasForecast ) {
		$aac           = (string) $area['aac'];
		$description   = (string) $area['description'];
		$areas[ $aac ] = $description;
	}
}

asort( $areas );

$output = "static \$areas = [\n";
foreach ( $areas as $aac => $name ) {
	$output .= "  '$aac' => '$name',\n";
}
$output .= "];\n";

echo $output;
