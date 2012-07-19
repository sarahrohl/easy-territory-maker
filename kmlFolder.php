<?php

$xml = simplexml_load_file('my_files/territory.kml');
$ns = $xml->getDocNamespaces();
if(isset($ns[""])){
 	$xml->registerXPathNamespace('kml', 'http://earth.google.com/kml/2.2');
}
$folders = $xml->xpath("//kml:Document/kml:Folder[kml:name/text()='" . $_REQUEST['locality'] . "']/kml:Folder[kml:name/text()='" . $_REQUEST['map'] . "']");
$placemarks = $xml->xpath("//kml:Document/kml:Folder[kml:name/text()='" . $_REQUEST['locality'] . "']/kml:Placemark[kml:name/text()='" . $_REQUEST['map'] . "']");

$displayMap = '';

//list folders if is set
if (empty($folders) == false) {
	foreach($folders as $folder) {
		foreach($folder->Placemark as $mark) {
			$placemark->styleUrl = '#standardStyle';
		}
		$displayMap = $folder->asXML();
	}
}

//list placemarks if is set
if (empty($placemarks) == false) {
	foreach($placemarks as $placemark) {
		$placemark->styleUrl = '#standardStyle';
		$displayMap = $placemark->asXML();
	}
}

header ("Content-Type:text/xml"); 
echo '
<kml xmlns="http://www.opengis.net/kml/2.2" xmlns:gx="http://www.google.com/kml/ext/2.2" xmlns:kml="http://www.opengis.net/kml/2.2" xmlns:atom="http://www.w3.org/2005/Atom">
	<Document>
		<name>'.(isset($_REQUEST['congregation']) ? $_REQUEST['congregation'] : "Valle Vista").'</name>
		<open>1</open>
		<Style id="standardStyle">
		<LineStyle>
			<color>'.(isset($_REQUEST['mini']) ? 
					'990000ff' : 
					($_REQUEST['locality'] == 'Apartment' ? 
						'990000ff' : 
						'4000ff00'
				)
			).'</color>
			<width>5</width>
		</LineStyle>
		</Style>
		'.$displayMap.'
	</Document>
</kml>';
