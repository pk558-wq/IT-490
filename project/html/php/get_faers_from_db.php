<?php
include_once 'db_util.php';

function fetch_faers_from_db($conn, $product_ndc, $page_number, $page_size) {
	$response = array('code' => 1, 'status'=>'failed', 'count' => 0);

        # First get number of rows from DB
        $sql = "SELECT COUNT(*) FROM faers f INNER JOIN product_faers pf on pf.safetyreportid = f.safetyreportid WHERE pf.product_ndc = '$product_ndc' ORDER BY f.receivedate DESC";

        $result = $conn->query($sql);
        $count = 0; 
        while($row = mysqli_fetch_array($result)) {
                $count = $row['COUNT(*)'];
	}

	$offset = $page_size * ($page_number - 1);

        $sql = "SELECT f.safetyreportid as safetyreportid, f.receivedate as receivedate, f.reactions as reactions, f.occurcountry as occurcountry, f.companynumb as companynumb FROM faers f INNER JOIN product_faers pf on pf.safetyreportid = f.safetyreportid WHERE pf.product_ndc = '$product_ndc' ORDER BY f.receivedate DESC LIMIT $page_size OFFSET $offset";


	$result = $conn->query($sql);
	$results = $result->fetch_all(MYSQLI_ASSOC);
	$response['code'] = 0;
	$response['count'] = $count;
	$response['status'] = 'success';
	$response['results'] = $results;

	return $response;
}

function get_product_faers_from_db($product_ndc, $page, $page_size) {
	$conn = connect_to_db();

	if (!is_null($product_ndc)) {
	    $response = fetch_faers_from_db($conn, $product_ndc, $page, $page_size);
	} else {
	   $response = array('code' => 1, 'status'=>'failed', 'count' => 0, 'message' => "missing params");
	}
	close_db($conn);
	return $response;
}

?>
