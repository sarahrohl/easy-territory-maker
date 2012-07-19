<!DOCTYPE html>
<html>
<head>
	<title>Easy Territory Maker</title>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script src="http://code.jquery.com/ui/1.8.21/jquery-ui.min.js"></script>
	<script src="http://jquery-ui.googlecode.com/svn/tags/latest/ui/minified/i18n/jquery-ui-i18n.min.js"></script>
	<link href="http://jquery-ui.googlecode.com/svn/tags/latest/themes/smoothness/jquery.ui.all.css" type="text/css" rel="Stylesheet" />
	<script>
		$(function() {
			$('#maps').accordion({
				autoHeight: false
			});
		});
	</script>
</head>
<div id="maps">
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
</div>
</html>