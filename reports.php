<?php

	if (!file_exists('bower_components')) {
	    throw new Exception("It looks like you have not setup 'bower'.  Please set it up first, and continue.");
	}


	$key = '';
	if (file_exists('my_files/google.spreadsheet.key')) {
		$key = file_get_contents('my_files/google.spreadsheet.key');
	}

	//include and instantiate EasyTerritoryMaker
	include_once('lib/EasyTerritoryMaker.php');
	$etm = new EasyTerritoryMaker();
	$list = '';
    $territoryAssignmentRecords = '';
    $territoryAssignmentRecordsTick = 0;
    $territoryAssignmentRecordsIndex = 0;
	$index = 0;

	$overview = '';
	//write string from etm->all()
	foreach($etm->all() as $locality) {
	    $localityName = $locality->name;
	    $localityNameEncoded = urlencode($locality->name);

		//The very first map is of all of the territory
		if ($index == 0) {
			$overview = "<span id='overview' class='territory'>
		                <a href='viewTerritories.php?index=$index'>$localityName - Overview</a>
		            </span>";
			$index++;
		}


		//the following maps, are of individual parts of the territory
		else {

		    //If has a placemark, it is a Locality (ie Folder), so list it's Placemarks (IE Territories)
		    if (!empty($locality->Placemark)) {
		        foreach($locality as $territory) {
		            if (!empty($territory->name)) {
		                $territoryName = $territory->name . '';

		                $territoryNameEncoded = urlencode($territory->name);

			            $status = $etm->getSingleStatus($territoryName);

			            $list .= "<tr>
							<td id='territory$index' class='territory' data-index='$index'>
	                            <a href='viewTerritory.php?territory=$territoryNameEncoded&locality=$localityNameEncoded&index=$index'>$territoryName - $localityName</a>
	                        </td>
	                        <td class='center'>$status</td>
	                    </tr>";

		                $index++;

                        //write the territory assignment records
                        if ($territoryAssignmentRecordsTick == 0) {
                            $territoryAssignmentRecordsIndex++;
                            $territoryAssignmentRecordsTick++;
                            $territoryAssignmentRecords .= "<li><a href='viewTerritoryAssignmentRecords.php?at=$index'>Set $territoryAssignmentRecordsIndex</a></li>";
                        } else if ($territoryAssignmentRecordsTick >= 5) {
                            $territoryAssignmentRecordsTick = 0;
                        } else {
                            $territoryAssignmentRecordsTick++;
                        }
		            }
		        }
		    }

		    //Otherwise it is a Placemark (ie Territory)
		    else {
			    $status = $etm->getSingleStatus($localityName . '');

                //write the standard list of territories
			    $list .= "<tr>
					<td id='territory$index' class='territory' data-index='$index'>
			            <a href='viewTerritory.php?territory=$localityNameEncoded&index=$index'>$localityName</a>
		            </td>
	                <td>$status</td>
	            </tr>";

		        $index++;
		    }
		}
	}


	//create priority
	$priority = '';
	foreach($etm->getPriority() as $territory) {
		$publisher = $territory->publisher;
		$territoryName = $territory->territory;
		$date = date("m/d/Y", $territory->in);
		$priority .= "<tr>
					<td class='center'>$territoryName</td>
	                <td class='center'>$date</td>
	            </tr>";
	}


	//create ideal return dates
	$idealReturnDates = '';
	foreach($etm->getIdealReturnDates() as $territory) {
		$publisher = $territory->publisher;
		$territoryName = $territory->territory;
		$date = date("m/d/Y", $territory->idealReturnDate);
		$idealReturnDates .= "<tr>
					<td>$publisher</td>
					<td class='center'>$territoryName</td>
	                <td class='center'>$date</td>
	            </tr>";
	}
?><!DOCTYPE html>
<html>
<head>
	<title>Territories</title>
    <script src="bower_components/jquery/dist/jquery.js"></script>
    <script src="bower_components/jquery-ui/ui/jquery-ui.js"></script>
    <script src="bower_components/jquery-ui/ui/i18n/jquery-ui-i18n.js"></script>
    <link href="bower_components/jquery-ui/themes/smoothness/jquery-ui.css" type="text/css" rel="Stylesheet" />
	<script>
		$(function() {
			$('.ui-button').button();

			$('#tabs').tabs();
		});
	</script>
	<style>
		.center {
			text-align: center;
		}
	</style>
</head>
<body>
	<div id="tabs">
		<ul>
			<li><a href="#list">List</a></li>
			<li><a href="#priority">Need Reworked</a></li>
			<li><a href="#idealReturnDates">Ideal Return Dates</a></li>
			<li><a href="#territoryAssignmentRecords">Territory Assignment Records</a></li>
		</ul>
		<div id="list">
			<table style="min-width: 50%;">
				<tr>
					<th>Territory - <?php echo $overview; ?></th>
					<th>Status</th>
				</tr>
				<?php echo $list;?>
			</table>
		</div>
		<div id="priority">
			<table style="min-width: 50%;">
				<tr>
					<th>Territory</th>
					<th>Last Worked On Date</th>
				</tr>
				<?php echo $priority;?>
			</table>
		</div>
		<div id="idealReturnDates">
			<table style="min-width: 50%;">
				<tr>
					<th>Publisher</th>
					<th>Territory</th>
					<th>Date</th>
				</tr>
				<?php echo $idealReturnDates;?>
			</table>
		</div>
        <div id="territoryAssignmentRecords">
            <ul>
                <?php echo $territoryAssignmentRecords; ?>
            </ul>
        </div>
	</div>
	<div class="ui-button ui-widget" style="position: absolute; right: 25px; top: 18px;">
		<a href="#" onmousedown="window.open('https://docs.google.com/spreadsheets/d/<?php echo $key; ?>');">Open Activity Spreadsheet</a>
	</div>
</body>
</html>