<?php
include_once 'config.php';
include_once 'db_util.php';
include_once 'send_email.php';

function get_user_and_email($conn) {
	$sql = 'SELECT username,email FROM users';
	$result = $conn->query($sql);
	$results = $result->fetch_all(MYSQLI_ASSOC);
	return $results;
}

function get_medications($conn, $username) {
	$sql = "select product_ndc from users_ndc where USERNAME='$username'";
	$result = $conn->query($sql);
	$results = $result->fetch_all(MYSQLI_ASSOC);
	return $results;
}

function get_product_string($conn, $product_ndc) {
	$sql = "select product_ndc, generic_name,labeler_name,brand_name from products where product_ndc='$product_ndc' LIMIT 1";
	$result = $conn->query($sql);
	$row = mysqli_fetch_assoc($result);
	$title = "Product : " . $row['product_ndc'] . " Generic Name " . $row['generic_name'];
	return $title;
}

function get_faers_message($conn, $product_ndc) {
	$sql = "SELECT f.safetyreportid as safetyreportid, f.receivedate as receivedate, f.reactions as reactions, f.occurcountry as occurcountry, f.companynumb as companynumb FROM faers f INNER JOIN product_faers pf on pf.safetyreportid = f.safetyreportid WHERE pf.product_ndc = '$product_ndc' ORDER BY f.receivedate DESC";
	$message = "";

	$result = $conn->query($sql);
	while( $row = mysqli_fetch_assoc($result)) {
	   $message = $message . "Safety Report ID : " . $row['safetyreportid'] . "<br/>";
	   $message = $message . "Received On " . $row['receivedate'] . "<br/>";
	   $message = $message . "Reported from " . $row['occurcountry'] . "<br/>";
	   $message = $message . "Reactions " . $row['reactions'] . "<br/>";
	   $message = $message . "------------------------- <br/>";
	}

	return $message;
}

function get_recall_message($conn, $product_ndc) {
	$sql = "SELECT r.recall_number as recall_number, r.report_date as report_date, r.reason_for_recall as reason_for_recall, r.recalling_firm as recalling_firm, r.status as status FROM recalls r INNER JOIN product_recalls pr on pr.recall_number = r.recall_number WHERE pr.product_ndc = '$product_ndc' ORDER BY r.report_date DESC";
	$message = "";

	$result = $conn->query($sql);
	while( $row = mysqli_fetch_assoc($result)) {
	   $message = $message . "Recall number : " . $row['recall_number'] . "<br/>";
	   $message = $message . "Report Date :" . $row['report_date'] . "<br/>";
	   $message = $message . "Reason for Recall: " . $row['reason_for_recall'] . "<br/>";
	   $message = $message . "Recalling firm: " . $row['recalling_firm'] . "<br/>";
	   $message = $message . "Status: " . $row['status'] . "<br/>";
	   $message = $message . "------------------------- <br/>";
	}

	return $message;
}

function send_alert_emails() {
	$conn = connect_to_db();

        $users = get_user_and_email($conn);
	foreach ($users as $user) {
		$products = get_medications($conn, $user['username']);
		foreach ($products as $product) {
                   $subject = get_product_string($conn, $product['product_ndc']);
		   $faers_message = get_faers_message($conn, $product['product_ndc']);
		   $recall_message = get_recall_message($conn, $product['product_ndc']);
		   if (strlen($faers_message) > 0 || strlen($recall_message) > 0) {
                       send_email($user['username'], $user['email'], $subject, $faers_message.$recall_message, true);
		   }
		}
	}
	close_db($conn);
}

send_alert_emails();

?>
