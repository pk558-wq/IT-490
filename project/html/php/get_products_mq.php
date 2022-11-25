<html>   
  <head>   
    <title>Products</title> 
   <link rel="stylesheet" href="./style.css"></link>
  </head>
  <body>

<?php

include_once 'session_util.php';
include_once 'rpc_client.php';
include_once 'config.php';

validate_session();
$page_size = 25;
$search = "";
$page = 1;

if (isset($_REQUEST["page"])) {    
     $page  = $_REQUEST["page"];    
}

if (isset($_REQUEST["search"])) {    
     $search  = $_REQUEST["search"];    
}

function do_get_products($page, $page_size) {
   $search=$_REQUEST['search'];
   $rpc_client = new SampleRpcClient();
   $payload = array('type'=>'get_products','search'=>$search,'page'=>$page, 'page_size'=>$page_size);
   $response = $rpc_client->call($payload);
   return $response;
}

$mq_response = do_get_products($page, $page_size);
?>

		<ul>
		  <li>	<a class="active" href="./get_products.php">Products</a> </li>
	          <li> 	<a href="./get_user_products_mq.php">My Medications</a> <li>
	          <li>	<a href="./logout.php">Logout</a> </li>
		</ul>
  <center>
    <div class="container">
      <br>
      <div>
        <h1> Product Information </h1>   
        <table class="table table-striped table-condensed table-bordered">   
          <thead>
            <tr>
	      <th width=10%>Product NDC</th>
              <th width=20%>Labeler</th>
              <th>Generic Name</th>
	      <th>Brand Name</th>
              <th>Add</th>
              <th>Events</th>
              <th>Recalls</th>
            </tr>
          </thead>
          <tbody>
          <?php
            foreach($mq_response['results'] as $row) {
          ?>
            <tr>
             <td><?php echo $row["product_ndc"]; ?></td>
             <td><?php echo $row["labeler_name"]; ?></td>
             <td><?php echo $row["generic_name"]; ?></td>
	     <td><?php echo $row["brand_name"]; ?></td>
	     <td><?php echo "<a href='add_user_meds_mq.php?" . http_build_query($row) . "'> Add </a>"; ?></td>
	     <td><?php echo "<a href='get_faers_mq.php?" . http_build_query(array('product_ndc'=>$row["product_ndc"])) . "'> Events </a>"; ?></td>
	     <td><?php echo "<a href='get_recalls_mq.php?" . http_build_query(array('product_ndc'=>$row["product_ndc"])) . "'> Recalls </a>"; ?></td>
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
           echo "No products found <br/>";
	}
        // Number of pages required.   
        $total_pages = ceil($total_records / $page_size);
      
        if ( $page >= 2 ) {
            echo "<a href='get_products_mq.php?page=" . ($page-1) . "&search=" . $search . "'> Prev </a>";
        }

        if ($page < $total_pages) {
            echo "<a href='get_products_mq.php?page=" . ($page+1) . "&search=" . $search . "'> Next </a>";
        }

      ?>
      </div>
    </div>
  </div>
</center>
  </body>
</html>
