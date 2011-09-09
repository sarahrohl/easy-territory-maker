<?php

$file = "kmlFiles/kmlFile.kml";
$xml = simplexml_load_file($file);
$folders = $xml->xpath(
	//"//kml:Document/kml:Folder/kml:Folder/kml:Folder/kml:name/..|".
	"//kml:Document/kml:Folder/kml:Folder/kml:Folder/kml:Placemark/kml:name/.."	
);

foreach($folders as $folder) {
	//print_r(count($folder->xpath("//kml:Placemark")))."<br />";
}
print_r($folders);
die;
$displayMap = '';
foreach($xml as $doc) {
	foreach($doc as $nodes) {
		if ($nodes->name == 'Residiential') {
			foreach($nodes as $folders) {
				foreach($folders as $map) {
					if ($map->name == $_REQUEST['map']) {
						if (!empty($map->styleUrl)) {
							$map->styleUrl = '#standardStyle';
						}
						foreach($map as $layer) {
							if (!empty($layer->styleUrl)) {
								$layer->styleUrl = '#standardStyle';
							}
						}
						
						$displayMap = ($map->asXML());
						
					}
				}
			}
		}
	}
}
header ("Content-Type:text/xml"); 
echo '
<kml xmlns="http://www.opengis.net/kml/2.2" xmlns:gx="http://www.google.com/kml/ext/2.2" xmlns:kml="http://www.opengis.net/kml/2.2" xmlns:atom="http://www.w3.org/2005/Atom">
	<Document>
		<name>Valle Vista</name>
		<open>1</open>
		<Style id="standardStyle">
		<LineStyle>
			<color>660000ff</color>
			<width>5</width>
		</LineStyle>
		</Style>
		'.$displayMap.'
	</Document>
</kml>';