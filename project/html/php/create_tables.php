<?php
include_once 'db_util.php';

function create_users_table($conn) {
   $sql = "CREATE TABLE IF NOT EXISTS users (
           id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
           username VARCHAR(32) NOT NULL,
           password VARCHAR(255) NOT NULL,
	   email    VARCHAR(255) DEFAULT NULL
	  )";
   if ($conn->query($sql) === TRUE) {
      echo "Table Users table created successfully\n";
   } else {
      echo "Error creating user table: " . $conn->error;
   }
}

function create_users_ndc($conn) {
   $sql = "CREATE TABLE IF NOT EXISTS users_ndc (
	   username VARCHAR(32) NOT NULL,
           product_ndc VARCHAR(13) NOT NULL,
           PRIMARY KEY(username, product_ndc)
	  )";
   if ($conn->query($sql) === TRUE) {
      echo "Table Users_ndc table created successfully\n";
   } else {
      echo "Error creating users_ndc table: " . $conn->error;
   }
}

function insert_users_ndc($conn, $username, $product_ndc) {
     $sql = "INSERT INTO users_ndc (username, product_ndc)
	     VALUES ('$username', '$product_ndc')
             ON DUPLICATE KEY UPDATE product_ndc='$product_ndc'";

     if ($conn->query($sql) === TRUE) {
        echo "USERS NDC $product_ndc added successfully\n";
     } else {
        echo "Error inserting USERS NDC $product_ndc : " . $conn->error;
     }
}

function remove_users_ndc($conn, $username, $product_ndc) {
     $sql = "DELETE FROM users_ndc WHERE username='$username' AND product_ndc='$product_ndc'";

     if ($conn->query($sql) === TRUE) {
        echo "USERS NDC $product_ndc deleted successfully\n";
     } else {
        echo "Error deleting USERS NDC $product_ndc : " . $conn->error;
     }
}

function insert_user($conn, $username, $password, $email) {

     $response = array('code' => 1, 'status' => 'failed', 'message' => 'add_user failed');

     $sql = "SELECT * FROM users WHERE username = '$username'";
     $result = $conn->query($sql);
     print_r($result);
     if ($result->num_rows > 0) {
	     $response['message'] = "$username already exists.";
	     return $response;
     }

     $encrypted_password = password_hash($password, PASSWORD_DEFAULT);
     $sql = "INSERT INTO users (username, password, email)
             VALUES ('$username', '$encrypted_password', '$email')";

     print($sql);
     if ($conn->query($sql) === TRUE) {
        echo "User $username added successfully\n";
	$response['message'] = "$username added successfully.";
	$response['code'] = 0;
     } else {
	$response['message'] = "Error creating user $username : " . $conn->error;
        echo "Error creating user $username : " . $conn->error;
     }
     return $response;
}

function initialize_db() {
	$conn = connect_to_db();
	create_users_table($conn);
        create_users_ndc($conn);
	close_db($conn);
}

function create_user($username, $password, $email) {
	$conn = connect_to_db();
        $response = insert_user($conn, $username, $password, $email);
	close_db($conn);
	return $response;
}

function create_user_ndc($username, $product_ndc) {
	$conn = connect_to_db();
        $response = insert_users_ndc($conn, $username, $product_ndc);
	close_db($conn);
	return $response;
}

function delete_user_ndc($username, $product_ndc) {
	$conn = connect_to_db();
        $response = remove_users_ndc($conn, $username, $product_ndc);
	close_db($conn);
	return $response;
}

initialize_db();

?>
