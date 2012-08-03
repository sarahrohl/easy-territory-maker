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
			$('#maps').tabs();
		});
	</script>
</head>
<?php
include_once('lib/etm.php');
$etm = new etm();

$tabs = "";
$tabContents = "";
foreach($etm->all() as $index => $locality) {
	$tabs .= "<li><a href='#tab$index'>" . $locality->name . "</a></li>";

	$tabContents .= "<div id='tab$index'><ul>";
	foreach($locality as $map) {
		if (!empty($map->name)) {
			$tabContents .= "<li><a href='viewMap.php?map=" . $map->name . "&locality=" . $locality->name . "'>" . $map->name . "</a></li>";
		}
	}
	$tabContents .= "</ul></div>";
}
?>
<div id="maps">
	<?php echo "<ul>" . $tabs . "</ul>" . $tabContents?>
</div>
</html>