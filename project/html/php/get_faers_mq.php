<html>   
  <head>   
    <title>Adverse Reactions</title> 
   <link rel="stylesheet" href="./style.css"></link>
  </head>
  <body>

<?php

include_once 'session_util.php';
include_once 'rpc_client.php';
include_once 'config.php';

validate_session();
$page_size = 25;
$product_ndc=$_REQUEST['product_ndc'];
$page = 1;

if (isset($_REQUEST["page"])) {    
     $page  = $_REQUEST["page"];    
}

if (isset($_REQUEST["search"])) {    
     $search  = $_REQUEST["search"];    
}

function prod_details() {
   $product_ndc=$_REQUEST['product_ndc'];
   $rpc_client = new SampleRpcClient();
   $payload = array('type'=>'get_product_details','product_ndc'=>$product_ndc);
   $response = $rpc_client->call($payload);
   return $response['results'];
}

function do_get_faers($page, $page_size) {
   $product_ndc=$_REQUEST['product_ndc'];
   $rpc_client = new SampleRpcClient();
   $payload = array('type'=>'get_faers','product_ndc'=>$product_ndc,'page'=>$page, 'page_size'=>$page_size);
   $response = $rpc_client->call($payload);
   return $response;
}

$med_details = prod_details();
$mq_response = do_get_faers($page, $page_size);
?>

		<ul>
		  <li>	<a href="./get_products.php">Products</a> </li>
	          <li> 	<a href="./get_user_products_mq.php">My Medications</a> <li>
	          <li>	<a href="./logout.php">Logout</a> </li>
		</ul>
  <center>
    <div class="container">
      <br>
      <div>
	<h1> FAERS Information </h1>
<?php
         
         echo "<h2> Product NDC: ". $med_details['product_ndc'] ."</h2>";
         echo "<h2> Generic Name: ". $med_details['generic_name'] ."</h2>";
         echo "<h2> Brand Name: ". $med_details['brand_name'] ."</h2>";
         echo "<h2> Labeler Name: ". $med_details['labeler_name'] ."</h2>";
?>

        <table class="table table-striped table-condensed table-bordered">   
          <thead>
            <tr>
              <th width=10%>Safety Report ID</th>
	      <th width=10%>Received Date</th>
              <th>Reactions</th>
              <th width=5%>Country</th>
            </tr>
          </thead>
          <tbody>
          <?php
            foreach($mq_response['results'] as $row) {
          ?>
            <tr>
             <td><?php echo $row["safetyreportid"]; ?></td>
             <td><?php echo $row["receivedate"]; ?></td>
             <td><?php echo $row["reactions"]; ?></td>
	     <td><?php echo $row["occurcountry"]; ?></td>
            </tr>
          <?php
            };
          ?>
          </tbody>
        </table>

     <div class="pagination">
      <?php
        $total_records = $mq_response['count'];
        echo "<br/>";
        if ($total_records == 0) {
           echo "No adverse events records found <br/>";
	}
        // Number of pages required.   
        $total_pages = ceil($total_records / $page_size);
      
        if ( $page >= 2 ) {
            echo "<a href='get_faers_mq.php?page=" . ($page-1) . "&product_ndc=" . $product_ndc . "'> Prev </a>";
        }

        if ($page < $total_pages) {
            echo "<a href='get_faers_mq.php?page=" . ($page+1) . "&product_ndc=" . $product_ndc . "'> Next </a>";
        }

      ?>
      </div>
    </div>
  </div>
</center>
  </body>
</html>
