<?php
	$_REQUEST = array_merge(array(
		"map" => "1",
		"congregation" => "",
		"locality" => ""
	), $_REQUEST);
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>Map <?php echo $_REQUEST['map'] ?></title>
    <script src='http://maps.google.com/maps?file=api&amp;v=2&amp;key=AIzaSyChxunYrmQJGp1binD9ROf5ZEgc-WHmT5M'></script>
    <script src="bower_components/jquery/dist/jquery.js"></script>
    <script src="bower_components/jquery-ui/ui/jquery-ui.js"></script>
    <script src="lib/Map.js"></script>
    <script src="bower_components/jquery-ui/ui/i18n/jquery-ui-i18n.js"></script>
    <link href="bower_components/jquery-ui/themes/smoothness/jquery-ui.css" type="text/css" rel="Stylesheet" />
	
    <style type="text/css">
		html, body {
            height: 100%;
			padding: 0;
			margin: 0;
        }
        .olPopup p {
            margin:0px; font-size: .9em;
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

        .olControlAttribution,
        .olControlZoom,
        .maximizeDiv {
            display: none ! important;
        }
        .ui-resizable-handle {
            background-image: none ! important;
        }
    </style>
    <script src="bower_components/OpenLayers/OpenLayers.js"></script>
    <script>
		$(function() {
			var height = $(window).height(),
			    width = height * 1.6,
                map = $('#map'),
                mapMini = $('#mapMini'),
                card = $('#card');

			mapMini
				.height(height * 0.5);
							
			map
				.height(height)
				.width(width)
				.resizable({
					aspectRatio: 1.6,
					alsoResize: '#card'
				});
				
			card
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

            new Map(map);
            new Map(mapMini, true);
        });
		
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
    </script>
  </head>
  <body>
	<input type="hidden" id="mapId" value="<?php echo $_REQUEST['map']; ?>" />
	<input type="hidden" id="locality" value="<?php echo $_REQUEST['locality']; ?>" />
	<input type="hidden" id="congregation" value="<?php echo $_REQUEST['congregation']; ?>" />
	
	<img id="card" src="my_files/card.png" />
	<table id="cardLabel" style="position: absolute;" border="0">
		<tr>
			<td style="width: 1%;"></td>
			<td style="width: 10%;"></td>
			<td style="width: 35%;"><?php echo $_REQUEST['congregation'] . ' ' . $_REQUEST['locality']; ?></td>
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
					<?php if ($_REQUEST['locality'] == "Apartment") { ?>
					<li>Work buildings highlighted in <span style="color: red;">red</span> on back.</li>
					<?php } else if ($_REQUEST['locality'] == "Business") { ?>
					<li>Work sections highlighted in <span style="color: red;">red</span> on back.</li>
					<?php } else { ?>
					<li>Work both sides of street highlighted in <span style="color: green;">green</span> on back.</li>
					<?php }?>
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
