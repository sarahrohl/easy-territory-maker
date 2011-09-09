<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title></title>
    <script src='http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAjpkAC9ePGem0lIq5XcMiuhR_wWLPFku8Ix9i2SXYRVK3e45q1BQUd_beF8dtzKET_EteAjPdGDwqpQ'></script>
	<script src="http://ecn.dev.virtualearth.net/mapcontrol/mapcontrol.ashx?v=6.2&amp;mkt=en-us"></script>
	
	<link rel="stylesheet" href="jquery/jquery-ui.css" type="text/css" media="all" />
	<script src="jquery/jquery.min.js"></script>
	<script src="jquery/jquery-ui.min.js"></script>
	
    <style type="text/css">
		html, body {
            height: 100%;
			padding: 0;
			margin: 0;
        }
        .olPopup p { margin:0px; font-size: .9em;}
        .olPopup h2 { font-size:1.2em; }
    </style>
    <script src="openlayers/OpenLayers.js"></script>
    <script type="text/javascript">
        var lat = 39.594966;
        var lon = -86.116333;
        var zoom = 12;
        var map, select;
		
		$(function() {
			var height = $(window).height();
			var width = height * 1.6;
			
			$('#map')
				.height(height)
				.width(width)
				.resizable({
					aspectRatio: 1.6,
					alsoResize: '#card'
				});
				
			$('#card')
				.height(height)
				.width(width)
				.resizable({
					aspectRatio: 1.6,
					alsoResize: '#map',
					stop: function() {
						resetLabels();
					}
				})
				.trigger('resize');
			
			resetLabels();
			
			$(document).dblclick(function() {
				toggleCredits();
			});
			
            var options = {
                projection: new OpenLayers.Projection("EPSG:900913"),
                displayProjection: new OpenLayers.Projection("EPSG:4326"),
                units: "m",
				numZoomLevels:18
            };
            map = new OpenLayers.Map('map', options);
            var mapnik = new OpenLayers.Layer.OSM("OpenStreetMap");
            var gmap = new OpenLayers.Layer.Google("Google", {
				sphericalMercator: true
			});
			
			var bing = new OpenLayers.Layer.VirtualEarth("Bing", {
                sphericalMercator: true,
				type: VEMapStyle.Shaded
            });
			
            var territory = new OpenLayers.Layer.Vector("KML", {
                projection: map.displayProjection,
                strategies: [new OpenLayers.Strategy.Fixed()],
                protocol: new OpenLayers.Protocol.HTTP({
                    url: "http://localhost/territory/kmlFolder.php?map=" + $('#mapId').val(),
                    format: new OpenLayers.Format.KML({
                        extractStyles: true,
                        extractAttributes: true
                    })
                })
            });
			
			territory.events.register("loadend", territory, function (e) {
				map.zoomToExtent(territory.getDataExtent());
			});

            map.addLayers([mapnik, gmap, bing, territory]);

            select = new OpenLayers.Control.SelectFeature(territory);
            
            territory.events.on({
                "featureselected": onFeatureSelect,
                "featureunselected": onFeatureUnselect
            });
  
            map.addControl(select);
            select.activate();   

            map.addControl(new OpenLayers.Control.LayerSwitcher());
			
			map.setCenter(
				new OpenLayers.LonLat(lon, lat).transform(
					new OpenLayers.Projection("EPSG:4326"),
					new OpenLayers.Projection("EPSG:900913")
				),
				zoom
			);
        });
		
		var panZoomToggle = false;
		function toggleCredits(skip) {
			//Open Street Map
			$('div.olControlAttribution:contains("Data CC-By-SA by")').hide();
			
			panZoomToggle = !panZoomToggle;
			if (panZoomToggle) {
				$('.olControlPanZoom').hide();
			} else {
				$('.olControlPanZoom').show();
			}
			
			
			$('.maximizeDiv').toggle();
			//Google
			$('div.olLayerGooglePoweredBy,div.olLayerGoogleCopyright').hide();
			
			//Bing
			$('div.MSVE_LogoContainer,div.MSVE_Copyright').hide();
		}
		
		function resetLabels() {
			height = $('#map').height();
			width = height * 1.6;
			
			$('#cardLabel')
				.width(width * 0.7)
				.css('left', width * 0.18)
				.css('top', ($('#card').position().top + (width * 0.07)) + 'px')
				.css('font-size', (width * 0.04) + 'px');
			
			$('#north')
				.width(50)
				.height(100)
				.fadeTo(0.5, 0.5)
				.css('position', 'absolute')
				.css('top', $('#map').position().top + 120)
				.css('left', $('#map').position().left + 10);
		}
		
        function onPopupClose(evt) {
            select.unselectAll();
        }
		
        function onFeatureSelect(event) {
            var feature = event.feature;
            var selectedFeature = feature;
            var popup = new OpenLayers.Popup.FramedCloud("chicken", 
                feature.geometry.getBounds().getCenterLonLat(),
                new OpenLayers.Size(100,100),
                "<h2>"+feature.attributes.name + "</h2>" + feature.attributes.description,
                null, true, onPopupClose
            );
            feature.popup = popup;
            map.addPopup(popup);
        }
		
        function onFeatureUnselect(event) {
            var feature = event.feature;
            if(feature.popup) {
                map.removePopup(feature.popup);
                feature.popup.destroy();
                delete feature.popup;
            }
        }
    </script>
  </head>
  <body>
	<input type="hidden" id="mapId" value="<?php echo $_REQUEST['map']; ?>" />
	<img id="card" src="territory_card.png" />
	<table id="cardLabel" style="position: absolute;">
		<tr>
			<td>Valle Vista Residential</td>
			<td><?php echo $_REQUEST['map']; ?></td>
		</tr>
	</table>
	<br style="line-height: 4px;" />
	<div id="map" class="smallmap" style="border: none;"></div>
	<img id="north" src="n.png" />
  </body>
</html>
