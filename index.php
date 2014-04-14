<?php

if (isset($_REQUEST['territory'])) {
	require_once('viewTerritory.php');
	exit;
}
?><!DOCTYPE html>
<html>
<head>
	<title>Valle Vista Territory</title>
</head>
<body>
	<form>
		<label>Territory:</label> <input type="text" name="territory"/> <input type="submit" value="Go"/>
	</form>
</body>
</html>