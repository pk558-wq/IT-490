<?php
include_once 'db_util.php';

function check_password($request_user, $request_pass) {
   // Create connection
   $conn = connect_to_db();
   $sql = "SELECT password FROM users where username='$request_user'";
   $result = $conn->query($sql);
   $response = array('code' => 1, 'status' => 'failed', 'message' => 'login failed');

   if ($result->num_rows > 0) {
	   while($row = $result->fetch_assoc()) {
              if (password_verify($request_pass, $row["password"])) {
                  $response = array('code' => 0, 'status' => 'ok', 'message' => 'login successful');
              } else {
                  $response['message'] = 'Invalid user or password';
              }
           }
   }
   close_db($conn);
   return $response;
}

?>
