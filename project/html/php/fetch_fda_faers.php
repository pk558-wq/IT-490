<?php
include_once 'db_util.php';
include_once 'create_fda_tables.php';
include_once "config.php";

function fetch_faers_by_dates($skip, $today, $limit) {
    $endpoint = "https://api.fda.gov/drug/event.json";
    $search_by="receivedate:[". FDA_FAERS_START_DATE . "+TO+". $today. "]+AND+serious:2";

    print($search_by);
    print("\n");
    $params = array('api_key' => FDA_API_KEY, 'search' => $search_by, 'limit'=> $limit);
    if ($skip > 0) {
	    $params['skip'] = $skip;
    }
    $url = $endpoint . '?' . http_build_query($params);
    $url = "https://api.fda.gov/drug/event.json?search=receivedate:[".FDA_FAERS_START_DATE."+TO+".$today."]+AND+serious:2&limit=".$limit."&api_key=".FDA_API_KEY;
    if ($skip > 0) {
	    $url = $url."&skip=".$skip;
    }

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

    list('rc' => $rc, 'data' => $obj) = fetch_faers_by_dates($skip, $today, $limit);
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
	    $patient = $elem['patient'];
	    $drugs = $patient['drug'];
	$reaction_msg = "";
	foreach ($patient['reaction'] as $reaction) {
		$reaction_msg .= $reaction['reactionmeddrapt'];
		$reaction_msg .= " ";
	}

	insert_faers($conn, $elem['safetyreportid'], $elem['companynumb'], $elem['occurcountry'], $elem['receivedate'], $reaction_msg);

	foreach ($drugs as $drug) {
		if (!array_key_exists('openfda', $drug)) {
			continue;
		}
		$openfda = $drug['openfda'];
		if (!array_key_exists('product_ndc', $openfda)) {
			continue;
		}
	        foreach ($openfda['product_ndc'] as $pr_ndc) {
                  insert_product_faers($conn, $pr_ndc, $elem['safetyreportid']);
	        }
	}
    }

    if($current_len < $limit) {
	    break;
    }
    echo "Total records processed " . $skip;
}

close_db($conn);

?>
