<!DOCTYPE html>
<html>
<head>
    <title>Tabbed Content</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
    <script src="tabcontent.js" type="text/javascript"></script>
    <link href="template1/tabcontent.css" rel="stylesheet" type="text/css" />
</head>
<head>
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


$destinations = array();
$sources = array();
$mydate;
$number = 0;
while ($row = mysql_fetch_assoc($result)) {
	$mydate = $row['DATE_OF_DISEMBARKATION'];
	if (!in_array($row['PORT_OF_DISEMBARKATION'], $destinations)) {
		$destinations[$row['PORT_OF_DISEMBARKATION']] = 1;
	} else {
		$destionations[$row['PORT_OF_DISEMBARKATION']] += 1;
	}
	if (!in_array($row['PORT_OF_EMBARKATION'], $sources)) {
		array_push($sources, $row['PORT_OF_EMBARKATION']);
	}
	$number++;
}
$populardest = "unknown";
$popularity = -1;
foreach ($destinations as $k => $v) {
	if ($v > $popularity) {
		$popularity = $v;
		$populardest = $k;
		}
}
$result = mysql_query("SELECT* FROM mint WHERE `day` = '" . $mydate . "'");
if (!$result) {
    die('Invalid query: ' . mysql_error());
}
$mintemp;
while ($row = mysql_fetch_assoc($result)) {
	$mintemp = $row['temp'];
}
$result = mysql_query("SELECT* FROM maxt WHERE `day` = '" . $mydate . "'");
if (!$result) {
    die('Invalid query: ' . mysql_error());
}
$maxtemp;
while ($row = mysql_fetch_assoc($result)) {
	$maxtemp = $row['temp'];
}
$avg = ($mintemp + $maxtemp) /2;
$colc = "rgb(0,0,0)";
$image = "./weather-clear.png";
if ($avg > 30){ $colc = "rgb(100,0,0)";  $image = "./weather-clear.png";
}
else if ($avg > 20){ $colc = "rgb(200,0,100)"; $image = "./weather-few-clouds.png";
}
else if ($avg > 15){ $colc = "rgb(230,100,230)"; $image = "./weather-overcast.png"; 
}
else if ($avg > 10){ $colc = "rgb(100,0,200)"; $image = "./weather-showers-scattered.png";
}
else { $colc = "rgb(0,0,100)"; $image = "./weather-snow.png";
}

?>
</head>

<body style="background:#F6F9FC;">
    <div style="width: 500px; margin: 0 auto;  font: 0.85em arial;">
        <ul class="tabs" persist="true">
            <li><a href="#" rel="view1">Ship</a></li>
            <li><a href="#" rel="view2">Background</a></li>
            <li><a href="#" rel="view3">Statistics</a></li>
            <li><a href="#" rel="view4">Passenger Log</a></li>
        </ul>
        <div class="tabcontents">
            <div id="view1" class="tabcontent">
                <b> General Information </b>
                <ul>
					<li> Voyage Barcode: <?php echo $_GET["voyage"] ?> </li>
					<li> Source(s): <?php foreach ($sources as &$v) echo($v) . " "; ?> </li>
					<li> Number of Passengers: <?php echo $number; ?>  </li>
				</ul>
            </div>
            <div id="view2" class="tabcontent">
                <b> Background about the Voyage </b>
				<p> Min/Max Temperature on Thier Arrival: </p>
				<p <?php echo  'style="color:' . $colc . ';"'; ?> >  <?php echo $mintemp . " / " . $maxtemp; ?>  </span> <img src="<?php echo $image; ?>"> </img> </p>
            </div>
            <div id="view3" class="tabcontent">
                <b> Statistics about the Voyage </b>
				<p> Most Popular Destination: <?php echo $populardest; ?> </p>
            </div>
            <div id="view4" class="tabcontent">
                <b>Click below to view the Ships Manifesto</b>
                <p> <?php echo '<a href="../passenger_list.php?surname=' . $_GET["surname"] . '&voyage=' . $_GET["voyage"] . '" target="_blank">I want to see!</a>'; ?> </p>                
				<p> Soda Link: </p>
				<p> Manifesto Digitised Image: </p>
			</div>
        </div>
    </div>
</body>
</html>
