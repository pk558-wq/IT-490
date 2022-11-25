<?php
include_once 'db_util.php';

function fetch_user_products_from_db($conn, $username, $page_number, $page_size) {
	$response = array('code' => 1, 'status'=>'failed', 'count' => 0);

	# First get number of rows from DB
	$sql = "SELECT COUNT(*) FROM products p INNER JOIN USERS_NDC u on p.product_ndc = u.product_ndc WHERE u.username = '$username'";

        $count = 0; 
        if ($result = $conn->query($sql)){
            while($row = mysqli_fetch_array($result)) {
                    $count = $row['COUNT(*)'];
	    }
        }

	$offset = $page_size * ($page_number - 1);

        $sql = "SELECT p.product_ndc as product_ndc, p.generic_name as generic_name, p.labeler_name as labeler_name, p.brand_name as brand_name FROM products p INNER JOIN USERS_NDC u on p.product_ndc = u.product_ndc WHERE u.username = '$username' LIMIT $page_size OFFSET $offset";


	if($result = $conn->query($sql)) {
        	$results = $result->fetch_all(MYSQLI_ASSOC);
        	$response['code'] = 0;
        	$response['count'] = $count;
        	$response['status'] = 'success';
        	$response['results'] = $results;
        }
	return $response;
}

function get_user_products_from_db($username, $page, $page_size) {
	$conn = connect_to_db();

	if (!is_null($username)) {
	    $response = fetch_user_products_from_db($conn, $username, $page, $page_size);
	} else {
	   $response = array('code' => 1, 'status'=>'failed', 'count' => 0, 'message' => "missing params");
	}
	close_db($conn);
	return $response;
}

?>
