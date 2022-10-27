<?php
include_once 'db_util.php';

function create_products_table($conn) {
   $sql = "CREATE TABLE IF NOT EXISTS products( product_ndc VARCHAR(13) PRIMARY KEY, 
           generic_name VARCHAR(1024) NOT NULL,
           brand_name VARCHAR(255) NOT NULL
	  )";
   if ($conn->query($sql) === TRUE) {
      echo "Product table created successfully \n";
   } else {
      echo "Error creating user table: " . $conn->error . "\n";
   }
}

function create_recalls_table($conn) {
   $sql = "CREATE TABLE IF NOT EXISTS recalls( safetyreportid VARCHAR(13) PRIMARY KEY, 
	   transmissiondate VARCHAR(8) NOT NULL,
            product_ndc VARCHAR(13) NOT NULL
	  )";
   if ($conn->query($sql) === TRUE) {
      echo "Recall table created successfully \n";
   } else {
      echo "Error creating recall table: " . $conn->error . "\n";
   }
}

function insert_recall($conn, $safteyreportid, $transmissiondate, $product_ndc) {
     $sql = "INSERT INTO products (safteyreportid, transmissiondate, product_ndc)
	     VALUES ('$safetyreportid', '$transmissiondate', '$product_ndc')
             ON DUPLICATE KEY UPDATE generic_name='$transmissiondate', product_ndc='$product_ndc'";

     print($sql);
     if ($conn->query($sql) === TRUE) {
        echo "Recall $safteyreportid added successfully \n";
     } else {
        echo "Error creating safetyreportid $safetyreportid : " . $conn->error . "\n";
     }
}

function insert_product($conn, $product_ndc, $generic_name, $brand_name) {
     $sql = "INSERT INTO products (product_ndc, generic_name, brand_name)
	     VALUES ('$product_ndc', '$generic_name', '$brand_name')
             ON DUPLICATE KEY UPDATE generic_name='$generic_name', brand_name='$brand_name'";

     print($sql);
     if ($conn->query($sql) === TRUE) {
        echo "Product $brand_name added successfully \n";
     } else {
        echo "Error creating brand $brand_name : " . $conn->error . "\n";
     }
}

function initialize_db() {
	$conn = connect_to_db();
	create_products_table($conn);
	create_recalls_table($conn);
	close_db($conn);
}

initialize_db();

?>
