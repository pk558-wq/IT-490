<?php

include_once 'rpc_client.php';
include_once 'config.php';

function redirect($url) {
    header('Location: '.$url);
    die();
}

function do_add_user() {

   # $_REQUEST is a superglobal and is available in all functions
   if (!isset($_REQUEST['username']) || !isset($_REQUEST['password']) || !isset($_REQUEST['email']) || !isset($_REQUEST['type'])) {
      redirect("login.html");
   }

   $current_user=$_REQUEST['username'];
   $current_password=$_REQUEST['password'];
   $current_email=$_REQUEST['email'];
   $rpc_client = new SampleRpcClient();
   $payload = array('type'=>'add_user','username'=>$current_user,'password'=>$current_password, 'email'=>$current_email);
   print_r($payload);
   $response = $rpc_client->call($payload);
   print_r($response);
   if ($response['code'] == 0) {
      redirect("./login.html");
   } else {
      $_SESSION['Error'] = $response['message'];
      redirect("./signup.html");
   }
}

do_add_user();

?>
