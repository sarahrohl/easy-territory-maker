<?php

if ($_REQUEST['territory']) {
	require_once('viewTerritory.php');
	exit;
}
?><!DOCTYPE html>
<html>
<head>

</head>
<body>
	<form>
		<label>Territory</label><input type="text" name="territory"/><input type="submit" value="Go"/>
	</form>
</body>
</html>