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
    <script src='http://maps.google.com/maps?file=api&amp;v=2&amp;key=AIzaSyChxunYrmQJGp1binD9ROf5ZEgc-WHmT5M'></script>
    <script src="../bower_components/jquery/dist/jquery.js"></script>
    <script src="../bower_components/jquery-ui/ui/jquery-ui.js"></script>
    <script src="../lib/Map.js"></script>
    <script src="../bower_components/jquery-ui/ui/i18n/jquery-ui-i18n.js"></script>
    <link href="../bower_components/jquery-ui/themes/smoothness/jquery-ui.css" type="text/css" rel="Stylesheet" />

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
    <script src="../bower_components/OpenLayers/lib/OpenLayers.js"></script>
    <script>
	    $(function() {
		    console.log(new Map($('#map')));

	    });
    </script>
</head>
<body>
<div id="map" style="width: 8000px;  height: 10000px;"></div>
</body>
</html>
