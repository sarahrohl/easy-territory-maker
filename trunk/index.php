<html>
<head>
	<title>Easy Territory Maker</title>
</head>
<?php

include_once('lib/etm.php');

$etm = new etm();

foreach($etm->all() as $locality) {
	echo "<h2>" . $locality->name . "</h2>";
	echo "<ul>";
	foreach($locality as $map) {
		if (!empty($map->name)) {
			echo "<li><a href='viewMap.php?map=" . $map->name . "&locality=" . $locality->name . "'>" . $map->name . "</a></li>";
		}
	}
	echo "</ul>";
}
?>
</html>