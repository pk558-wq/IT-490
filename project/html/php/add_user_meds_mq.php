<?php

include_once 'session_util.php';
include_once 'rpc_client.php';
include_once 'config.php';

validate_session();

function add_user_meds($username, $product_ndc) {
   $rpc_client = new SampleRpcClient();
   $payload = array('type'=>'add_user_ndc','username'=>$username,'product_ndc'=>$product_ndc);
   $response = $rpc_client->call($payload);
   return $response;
}

$mq_response = add_user_meds($_SESSION['user'], $_REQUEST['product_ndc']);
redirect("./get_user_products_mq.php");
?>
