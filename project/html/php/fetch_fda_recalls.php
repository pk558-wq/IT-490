<?php
include_once 'db_util.php';
include_once 'create_fda_tables.php';
include_once "config.php";

function fetch_recalls_by_dates($skip, $today, $limit) {
    $endpoint = "https://api.fda.gov/drug/enforcement.json";

    $url = $endpoint."?search=report_date:[".FDA_RECALL_START_DATE."+TO+".$today."]&limit=".$limit."&api_key=".FDA_API_KEY;
    if ($skip > 0) {
	    $url = $url."&skip=".$skip;
    }

    print($url);
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_HEADER, 0);

    // Return response instead of outputting
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute the GET request
    $result = curl_exec($ch);

    if(curl_error($ch)) {
        echo(curl_error($ch));
        $rc = 1;
    } else {
	    $rc = 0;
    }
    curl_close($ch);

    if ($rc == 0) {
	    return ['rc' => $rc, 'data' => json_decode($result, true)];
    }
    return ['rc' => 1, 'data' => null];
}

$skip = 0;
$limit = 200;
$conn = connect_to_db();

$today = date("Ymd");
while(true) {
    echo "Total records processed " . $skip;

    list('rc' => $rc, 'data' => $obj) = fetch_recalls_by_dates($skip, $today, $limit);
    if ($rc != 0) {
	    echo "Sync failed ";
	    return;
    }

    if (!isset($total)) {
       $meta = $obj["meta"];
       $total = $meta['results']['total'];
    }


    if (!array_key_exists("results", $obj))
    {
	    echo "Invalid data returned";
	    print_r($obj);
	    break;
    }

    $objects = $obj['results'];
    $current_len = count($obj['results']);
    $skip = $skip + $current_len;

    foreach($objects as $elem)  {
	if (!array_key_exists('openfda', $elem)) {
		continue;
	}

	$openfda = $elem['openfda'];

	if (!array_key_exists('product_ndc', $openfda)) {
		continue;
	}

        insert_recalls($conn, $elem['recall_number'], $elem['reason_for_recall'], $elem['report_date'], $elem['recalling_firm'], $elem['status']);

        foreach ($openfda['product_ndc'] as $pr_ndc) {
             insert_product_recalls($conn, $pr_ndc, $elem['recall_number']);
        }
    }

    if($current_len < $limit) {
	    break;
    }
    echo "Total records processed " . $skip;
}

close_db($conn);

?>
