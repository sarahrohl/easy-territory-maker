<!DOCTYPE html>
<html>
<head>
	<title>Easy Territory Maker</title>
    <script src="bower_components/jquery/dist/jquery.js"></script>
    <script src="bower_components/jquery-ui/ui/jquery-ui.js"></script>
    <script src="bower_components/jquery-ui/ui/i18n/jquery-ui-i18n.js"></script>
    <link href="bower_components/jquery-ui/themes/smoothness/jquery-ui.css" type="text/css" rel="Stylesheet" />
</head>
<?php
include_once('lib/etm.php');
$etm = new etm();

$tabContents = "";
foreach($etm->all() as $index => $locality) {
	$tabContents .= "<div id='tab$index'><ul>";

    if (!empty($locality->Placemark)) {
        foreach($locality as $map) {
            if (!empty($map->name)) {
                $tabContents .= "<li><a href='viewMap.php?map=" . $map->name . "&locality=" . $locality->name . "'>" . $map->name . ' - ' . $locality->name . ' ' . "</a></li>";
            }
        }
    } else {
        $tabContents .= "<li><a href='viewMap.php?map=" . $locality->name . "'>" . $locality->name . "</a></li>";
    }
	$tabContents .= "</ul></div>";
}
?>
<div id="maps">
	<?php echo $tabContents?>
</div>
</html>