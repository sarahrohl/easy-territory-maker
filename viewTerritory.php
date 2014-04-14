<?php
	$_REQUEST = array_merge(array(
		"territory" => "1",
		"congregation" => "",
		"locality" => ""
	), $_REQUEST);

    if (!file_exists('my_files/card.png')) {
        throw new Exception("It looks like you don't yet have the 'card.png' file in the 'my_files' folder.  Please scan a S-12-E or similar and place there to continue.  This is not a digitally distributed file, which is why this measure is in place.");
    }

	require_once('lib/EasyTerritoryMaker.php');
	$etm = new EasyTerritoryMaker();
	$territory = $etm->lookup($_REQUEST['territory']);


?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>Territory <?php echo $territory->territory ?></title>
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
<?php if (!isset($_REQUEST['debug'])) {?>
    <style>
        .olControlAttribution,
        .olControlZoom,
        .maximizeDiv,
        .gmnoprint,
        .gm-style a {
            display: none ! important;
        }
        .ui-resizable-handle {
            background-image: none ! important;
        }
    </style>
<?php }?>
    <script src="bower_components/OpenLayers/lib/OpenLayers.js"></script>
    <script>
        $(function() {
            var height = $(window).height(),
                width = height * 1.6,
                map = $('#map'),
                mapMini = $('#mapMini'),
                territoryName = $('#territory').val(),
                locality = $('#locality').val(),
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

            console.log(new Map(map, territoryName, locality));
            console.log(new Map(mapMini, territoryName, locality, true));
        });

        function resetLabels() {
            var map = $('#map'),
                card = $('#card'),
                mapPosition = map.position(),
                cardPosition = card.position(),
                height = map.height(),
                width = height * 1.6;

            $('#cardLabel').css({
                width: width + 'px',
                left: '1px',
                top: (cardPosition.top + (width * 0.08)) + 'px',
                fontSize: (width * 0.04) + 'px'
            });

            $('#north').css({
                position: 'absolute',
                top: (mapPosition.top + 120) + 'px',
                left: (mapPosition.left + 10) + 'px'
            });
        }

        function onPopupClose(evt) {
            select.unselectAll();
        }
    </script>
</head>
<body>
    <input type="hidden" id="territory" value="<?php echo $territory->territory; ?>" />
    <input type="hidden" id="locality" value="<?php echo $territory->locality; ?>" />
    <input type="hidden" id="congregation" value="<?php echo $territory->congregation; ?>" />

    <img id="card" src="my_files/card.png" />
    <table id="cardLabel" style="position: absolute;" border="0">
        <tr>
            <td style="width: 3%;"></td>
            <td style="width: 10%;"></td>
            <td style="width: 35%; white-space:nowrap;"><?php echo $territory->locality; ?></td>
            <td style="width: 1%;"></td>
            <td style="width: 10%;"></td>
            <td style="width: 12%;"><?php echo $territory->territory; ?></td>
            <td style="width: 1%;"></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2" style="text-align: center;">
                <br />
                <div id="mapMini" style="border: none;"></div>
            </td>
            <td></td>
            <td style="font-size: 14px;" colspan="2">
                <h3>Directions</h3>
                <ul>
                    <li>Work <span style="font-weight: bold;">houses, apartments, and businesses</span> that are encompassed within the <span style="color: green;">green highlighted area</span>.</li>
                    <li>Keep track of do not calls on front.</li>
                </ul>
                <h3>Do Not Calls</h3>
                <table style="width: 100%;" border="1">
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
