<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title></title>
    <script src='http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAjpkAC9ePGem0lIq5XcMiuhR_wWLPFku8Ix9i2SXYRVK3e45q1BQUd_beF8dtzKET_EteAjPdGDwqpQ'></script>
	<script src="http://ecn.dev.virtualearth.net/mapcontrol/mapcontrol.ashx?v=6.2&amp;mkt=en-us"></script>
	<script src="http://api.maps.yahoo.com/ajaxymap?v=3.0&appid=euzuro-openlayers"></script>
	
	<link rel="stylesheet" href="jquery-ui.css" type="text/css" media="all" />
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
        var select;
		
		$(function() {
			var height = $(window).height();
			var width = height * 1.6;
			
			$('#mapMini')
				.height(height * 0.5);
							
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
			
			$(document)
				.dblclick(function() {
					toggleControls();
					toggleCredits();
					return false;
				});
			
			$('#map').makeMap();
			$('#mapMini').makeMap(true);
        });
		
		function toggleControls() {
			this.panZoomToggle = !this.panZoomToggle;
			if (this.panZoomToggle) {
				$('.olControlPanZoom').hide();
			} else {
				$('.olControlPanZoom').show();
			}
		}
			
		function toggleCredits(skip) {
			//Open Street Map
			$('div.olControlAttribution:contains("Data CC-By-SA by")').hide();

			$('.maximizeDiv').toggle();
			//Google
			$('div.olLayerGooglePoweredBy').hide();
			$('div.olLayerGoogleCopyright').hide();
			
			//Bing
			$('div.MSVE_LogoContainer,div.MSVE_Copyright').hide();
		}
		
		function resetLabels() {
			height = $('#map').height();
			width = height * 1.6;
			
			$('#cardLabel')
				.width(width * 1)
				.css('left', 1)
				.css('top', ($('#card').position().top + (width * 0.07)) + 'px')
				.css('font-size', (width * 0.04) + 'px');
			
			$('#north')
				.css('position', 'absolute')
				.css('top', $('#map').position().top + 120)
				.css('left', $('#map').position().left + 10);
		}
		
        function onPopupClose(evt) {
            select.unselectAll();
        }
		
		$.fn.makeMap = function (mini) {
			var me = $(this);
			var map = this.map;
			var options = {
                projection: new OpenLayers.Projection("EPSG:900913"),
                displayProjection: new OpenLayers.Projection("EPSG:4326"),
                units: "m",
				numZoomLevels: 20
            };
            map = new OpenLayers.Map($(this).attr('id'), options);
            var mapnik = new OpenLayers.Layer.OSM("OpenStreetMap");
            var gmap = new OpenLayers.Layer.Google("Google", {
				sphericalMercator: true,
				
			});
			
			var y = new OpenLayers.Layer.Yahoo("Yahoo Street", {
				sphericalMercator: true
			});
			
			
			var bing = new OpenLayers.Layer.VirtualEarth("Bing", {
                sphericalMercator: true,
				type: VEMapStyle.Shaded
            });
			
			var wms = new OpenLayers.Layer.WMS("World Map");
			
            var territory = new OpenLayers.Layer.Vector("KML", {
                projection: map.displayProjection,
                strategies: [new OpenLayers.Strategy.Fixed()],
                protocol: new OpenLayers.Protocol.HTTP({
                    url: "http://localhost/territory/kmlFolder.php?map=" + $('#mapId').val() + (mini ? '&mini' : ''),
                    format: new OpenLayers.Format.KML({
                        extractStyles: true,
                        extractAttributes: true
                    })
                })
            });
			
			if (!mini) {
				territory.events.register("loadend", territory, function (e) {
					map.zoomToExtent(territory.getDataExtent());
					
					toggleCredits();
					
					me.find('div.baseLayersDiv input[value="Bing"]').next().click();
				});
			} else {
				territory.events.register("loadend", territory, function (e) {
					map.zoomToExtent(territory.getDataExtent());
					map.zoomTo(13);
					map.updateSize();
					
					toggleCredits();
					
					me.find('div.baseLayersDiv input[value="Google"]').next().click();
					
					setTimeout(function() {
						$(document).dblclick();
					}, 2000);
				});
			}
			
            map.addLayers([mapnik, gmap, y, bing, wms, territory]);

            select = new OpenLayers.Control.SelectFeature(territory);
  
            map.addControl(select);
			
			var layerSwitch = new OpenLayers.Control.LayerSwitcher();
            map.addControl(layerSwitch);
		};
    </script>
	<style>
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
  </head>
  <body>
	<input type="hidden" id="mapId" value="<?php echo $_REQUEST['map']; ?>" />
	<img id="card" src="my_files/card.png" />
	<table id="cardLabel" style="position: absolute;" border="0">
		<tr>
			<td style="width: 1%;"></td>
			<td style="width: 10%;"></td>
			<td style="width: 35%;">Valle Vista Residential</td>
			<td style="width: 1%;"></td>
			<td style="width: 10%;"></td>
			<td style="width: 12%;"><?php echo $_REQUEST['map']; ?></td>
			<td style="width: 1%;"></td>
		</tr>
		<tr>
			<td></td>
			<td colspan="2" style="text-align: center;">
				<br />
				<div id="mapMini" style="border: none;"></div>
			</td>
			<td></td>
			<td style="font-size: 14px;" valign="top" colspan="2">
				<h3>Directions</h3>
				<ul>
					<li>Work both sides of street highlighted in <span style="color: green;">green</span> on back.</li>
					<li>Keep track of do not calls on front.</li>
				</ul>
				<h3>Do Not Calls</h3>
				<table style="width: 100%;" border="1" cellspacing="0">
					<tr>
						<th>Name</th>
						<th>Address</th>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td></td>
			<td style="font-size: 12px; color: red;text-align: center;" colspan="2">
				(aerial map, larger map on back)
			</td>
		</tr>
	</table>
	<br style="line-height: 4px;" />
	<div id="map" class="smallmap" style="border: none;"></div>
	<img id="north" src="img/n.png" />
  </body>
</html>
