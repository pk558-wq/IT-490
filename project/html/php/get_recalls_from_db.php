<?php
include_once 'db_util.php';

function fetch_recalls_from_db($conn, $product_ndc, $page_number, $page_size) {
	$response = array('code' => 1, 'status'=>'failed', 'count' => 0);

        # First get number of rows from DB
        $sql = "SELECT COUNT(*) FROM recalls r INNER JOIN product_recalls pr on pr.recall_number = r.recall_number WHERE pr.product_ndc = '$product_ndc' ORDER BY r.report_date DESC";

        $result = $conn->query($sql);
        $count = 0; 
        while($row = mysqli_fetch_array($result)) {
                $count = $row['COUNT(*)'];
	}

	$offset = $page_size * ($page_number - 1);

        $sql = "SELECT r.recall_number as recall_number, r.report_date as report_date, r.reason_for_recall as reason_for_recall, r.recalling_firm as recalling_firm, r.status as status FROM recalls r INNER JOIN product_recalls pr on pr.recall_number = r.recall_number WHERE pr.product_ndc = '$product_ndc' ORDER BY r.report_date DESC LIMIT $page_size OFFSET $offset";


	$result = $conn->query($sql);
	$results = $result->fetch_all(MYSQLI_ASSOC);
	$response['code'] = 0;
	$response['count'] = $count;
	$response['status'] = 'success';
	$response['results'] = $results;

	return $response;
}

function get_product_recalls_from_db($product_ndc, $page, $page_size) {
	$conn = connect_to_db();

	if (!is_null($product_ndc)) {
	    $response = fetch_recalls_from_db($conn, $product_ndc, $page, $page_size);
	} else {
	   $response = array('code' => 1, 'status'=>'failed', 'count' => 0, 'message' => "missing params");
	}
	close_db($conn);
	return $response;
}

?>
