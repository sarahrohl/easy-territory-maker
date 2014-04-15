<?php

if (isset($_REQUEST['territory'])) {
    global $security;
    $security = true;
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
        <table>
            <tr>
                <td>Territory:</td>
                <td><input type="text" name="territory"/></td>
            </tr>
            <tr>
                <td>Your Initials:</td>
                <td><input type="text" name="initials" /></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;">
                    <input type="submit" value="Go"/>
                </td>
            </tr>
        </table>
	</form>
</body>
</html>