<!-- Basic search engine -->
<!-- Julian Thijssen & Bas Verhaar -->
<!-- 10461329 & 10177914 -->

<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<?php
$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "zoekmachines";

$query = $_GET['query'];
$jaar_facet = $_GET['jaar'];
$partij_facet = $_GET['partij'];

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

$base_sql = "SELECT * FROM kamervragen WHERE MATCH(titel, vraag, antwoord) AGAINST ('" . $query . "')";

// If the year facet is set, also filter on that
if (isset($jaar_facet)) {
	$base_sql .= " AND jaar=" . $jaar_facet;
}

// If the party facet is set, also filter on that
if (isset($partij_facet)) {
	$base_sql .= " AND partij='" . $partij_facet . "'";
}

//echo $base_sql;
// Check connection
if ($conn->connect_error) {
	echo "Not connected to MySQL!";
    die("Connection failed: " . $conn->connect_error);
}

$result = mysqli_query($conn, $base_sql);

// Push all results to an array
$results = array();
if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        array_push ($results, $row);
    }
}

// Generate the year facet data
$jaren = array();
foreach ($results as $row) {
	$jaar = $row["jaar"];
	if (!array_key_exists($jaar, $jaren)) {
		$jaren[$jaar] = 0;
	}
	$jaren[$jaar]++;
}

// Generate the party facet data
$partijen = array();
foreach ($results as $row) {
	$partij = $row["partij"];
	if (!array_key_exists($partij, $partijen)) {
		$partijen[$partij] = 0;
	}
	$partijen[$partij]++;
}
?>

<form action="index.php">
	<input type="submit" value="Reset">
</form>
</br>
<form action="index.php" method="get">
	Zoek: <input type="text" name="query"></br>
	</br>
	<input type="submit" value="Submit">
</form>

<!-- The year facet -->
Jaar:</br>
<?php
	foreach ($jaren as $key => $value) {
		echo sprintf("<li><a href='http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]&jaar=%d'>%d (%d)</a></li>", $key, $key, $value);
	}
?>
</br>
</br>

<!-- The party facet -->
Partij:</br>
<?php
	foreach ($partijen as $key => $value) {
		echo sprintf("<li><a href='http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]&partij=%s'>%s (%d)</a></li>", $key, $key, $value);
	}
?>
</br>
</br>

<!-- The list of results -->
Results:</br>
<ul>
<?php
if (count($results) > 0) {
	foreach ($results as $row) {
		echo "<li><b>Partij:</b> " . $row["partij"] . "</br><b>Jaar:</b> " . $row["jaar"] . "</br><b>Titel:</b> " . $row["titel"] . "</li>";
	}
} else {
	echo "No results found.";
}
?>
</ul>
</body>

</html>

