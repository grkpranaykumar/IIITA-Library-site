<?php 
if (isset($_POST['action'])) {
	$errorMsgs = array();
	$successMsgs = array();
	$data = new stdClass();
	require_once __DIR__."/db_connect.php";
	$id = mysqli_real_escape_string ($conn, $_POST['id']);
	if (empty($errorMsgs)) {
		session_start();
		if (isset($_SESSION['username']) && $_SESSION['type'] == "admin") {
			if ($_POST['group'] == "user" || $_POST['group'] == "staff") {
				if ($_POST['action'] == "add") {
					$username = mysqli_real_escape_string ($conn, $_POST['username']);
					$password = mysqli_real_escape_string ($conn, $_POST['password']);
					$name = mysqli_real_escape_string ($conn, $_POST['name']);
					$address = mysqli_real_escape_string ($conn, $_POST['address']);
					/*//use in  future if query for adding to user and staff table vary too much
					if ($_POST['group'] == "staff") {
						//insert into staff table
					} else if ($_POST['group'] == "user") {
						//inserting into user table
					}*/
					//inserting into respective tables
					$details = "INSERT INTO `{$_POST['group']}_details`(`id`, `name`, `address`) VALUES ('{$id}', '{$name}', '{$address}')";
					$credentials = "INSERT INTO `{$_POST['group']}_credentials`(`id`, `username`, `password`) VALUES ('{$id}', '{$username}', '{$password}')";

					if ($conn->query($details) && $conn->query($credentials)) {
						array_push($successMsgs, "{$_POST['group']} successfully added");
					} else {
						array_push($errorMsgs, "Could not insert {$_POST['group']} into database");
						//remove half added rows
						$details = "DELETE FROM `{$_POST['group']}_details` WHERE id='{$id}'";
						$credentials = "DELETE FROM `{$_POST['group']}_credentials` WHERE id='{$id}'";
						$conn->query($details);
						$conn->query($credentials);
					}
				} else if ($_POST['action'] == "remove") {
					$credentials = "DELETE FROM `{$_POST['group']}_credentials` WHERE id='{$id}'";
					$details = "DELETE FROM `{$_POST['group']}_details` WHERE id='{$id}'";
					
					if ($conn->query($credentials) && $conn->query($details)) {
						array_push($successMsgs, "{$_POST['group']} successfully removed");
					} else {
						array_push($errorMsgs, "Failed to remove {$_POST['group']}");
					}
				}
			}
		} else {
			array_push($errorMsgs, "Please log in as admin");
		}		
	}
	$conn->close();
	$response = array('errorMsgs' => $errorMsgs, 'successMsgs' => $successMsgs, 'data' => $data);
	echo json_encode($response);
}
?>
