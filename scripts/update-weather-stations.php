<?php

$html = file_get_contents('https://www.bom.gov.au/vic/observations/vicall.shtml');
$tegex = '/<th id="t.*-station-.*" class="rowleftcolumn"><a href="\/products(\/IDV.*\/IDV.*..*.shtml)">(.*)<\/a><\/th>/';

$matches = array();

// Capture each of the table cells using the regex above
preg_match_all($tegex, $html, $matches, PREG_SET_ORDER, );

$baseUrl = 'https://reg.bom.gov.au/fwo';
$stations = array();
foreach($matches as $match) {
	$url = $baseUrl . preg_replace('/(.*)\.shtml$/', '$1.json', $match[1]);
	$name = $match[2];

	$stations[$url] = $name;
}

asort($stations);

$output = "static \$stations = [\n";
foreach ($stations as $url => $name) {
	$output .= "  '$url' => '$name',\n";
}
$output .= "]\n";

echo $output;
