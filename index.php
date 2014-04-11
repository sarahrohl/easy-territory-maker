<?php

if (!file_exists('bower_components')) {
    throw new Exception("It looks like you have not setup 'bower'.  Please set it up first, and continue.");
}

//include and instantiate EasyTerritoryMaker
include_once('lib/EasyTerritoryMaker.php');
$etm = new EasyTerritoryMaker();
$li = '';
$index = 0;

//write string from etm->all()
foreach($etm->all() as $locality) {
    $localityName = $locality->name;
    $localityNameEncoded = urlencode($locality->name);


    //If has a placemark, it is a Locality (ie Folder), so list it's Placemarks (IE Territories)
    if (!empty($locality->Placemark)) {
        foreach($locality as $territory) {
            if (!empty($territory->name)) {
                $territoryName = $territory->name;

                $territoryNameEncoded = urlencode($territory->name);

                $li .= "<li id='territory$index' class='territory' data-index='$index'>
                            <a href='viewTerritory.php?territory=$territoryNameEncoded&locality=$localityNameEncoded&index=$index'>$territoryName - $localityName</a>
                        </li>";
                $index++;
            }
        }
    }

    //Otherwise it is a Placemark (ie Territory)
    else {
        $li .= "<li id='territory$index' class='territory' data-index='$index'><a href='viewTerritory.php?territory=$localityNameEncoded&index=$index'>$localityName</a></li>";
        $index++;
    }
}
$territoryList = "<div><ul>$li</ul></div>";
?><!DOCTYPE html>
<html>
<head>
	<title>Easy Territory Maker</title>
    <script src="bower_components/jquery/dist/jquery.js"></script>
    <script src="bower_components/jquery-ui/ui/jquery-ui.js"></script>
    <script src="bower_components/jquery-ui/ui/i18n/jquery-ui-i18n.js"></script>
    <link href="bower_components/jquery-ui/themes/smoothness/jquery-ui.css" type="text/css" rel="Stylesheet" />
</head>
<body>
    <?php echo $territoryList;?>
</body>
</html>