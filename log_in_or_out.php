<?php 
if (isset($_POST['action'])) {
	$errorMsgs = array();
	$successMsgs = array();
	$data = new stdClass();
	require_once __DIR__."/db_connect.php";
	if (empty($errorMsgs)) {
		if ($_POST['action'] == "login") {
			$username = mysqli_real_escape_string ($conn, $_POST['username']);
			$password = mysqli_real_escape_string ($conn, $_POST['password']);
			$type = mysqli_real_escape_string ($conn, $_POST['type']);
			/*if (!$username) {
				array_push($errorMsgs, "Username cannot be empty");
			}
			if (!$password) {
				array_push($errorMsgs, "Password cannot be empty");
			}*/
			//read database here
			$str = "SELECT * FROM {$type}_credentials WHERE username='{$username}'";
			$result = $conn->query($str);
			if ($result->num_rows == 0) {
				array_push($errorMsgs, "Username doesn't exists");
			} else if ($result->num_rows == 1) {
				//username exists
				$row = $result->fetch_assoc();
				$storedPassword = $row['password'];
				if (strcmp($storedPassword, $password) != 0) {
					array_push($errorMsgs, "Wrong Password");
				} else {
					//login successful
					session_start();
					$_SESSION['username'] = $username;
					$_SESSION['type'] = $type;
					$data->username = $_SESSION['username'];
					$data->type = $_SESSION['type'];
					array_push($successMsgs, "you are logged in as {$type} {$username}");
				}
			} else {
				array_push($errorMsgs, "multiple accounts exist with this username. please register with another account");
			}
		} else if ($_POST['action'] == "logout") {
			session_start();
			if (isset($_SESSION['username'])) {
				unset($_SESSION['username']);
				unset($_SESSION['type']);
				array_push($successMsgs, "Successfully logged out");
			} else {
				array_push($errorMsgs, "You are not currently logged in");
			}
		}
	}
	$conn->close();
	$response = array('errorMsgs' => $errorMsgs, 'successMsgs' => $successMsgs, 'data' => $data);
	echo json_encode($response);
}

?>