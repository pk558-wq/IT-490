<?php

require_once __DIR__ . '/vendor/autoload.php';
include_once 'config.php';
include_once 'checkpass.php';
include_once 'create_tables.php';
include_once 'get_products_from_db.php';
include_once 'get_faers_from_db.php';
include_once 'get_recalls_from_db.php';
include_once 'get_user_products_from_db.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection(RABBITMQ_HOST, RABBITMQ_PORT, RABBITMQ_USER, RABBITMQ_PASSWORD);
$channel = $connection->channel();

$channel->queue_declare('rpc_queue', false, false, false, false);

echo " [x] Awaiting RPC requests\n";

$callback = function ($req) {
    echo ' [x] Received ', $req->body, "\n";
    $data = json_decode($req->body, true);
    $response = array('code' => '1', 'status' => 'failed');

    switch($data['type']) {
            case "login":
                echo "login requested \n";
		$response = check_password($data['username'], $data['password']);
		print_r($response);
		break;
            case "add_user":
	        echo "add_user requested \n";
		$response = create_user($data['username'], $data['password'], $data['email']);
		print_r($response);
                break;
            case "add_user_ndc":
	        echo "add_user requested \n";
		$response = create_user_ndc($data['username'], $data['product_ndc']);
		print_r($response);
                break;
            case "remove_user_ndc":
	        echo "remove_user_ndc requested \n";
		$response = delete_user_ndc($data['username'], $data['product_ndc']);
		print_r($response);
                break;
            case "get_products":
		$response = get_products_from_db($data['search'], $data['page'], $data['page_size']);
                break;
            case "get_user_products":
		$response = get_user_products_from_db($data['username'], $data['page'], $data['page_size']);
                break;
            case "get_faers":
		$response = get_product_faers_from_db($data['product_ndc'], $data['page'], $data['page_size']);
                break;
            case "get_recalls":
		$response = get_product_recalls_from_db($data['product_ndc'], $data['page'], $data['page_size']);
                break;
            case "get_product_details":
		$response = get_product_details_from_db($data['product_ndc']);
                break;
	    default:
		$response['message'] = "unknown type";
    }

     $msg = new AMQPMessage(
        json_encode($response),
        array('correlation_id' => $req->get('correlation_id'))
     );

     $req->delivery_info['channel']->basic_publish(
        $msg,
        '',
        $req->get('reply_to')
     );
     $req->ack();
};

$channel->basic_qos(null, 1, null);
$channel->basic_consume('rpc_queue', '', false, false, false, false, $callback);

while ($channel->is_open()) {
    $channel->wait();
}

$channel->close();
$connection->close();
?>
