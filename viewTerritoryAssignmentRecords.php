<?php
require_once("security.php");
require_once('lib/EasyTerritoryMaker.php');
	$etm = new EasyTerritoryMaker();
	$startingTerritoryName = $_REQUEST['at'] * 1;
	$endingTerritoryName =  $startingTerritoryName + 4;
	$list = "";
	$offset = 0;
	for($i = $startingTerritoryName; $i <= $endingTerritoryName; $i++) {
		$territoryRecord = $etm->territories[$i]->sort();

		$left = ($offset * 455) + 130;
		$list .= "<table style='position: absolute; top: 290px; left: {$left}px; font-size: 46px; width: 454px;'>
		<tr><td colspan='2' style='height: 40px; padding-left: 120px;'>$i</td></tr>";

		$offset++;

		foreach($territoryRecord->collection as $territory) {
			$out = date("m/d/Y", $territory->out);
			$in = date("m/d/Y", $territory->in);
			$list .= <<<HTML
<tr>
	<td colspan='2'>$territory->publisher</td>
</tr>
<tr>
	<td>$out</td>
	<td>$in</td>
</tr>
HTML;

		}

		$list .= '</table>';
	}
?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>Territory Assignment Records</title>
</head>
<body>
    <img id="card" src="my_files/s13.png" style="position: absolute; top: 0px; left: 0px;"/>
    <?php echo $list;?>
</body>
</html>
