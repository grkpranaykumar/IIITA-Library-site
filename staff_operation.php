<?php 
if (isset($_POST['action'])) {
	$errorMsgs = array();
	$successMsgs = array();
	$data = new stdClass();
	$rows = array();
	require_once __DIR__."/db_connect.php";
	if (empty($errorMsgs)) {
		session_start();
		if (isset($_SESSION['username']) && $_SESSION['type'] == "staff") {
			if ($_POST['action'] == "add") {
				$id = mysqli_real_escape_string ($conn, $_POST['id']);
				$isbn = mysqli_real_escape_string ($conn, $_POST['isbn']);
				$title = mysqli_real_escape_string ($conn, $_POST['title']);
				$authors = $_POST['authors'];
				$publishers = $_POST['publishers'];
				$price = mysqli_real_escape_string ($conn, $_POST['price']);
				$shelf_no = mysqli_real_escape_string ($conn, $_POST['shelf_no']);
				//inserting into respective tables
				$select_book_details = "SELECT * FROM `book_details` WHERE `isbn`='{$isbn}'";
				$result = $conn->query($select_book_details);
				if ($result->num_rows == 0) {
					//book_details
					$book_details = "INSERT INTO `book_details` (`isbn`, `title`, `price`) VALUES ('{$isbn}', '{$title}', '{$price}')";
					$conn->query($book_details);
					//publishers
					foreach ($publishers as $publisher) {
						array_push($successMsgs, $publisher);
						$select = "SELECT * FROM `publishers` WHERE isbn='{$isbn}' AND publisher='{$publisher}'";
						$resultSelect = $conn->query($select);
						if ($resultSelect->num_rows == 0) {
							$str = "INSERT INTO `publishers`(isbn, publisher) VALUES ('{$isbn}', '{$publisher}')";
							$conn->query($str);
						}
						
					}
					//authors
					foreach ($authors as $author) {
						$select = "SELECT * FROM `authors` WHERE isbn='{$isbn}' AND author='{$author}'";
						$resultSelect = $conn->query($select);
						if ($resultSelect->num_rows == 0) {
							$str = "INSERT INTO `authors`(isbn, author) VALUES ('{$isbn}', '{$author}')";
							$conn->query($str);
						}
					}
				}
				$books_list = "INSERT INTO `books_list` (`id`, `isbn`, `shelf_no`, `available`) VALUES ('{$id}', '{$isbn}', '{$shelf_no}', 1)";
				if ($conn->query($books_list)) {
					array_push($successMsgs, "Book successfully added");
				} else {
					array_push($errorMsgs, "Could not add book into database");
				}
				
			} else if ($_POST['action'] == "remove") {
				$id = mysqli_real_escape_string ($conn, $_POST['id']);
				//getting isbn of book to be removed
				$select_books_list = "SELECT isbn FROM `books_list` WHERE `id`='{$id}'";
				$result = $conn->query($select_books_list);
				if ($result->num_rows == 0) {
					array_push($errorMsgs, "The requested book id doesn't exist in database");
				} else {
					$row = $result->fetch_assoc();
					$isbn = $row['isbn'];
					//removing from books list
					$delete_books_list = "DELETE FROM `books_list` WHERE id='{$id}'";
					$conn->query($delete_books_list);
					//checking if any other books with same isbn still exist in books list
					$select_books_list = "SELECT * FROM `books_list` WHERE `isbn`='{$isbn}'";
					$result = $conn->query($select_books_list);
					if ($result->num_rows == 0) {
						//if doesn't exist then removing book from book details
						$delete_book_details = "DELETE FROM `book_details` WHERE isbn='{$isbn}'";
						$conn->query($delete_book_details);
						//from publishers
						$str = "DELETE FROM `publishers` WHERE isbn='{$isbn}'";
						$conn->query($str);
						//from authors
						$str = "DELETE FROM `authors` WHERE isbn='{$isbn}'";
						$conn->query($str);
					}
					array_push($successMsgs, "Book successfully removed");
				}
			} else if ($_POST['action'] == "view") {
				//getting data from requested_books table
				$str = "SELECT * FROM requested_books";
				$result = $conn->query($str);

				if ($result->num_rows == 0) {
					array_push($errorMsgs, "There are no requested books presently");
				} else {
					while ($row = $result->fetch_object())
						array_push($rows, /*json_encode(*/$row/*)*/);
					$data->rows = $rows;
				}
			} else if ($_POST['action'] == "issue") {
				$user_id = mysqli_real_escape_string ($conn, $_POST['user_id']);
				$isbn = mysqli_real_escape_string ($conn, $_POST['isbn']);
				//checking if atleast 1 book of given isbn is available
				$str = "SELECT id FROM `books_list` WHERE isbn='{$isbn}' AND available=1";
				$result = $conn->query($str);
				if ($result->num_rows == 0) {
					array_push($errorMsgs, "Books with requested isbn are not available presently");
				} else {
					$row = $result->fetch_object();
					$book_id = $row->id;
					//inserting into issued_books(user_id, book_id), removing from requested_books(user_id, isbn) and update availability of that book_id in books_list
					$select = "SELECT * FROM `requested_books` WHERE user_id='{$user_id}' AND isbn='{$isbn}'";
					$result = $conn->query($select);
					if ($result->num_rows == 0) {
						array_push($errorMsgs, "The book has not been requested");
					} else {
						$row = $result->fetch_assoc();
						$req_date = $row['req_date'];
						$insert = "INSERT INTO `issued_books`(`user_id`, `book_id`, `req_date`, `issue_date`) VALUES ('{$user_id}', '{$book_id}', '{$req_date}', now())";
						$remove = "DELETE FROM `requested_books` WHERE user_id='{$user_id}' AND isbn='{$isbn}'";
						$update = "UPDATE `books_list` SET `available`=0 WHERE id='{$book_id}'";
						if ($conn->query($insert) && $conn->query($remove) && $conn->query($update)) {
							array_push($successMsgs, "Book successfully issued");
						} else {
							array_push($errorMsgs, "Failed to issue the requested book");
						}
						$data->book_id = $book_id;
					}
				}
			} else if ($_POST['action'] == "return") {
				$book_id = mysqli_real_escape_string ($conn, $_POST['book_id']);
				//checking if the given book_id is issued
				$get_user_id = "SELECT `user_id` FROM `issued_books` WHERE book_id='{$book_id}'";
				$result = $conn->query($get_user_id);
				if ($result->num_rows == 0) {
					array_push($errorMsgs, "The submitted book id is not issued presently");
				} else {
					$row = $result->fetch_object();
					$user_id = $row->user_id;
					//removing from issued_books(user_id, book_id), inserting into returned_books(user_id, isbn) and update availability of that book_id in books_list
					
					$select = "SELECT * FROM `issued_books` WHERE user_id='{$user_id}' AND book_id='{$book_id}'";
					$result = $conn->query($select);
					if ($result->num_rows == 0) {
						array_push($errorMsgs, "The book has not been issued");
					} else {
						$row = $result->fetch_assoc();
						$req_date = $row['req_date'];
						$issue_date = $row['issue_date'];
						$insert = "INSERT INTO `returned_books`(`user_id`, `book_id`, `req_date`, `issue_date`, `return_date`) VALUES ('{$user_id}', '{$book_id}', '{$req_date}', '{$issue_date}', now())";
						$remove = "DELETE FROM `issued_books` WHERE user_id='{$user_id}' AND book_id='{$book_id}'";
						$update = "UPDATE `books_list` SET `available`=1 WHERE id='{$book_id}'";
						if ($conn->query($update) && $conn->query($insert) && $conn->query($remove)) {
							array_push($successMsgs, "Book successfully returned");

							require_once __DIR__."/defaults.php";

							$str = "SELECT DATEDIFF(return_date, issue_date) AS DiffDate FROM `returned_books` WHERE user_id='{$user_id}' AND book_id='{$book_id}'";
							$result = $conn->query($str);
							$row = $result->fetch_assoc();
							$days = $row['DiffDate'];
							$totalFine = $finePerDay*($days-$maxNoOfDays);
							if ($totalFine < 0) {
								$data->fine = 0;
							} else {
								$data->fine = $totalFine;
							}
							
						} else {
							array_push($errorMsgs, "Failed to return the submitted book");
						}
					}
					
				}
			}
		} else {
			array_push($errorMsgs, "Please log in as staff");
		}
	}
	$conn->close();
	$response = array('errorMsgs' => $errorMsgs, 'successMsgs' => $successMsgs, 'data' => $data);
	echo json_encode($response);
}
?>