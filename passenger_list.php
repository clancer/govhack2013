<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Passenger List</title>
    <meta charset="utf-8">
    <meta name="description" content="TimelineJS example">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
	<link rel="stylesheet" type="text/css" href="./example.css" media="screen" />

  </head>
</html>
<body>


	<ul id="double">

<?php 



$link = mysql_connect('localhost', 'root', '');
if (!$link) {
    die('Not connected : ' . mysql_error());
}

// make foo the current db
$db_selected = mysql_select_db('my_app', $link);
if (!$db_selected) {
    die ('Can\'t use foo : ' . mysql_error());
}



$name = $_GET["surname"];

$voyage = $_GET["voyage"];
if ($voyage != "") $voyage = " AND `ITE_BARCODE_NO` = '" . $voyage . "'";

$result = mysql_query('SELECT* FROM passengers_arrival WHERE 1' . $voyage);
if (!$result) {
    die('Invalid query: ' . mysql_error());
}



while ($row = mysql_fetch_assoc($result)) {
	if ($row['SURNAME'] == $name) {
		echo "<li style='font-weight: bold;'>" . $row['TITLE'] . " " . $row['GIVEN_NAMES'] . " " . $row['SURNAME'] . "</li>";
	} else {
		echo "<li>" . $row['TITLE'] . " " . $row['GIVEN_NAMES'] . " " . $row['SURNAME'] . "</li>";
	}
}


?> 
	</ul>
 
</body>
