<?php
include_once 'db_util.php';

function create_users_table($conn) {
   $sql = "CREATE TABLE users (
           id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
           username VARCHAR(32) NOT NULL,
           password VARCHAR(255) NOT NULL,
	   email    VARCHAR(255) DEFAULT NULL
	  )";
   if ($conn->query($sql) === TRUE) {
      echo "Table Users table created successfully";
   } else {
      echo "Error creating user table: " . $conn->error;
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
        echo "User $username added successfully";
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
        insert_user($conn, "user1", "user1pass", "user1@example.com");
	close_db($conn);
}

function create_user($username, $password, $email) {
	$conn = connect_to_db();
        $response = insert_user($conn, $username, $password, $email);
	close_db($conn);
	return $response;
}


?>
