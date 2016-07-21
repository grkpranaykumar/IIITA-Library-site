<?php 
$sqlServername = "localhost";
$sqlUsername = "root";
$sqlPassword = "";
$conn = new mysqli($sqlServername, $sqlUsername, $sqlPassword);
$useDB = "USE library";
if ($conn->query($useDB) !== TRUE) {
    array_push($errorMsgs, "Could not connect to database");
} else {
	require_once __DIR__."/create_tables.php";
}
?>