<?php 
//admin
$createAdminDetails = "CREATE TABLE IF NOT EXISTS `admin_details` (
    `id` varchar(20) NOT NULL,
    `name` varchar(50) NOT NULL,
    `address` varchar(255) NOT NULL default '',
    PRIMARY KEY (`id`)
)";
$conn->query($createAdminDetails);
$createAdminCredentials = "CREATE TABLE IF NOT EXISTS `admin_credentials` (
    `id` varchar(20) NOT NULL,
    `username` varchar(30) NOT NULL,
    `password` varchar(30) NOT NULL,
    FOREIGN KEY (`id`) REFERENCES admin_details(`id`),
    PRIMARY KEY (`id`)
)";
$conn->query($createAdminCredentials);

//user
$createUserDetails = "CREATE TABLE IF NOT EXISTS `user_details` (
    `id` varchar(20) NOT NULL,
    `name` varchar(50) NOT NULL,
    `address` varchar(255) NOT NULL default '',
    PRIMARY KEY  (`id`)
)";
$conn->query($createUserDetails);
$createUserCredentials = "CREATE TABLE IF NOT EXISTS `user_credentials` (
    `id` varchar(20) NOT NULL,
    `username` varchar(30) NOT NULL,
    `password` varchar(30) NOT NULL,
    FOREIGN KEY (`id`) REFERENCES user_details(`id`),
    PRIMARY KEY  (`id`)
)";
$conn->query($createUserCredentials);

//staff
$createStaffDetails = "CREATE TABLE IF NOT EXISTS `staff_details` (
    `id` varchar(20) NOT NULL,
    `name` varchar(50) NOT NULL,
    `address` varchar(255) NOT NULL default '',
    PRIMARY KEY  (`id`)
)";
$conn->query($createStaffDetails);
$createStaffCredentials = "CREATE TABLE IF NOT EXISTS `staff_credentials` (
    `id` varchar(20) NOT NULL,
    `username` varchar(30) NOT NULL,
    `password` varchar(30) NOT NULL,
    FOREIGN KEY (`id`) REFERENCES staff_details(`id`),
    PRIMARY KEY  (`id`)
)";
$conn->query($createStaffCredentials);

//books
$createBookDetails = "CREATE TABLE IF NOT EXISTS `book_details` (
    `isbn` varchar(30) NOT NULL,
    `title` varchar(255),
    `price` int(6),
    PRIMARY KEY  (`isbn`)
)";
$conn->query($createBookDetails);

$createBooksList = "CREATE TABLE IF NOT EXISTS `books_list` (
    `id` varchar(10) NOT NULL,
    `isbn` varchar(30) NOT NULL,
    `shelf_no` varchar(10) NOT NULL default '',
    `available` BOOLEAN NOT NULL default TRUE,
    PRIMARY KEY  (`id`),
    FOREIGN KEY (`isbn`) REFERENCES book_details(`isbn`)
)";
$conn->query($createBooksList);
//book_operations(request, borrow, return)
//request
$createBookRequest = "CREATE TABLE IF NOT EXISTS `requested_books` (
    `user_id` varchar(20) NOT NULL,
    `isbn` varchar(30) NOT NULL,
    `req_date` date NOT NULL,
    CONSTRAINT pk_request_id PRIMARY KEY (user_id,isbn),
    FOREIGN KEY (`user_id`) REFERENCES user_details(`id`),
    FOREIGN KEY (`isbn`) REFERENCES book_details(`isbn`)
)";
$conn->query($createBookRequest);
//issue
$createBookIssue = "CREATE TABLE IF NOT EXISTS `issued_books` (
    `user_id` varchar(20) NOT NULL,
    `book_id` varchar(10) NOT NULL,
    `req_date` date NOT NULL,
    `issue_date` date NOT NULL,
    CONSTRAINT pk_issue_id PRIMARY KEY (user_id,book_id),
    FOREIGN KEY (`user_id`) REFERENCES user_details(`id`),
    FOREIGN KEY (`book_id`) REFERENCES books_list(`id`)
)";
$conn->query($createBookIssue);
//return
$createBookReturn = "CREATE TABLE IF NOT EXISTS `returned_books` (
    `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` varchar(20) NOT NULL,
    `book_id` varchar(10) NOT NULL,
    `req_date` date NOT NULL,
    `issue_date` date NOT NULL,
    `return_date` date NOT NULL,
    PRIMARY KEY  (`id`)
)";
$conn->query($createBookReturn);

//publishers list
$createPublishers = "CREATE TABLE IF NOT EXISTS `publishers` (
    `isbn` varchar(30) NOT NULL,
    `publisher` varchar(100) NOT NULL,
    CONSTRAINT pk_publishers PRIMARY KEY (isbn,publisher),
    FOREIGN KEY (`isbn`) REFERENCES book_details(`isbn`)
)";
$conn->query($createPublishers);

//authors list
$createAuthors = "CREATE TABLE IF NOT EXISTS `authors` (
    `isbn` varchar(30) NOT NULL,
    `author` varchar(50) NOT NULL,
    CONSTRAINT pk_authors PRIMARY KEY (isbn,author),
    FOREIGN KEY (`isbn`) REFERENCES book_details(`isbn`)
)";
$conn->query($createAuthors);

?>