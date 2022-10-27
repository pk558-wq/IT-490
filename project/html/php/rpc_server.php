<?php

require_once __DIR__ . '/vendor/autoload.php';
include_once 'config.php';
include_once 'checkpass.php';
include_once 'create_users.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection(RABBITMQ_HOST, RABBITMQ_PORT, RABBITMQ_USER, RABBITMQ_PASSWORD);
$channel = $connection->channel();

$channel->queue_declare('rpc_queue', false, false, false, false);

echo " [x] Awaiting RPC requests\n";

$callback = function ($req) {
    //client sends messages here, appropriate action is done based on type
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
