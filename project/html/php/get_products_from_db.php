<?php
include_once 'db_util.php';

function fetch_products_from_db($conn, $search, $page_number, $page_size) {
	$response = array('code' => 1, 'status'=>'failed', 'count' => 0);
	$search = strtolower($search);
	$sql = 'SELECT COUNT(*) FROM products';
	if (empty($search)) {
		$sql = "SELECT COUNT(*) FROM products";
	} else {
		$sql = "SELECT COUNT(*) FROM products WHERE LOWER(products.generic_name) LIKE '%$search%'";
	}

        $result = $conn->query($sql);
        $count = 0; 
        while($row = mysqli_fetch_array($result)) {
                $count = $row['COUNT(*)'];
	}

	$offset = $page_size * ($page_number - 1);

	if (empty($search)) {
		$sql = "SELECT * FROM products LIMIT $page_size OFFSET $offset";
	} else {
		$sql = "SELECT * FROM products WHERE LOWER(products.brand_name) LIKE '%$search%' OR LOWER(products.generic_name) LIKE '%$search%' LIMIT $page_size OFFSET $offset";
	}
	echo "testing\n";
	echo "$count" . "\n";
	echo "$sql " . "\n";

	$result = $conn->query($sql);
	$results = $result->fetch_all(MYSQLI_ASSOC);
	$response['code'] = 0;
	$response['count'] = $count;
	$response['status'] = 'success';
	$response['results'] = $results;

	return $response;
}

function get_products_from_db($search, $page, $page_size) {
	$conn = connect_to_db();

	if (!is_null($search) && !is_null($page) && !is_null($page_size)) {
	    $response = fetch_products_from_db($conn, $search, $page, $page_size);
	} else {
	   $response = array('code' => 1, 'status'=>'failed', 'count' => 0, 'message' => "missing params");
	}
	close_db($conn);
	return $response;
}

function get_product_details_from_db($product_ndc) {
	$conn = connect_to_db();
	if (!is_null($product_ndc)) {
	    $sql = "select product_ndc, generic_name,labeler_name,brand_name from products where product_ndc='$product_ndc' LIMIT 1";
	    $result = $conn->query($sql);
	    $row = mysqli_fetch_assoc($result);
	    $response = array('code' => 0, 'status'=>'success', 'results' => $row);
	} else {
	   $response = array('code' => 1, 'status'=>'failed', 'count' => 0, 'message' => "missing params");
	}
	close_db($conn);
	return $response;
}

?>
