<?php 
if (isset($_POST['request'])) {
	$errorMsgs = array();
	$successMsgs = array();
	$data = new stdClass();
	$rows = array();
	require_once __DIR__."/db_connect.php";
	if (empty($errorMsgs) && $_POST['request'] == "book") {
		$isbn = mysqli_real_escape_string ($conn, $_POST['isbn']);
		//checking if isbn is valid
		$str = "SELECT * FROM book_details WHERE isbn='{$isbn}'";
		$result = $conn->query($str);
		if ($result->num_rows == 0) {
			array_push($errorMsgs, "You entered a wrong isbn or entered isbn is not available");
		} else {
			//checking if user is logged in
			session_start();
			if (isset($_SESSION['username'])) {
				$username = $_SESSION['username'];
				//checking if user is logged in as user
				if ($_SESSION['type'] == "user") {
					//getting userid of that username
					$get_user_id = "SELECT `id` FROM `user_credentials` WHERE username='{$username}'";
					$result = $conn->query($get_user_id);
					//print_r($result);
					$details = $result->fetch_assoc();
					$user_id = $details['id'];
					//checking if user_id and isbn combination exists in requested books
					$check_exists = "SELECT * FROM requested_books WHERE user_id='{$user_id}' AND isbn='{$isbn}'";
					$result = $conn->query($check_exists);
					if ($result->num_rows == 0) {
						//checking if user_id and isbn combination exists in issued books
						$check_exists = "SELECT I.user_id, BL.isbn FROM `issued_books` I, `books_list` BL WHERE BL.id=I.book_id AND I.user_id='{$user_id}' AND BL.isbn='{$isbn}'";
						$result = $conn->query($check_exists);
						if ($result->num_rows == 0) {
							//checking if user max issue/request limit has been reached
							$check_exists = "SELECT * FROM requested_books WHERE user_id='{$user_id}'";
							$result = $conn->query($check_exists);
							$requested_no = $result->num_rows;

							$check_exists = "SELECT * FROM issued_books WHERE user_id='{$user_id}'";
							$result = $conn->query($check_exists);
							$issued_no = $result->num_rows;

							require_once __DIR__."/defaults.php";
														
							if ($requested_no+$issued_no > $max_no_of_books) {
								array_push($errorMsgs, "You reached your maximum book issue or request limit");
							} else {
								//adding request(user_id, isbn) to rerquested_books table
								$add_request = "INSERT INTO `requested_books`(`user_id`, `isbn`, `req_date`) VALUES ('{$user_id}', '{$isbn}', now())";
								if ($conn->query($add_request)) {
									array_push($successMsgs, "Book successfully requested");
								} else {
									array_push($errorMsgs, "Could not request an issue of the specified book");
								}
							}

						} else {
							array_push($errorMsgs, "You already issued this book");
						}
					} else {
						array_push($errorMsgs, "You already requested for this book");
					}
				} else {
					array_push($errorMsgs, "Please log in as a user");
				}
				
			} else {
				array_push($errorMsgs, "You are not logged in to request books");
			}
		}
	}
	$conn->close();
	$response = array('errorMsgs' => $errorMsgs, 'successMsgs' => $successMsgs, 'data' => $data);
	echo json_encode($response);
}
?>