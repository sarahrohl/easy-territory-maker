<?php

include_once('lib/etm.php');

$etm = new etm();

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
		'.$etm->getMap($_REQUEST['locality'], $_REQUEST['map']).'
	</Document>
</kml>';
