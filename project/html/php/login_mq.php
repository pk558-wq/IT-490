<?php

include_once 'rpc_client.php';
include_once 'config.php';

function redirect($url) {
    header('Location: '.$url);
    die();
}

function do_login() {
   session_start();
   # $_REQUEST is a superglobal and is available in all functions
   if (!isset($_REQUEST['username']) || !isset($_REQUEST['password']) || !isset($_REQUEST['type'])) {
      redirect("login.html");
   }

   $current_user=$_REQUEST['username'];
   $current_password=$_REQUEST['password'];
   $rpc_client = new SampleRpcClient();
   $payload = array('type'=>'login','username'=>$current_user,'password'=>$current_password);
   print_r($payload);
   $response = $rpc_client->call($payload);
   print_r($response);
   if ($response['code'] == 0) {
      $_SESSION['user'] = $current_user;
      redirect("get_user_products_mq.php");
   } else {
      $_SESSION['Error'] = $response['message'];
      redirect("../login.html");
   }
}

do_login();

?>
