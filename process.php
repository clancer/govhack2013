
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
if ($name != "") $name = " AND `SURNAME` = '" . $name . "'";


$toyear = $_GET["toyear"];
if ($toyear == "") $toyear = "1949";

$fromyear = $_GET["fromyear"];
if ($fromyear != "") $fromyear = " AND `DATE_OF_DISEMBARKATION` BETWEEN '" . $fromyear . "-01-01' AND '" . $toyear . "-12-30'";



$ship = $_GET["ship"];
if ($ship != "") $ship = " AND `SHIP_AIRCRAFT_NAME` = '" . $ship . "'";

$voyage = $_GET["voyage"];
if ($voyage != "") $voyage = " AND `ITE_BARCODE_NO` = '" . $voyage. "'";

// fix from year
$result = mysql_query('SELECT* FROM passengers_arrival WHERE 1' . $name . $voyage . $ship . $fromyear . ' GROUP BY `ITE_BARCODE_NO`');
if (!$result) {
    die('Invalid query: ' . mysql_error());
}


$voyages = array();
$stack = array();
//$aux = array();
while ($row = mysql_fetch_assoc($result)) {
	// generate statistics output json
	if (!in_array($row['ITE_BARCODE_NO'], $voyages) ) {
		array_push($voyages, $row['ITE_BARCODE_NO']);
		$pieces = explode("-", $row['DATE_OF_DISEMBARKATION']);
		$mydate = $pieces[0] . "," . $pieces[1] . "," . $pieces[2];
		array_push($stack, array("startDate" => $mydate,
				"endDate" => $mydate,
                "headline" => $row['SHIP_AIRCRAFT_NAME'],
                "text" => "<h3 id='loda" . count($voyages) . "'>Voyage loading. Please wait...</h3><iframe onload='hiPo(". "\"" . count($voyages) . "\"" .")' frameborder='0' width='600' height='400' src='http://localhost/tab/demo.php?surname=" . $_GET["surname"] . "&voyage=" . $row['ITE_BARCODE_NO'] . "'>Loading voyage data...</iframe>"

		));
		
	}

	if (count($voyages) > 98) break;

}
$c = array('timeline' => array("headline" => "Freemantle Arrivals Timeline", 
		"type" => "default",
		"text" => "Lists the ship names along the timeline as they arrived",
		"startDate" => $_GET["fromyear"] . "-01-01",
		"date" => $stack));



$fp = fopen('results.json', 'w');
fwrite($fp, json_encode($c));
fclose($fp);
header( 'Location: http://localhost/example_json.html' ) ;

?> 
