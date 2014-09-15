<?php
require_once("../security.php");

$_REQUEST = array_merge(array(
    "congregation" => ""
), $_REQUEST);

?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">

    <title><?php echo $_REQUEST['congregation'];?> Territory</title>

    <style type="text/css">
        html, body {
            height: 100%;
            padding: 0;
            margin: 0;
        }
        .olPopup p {
            margin:0px;
            font-size: .9em;
        }
        .olPopup h2 {
            font-size:1.2em;
        }
        #cardLabel table, #cardLabel table td {
            border: 1px solid #C3C3C3;
            border-collapse: collapse;
        }
        #cardLabel table th {
            border: 1px solid #C3C3C3;
            text-align: center;
            font-weight: bold;
        }
        h3 {
            margin: 0px;
            padding: 0px;
            width: 100%;
        }
    </style>
	<link href="../bower_components/leaflet/dist/leaflet.css" type="text/css" rel="Stylesheet" />
	<link href="../bower_components/leaflet.label/dist/leaflet.label.css" type="text/css" rel="Stylesheet" />
	<link href="../bower_components/leaflet.labeloverlay/leaflet.labelOverlay.css" type="text/css" rel="Stylesheet" />

	<script src="../bower_components/jquery/dist/jquery.js"></script>
	<script src="../bower_components/leaflet/dist/leaflet-src.js"></script>
    <script src="../bower_components/poly2tri/dist/poly2tri.js"></script>
	<script src="../bower_components/leaflet.labeloverlay/leaflet.labelOverlay.js"></script>
	<script src="../bower_components/togeojson/togeojson.js"></script>

    <script>
	    $(function() {
		    $.when(
			    $.ajax("../my_files/territory.kml")
		    ).then(function(mapXml) {

			    var map = L.map('map'),
				    mapGeoJson = L.geoJson(toGeoJSON.kml(mapXml));

			    map.fitBounds(mapGeoJson.getBounds());

			    L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
				    attribution: '&nbsp;'
			    }).addTo(map);

			    mapGeoJson.eachLayer(function(layer) {
				    var label = layer.feature.properties.name,
					    labelOverlay = new L.LabelOverlay(layer, label);

				    map.addLayer(labelOverlay);
			    }).addTo(map);
		    });
	    });
    </script>
</head>
<body>
<div id="map" style="width: 8000px;  height: 10000px;"></div>
</body>
</html>
