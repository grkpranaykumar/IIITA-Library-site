<?php
function get_table_name($group){
	switch ($group) {
		case 'user':
			return "user_details";
		case 'staff':
			return "staff_details";
		case 'book':
			return "books_list";
	}
}
function get_column_name($subgroup) {
	switch ($subgroup) {
		case 'name':
			return "name";
		case 'address':
			return "address";
		case 'id':
			return "id";
		case 'title':
			return "title";
		case 'author':
			return "author";
		case 'publisher':
			return "publisher";
		case 'availability':
			return "available";
		case 'isbn':
			return "isbn";
	}
}
function get_rows($table_name, $column_name, $query, $conn) {
	if ($column_name == "id" || $column_name == "available") {
		//books_list left join book_details and condition in books_list
		if ($query == "") {
			$str = "SELECT L.id, D.title, D.isbn, D.price, L.shelf_no, L.available FROM `books_list` L LEFT JOIN `book_details` D ON L.isbn = D.isbn";
		} else {
			$str = "SELECT L.id, D.title, D.isbn, D.price, L.shelf_no, L.available FROM `books_list` L LEFT JOIN `book_details` D ON L.isbn = D.isbn WHERE L.{$column_name}='{$query}'";
		}
	} else if ($column_name == "isbn" || $column_name == "title") {
		//books_list left join book_details and condition in books_details
		if ($query == "") {
			$str = "SELECT L.id, D.title, D.isbn, D.price, L.shelf_no, L.available FROM `books_list` L LEFT JOIN `book_details` D ON L.isbn = D.isbn";
		} else {
			$str = "SELECT L.id, D.title, D.isbn, D.price, L.shelf_no, L.available FROM `books_list` L LEFT JOIN `book_details` D ON L.isbn = D.isbn WHERE D.{$column_name}='{$query}'";
		}
	}
	$result = $conn->query($str);
	$rows = array();
	if ($result->num_rows == 0) {
		return $rows;
	} else {
		while ($row = $result->fetch_object()){
			
			array_push($rows, /*json_encode(*/$row/*)*/);
		}
		foreach ($rows as $row) {
			$isbn = $row->isbn;
			
			$authors = array();
			$str = "SELECT author FROM `authors` WHERE isbn='{$isbn}'";
			$result = $conn->query($str);
			if ($result->num_rows != 0) {
				while($authorRow = $result->fetch_assoc()){
					array_push($authors, $authorRow['author']);
				}
			}
			$publishers = array();
			$str = "SELECT publisher FROM `publishers` WHERE isbn='{$isbn}'";
			$result = $conn->query($str);
			if ($result->num_rows != 0) {
				while($publisherRow = $result->fetch_assoc()){
					array_push($publishers, $publisherRow['publisher']);
				}
			}
			$row->authors = $authors;
			$row->publishers = $publishers;
			
		}
		return $rows;	
	}
}
?>

<?php 
if (isset($_POST['action'])) {
	$errorMsgs = array();
	$successMsgs = array();
	$data = new stdClass();
	$rows = array();
	require_once __DIR__."/db_connect.php";
	if (empty($errorMsgs)) {
		if ($_POST['action'] == "search") {
			$query = mysqli_real_escape_string ($conn, $_POST['query']);
			$group = mysqli_real_escape_string ($conn, $_POST['group']);
			$subgroup = mysqli_real_escape_string ($conn, $_POST['subgroup']);


			$table_name = get_table_name($group);
			$column_name = get_column_name($subgroup);
			//echo $table_name." ".$column_name;
			if ($table_name == "books_list") {
				if (($column_name == "author" || $column_name == "publisher")) {
					if ($query == "") {
						$str = "SELECT isbn FROM {$column_name}s";
					} else {
						$str = "SELECT isbn FROM {$column_name}s WHERE {$column_name}='{$query}'";
					}
					$result = $conn->query($str);
					if ($result->num_rows == 0) {
						array_push($errorMsgs, "The requested list is empty");
					} else {
						$isbn = array();
						while($row = $result->fetch_assoc()){
							array_push($isbn, $row['isbn']);
						}
						$totalRows = array();
						foreach ($isbn as $isbnVal) {
							//isbn
							$isbnRows = get_rows($table_name, "isbn", $isbnVal, $conn);
							//join arrays
							$totalRows = array_merge($totalRows, $isbnRows);
						}
						$data->rows = $totalRows;
						//store joined arrays into data->rows
					}
				} else {
					//resp
					$rows = get_rows($table_name, $column_name, $query, $conn);
					$data->rows = $rows;
					//store into data->rows
				}
			} else {
				//echo "hello";
				if ($query == "") {
					$str = "SELECT * FROM {$table_name}";
				} else {
					$str = "SELECT * FROM {$table_name} WHERE {$column_name}='{$query}'";
				}
				//echo $str;
				$result = $conn->query($str);
				if ($result->num_rows == 0) {
					array_push($errorMsgs, "The requested list is empty");
				} else {
					while ($row = $result->fetch_object())
						array_push($rows, /*json_encode(*/$row/*)*/);
					$data->rows = $rows;
				}
			}


		} else if ($_POST['action'] == "search_by_id") {
			$query = mysqli_real_escape_string ($conn, $_POST['query']);
			$group = mysqli_real_escape_string ($conn, $_POST['group']);
			$subgroup = "id";


			$table_name = get_table_name($group);
			$column_name = get_column_name($subgroup);
			//echo $table_name." ".$column_name;
			if ($table_name == "books_list") {
				$rows = get_rows($table_name, $column_name, $query, $conn);
				$data->rows = $rows;
			} else {
				//echo "hello";
				if ($query == "") {
					$str = "SELECT * FROM {$table_name}";
				} else {
					$str = "SELECT * FROM {$table_name} WHERE {$column_name}='{$query}'";
				}
				//echo $str;
				$result = $conn->query($str);
				if ($result->num_rows == 0) {
					array_push($errorMsgs, "The requested list is empty");
				} else {
					while ($row = $result->fetch_object())
						array_push($rows, /*json_encode(*/$row/*)*/);
					$data->rows = $rows;
				}
			}


		}
	}
	$conn->close();
	$response = array('errorMsgs' => $errorMsgs, 'successMsgs' => $successMsgs, 'data' => $data);
	echo json_encode($response);
}
?>