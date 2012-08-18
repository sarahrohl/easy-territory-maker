<?php

include_once('lib/etm.php');

$etm = new etm();

header ("Content-Type:text/xml"); 

$borderColor = '';
$fillColor = '';

switch (!empty($_REQUEST['locality']) ? $_REQUEST['locality'] : 'Apartment') {
	case "Business":
		$borderColor = '660000ff';
		$fillColor = '660000ff';
		break;
	default:
		$borderColor = '4000ff00';
		$fillColor = '00000000';
		break;
}

if (isset($_REQUEST['mini'])) {
	$borderColor = '660000ff';
}

echo '
<kml xmlns="http://www.opengis.net/kml/2.2" xmlns:gx="http://www.google.com/kml/ext/2.2" xmlns:kml="http://www.opengis.net/kml/2.2" xmlns:atom="http://www.w3.org/2005/Atom">
	<Document>
		<name>'.(isset($_REQUEST['congregation']) ? $_REQUEST['congregation'] : "Valle Vista").'</name>
		<open>1</open>
		<Style id="standardStyle">
		<LineStyle>
			<color>'.$borderColor.'</color>
			<width>5</width>
		</LineStyle>
		<PolyStyle>
			<color>' . $fillColor . '</color>
		</PolyStyle>
		</Style>
		'.$etm->getMap($_REQUEST['locality'], $_REQUEST['map']).'
	</Document>
</kml>';
