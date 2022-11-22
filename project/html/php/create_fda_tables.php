<?php
include_once 'db_util.php';

function create_products_table($conn) {
   $sql = "CREATE TABLE IF NOT EXISTS products( product_ndc VARCHAR(13) PRIMARY KEY, 
           generic_name VARCHAR(1024) NOT NULL,
	   brand_name VARCHAR(255) NOT NULL,
           labeler_name VARCHAR(255) NOT NULL
	  )";
   if ($conn->query($sql) === TRUE) {
      echo "Product table created successfully\n";
   } else {
      echo "Error creating user table: " . $conn->error;
   }
}

function create_faers_table($conn) {
   $sql = "CREATE TABLE IF NOT EXISTS faers( safetyreportid VARCHAR(13) PRIMARY KEY, 
	   receivedate DATE NOT NULL,
           reactions VARCHAR(1024),
           companynumb VARCHAR(512),
           occurcountry VARCHAR(32)
	  )";
   if ($conn->query($sql) === TRUE) {
      echo "FAERS table created successfully\n";
   } else {
      echo "Error creating faers table: " . $conn->error;
   }
}

function create_recalls_table($conn) {
   $sql = "CREATE TABLE IF NOT EXISTS recalls( recall_number VARCHAR(32) PRIMARY KEY, 
	   report_date DATE NOT NULL,
           reason_for_recall VARCHAR(1024),
           recalling_firm VARCHAR(512),
           status VARCHAR(32)
	  )";
   if ($conn->query($sql) === TRUE) {
      echo "RECALLS table created successfully\n";
   } else {
      echo "Error creating recalls table: " . $conn->error;
   }
}

function create_product_ndc_recalls_table($conn) {
   $sql = "CREATE TABLE IF NOT EXISTS product_recalls( recall_number VARCHAR(32) NOT NULL, 
	   product_ndc VARCHAR(13) NOT NULL,
           PRIMARY KEY(recall_number, product_ndc)
	  )";
   if ($conn->query($sql) === TRUE) {
      echo "PRODUCT_RECALLS table created successfully\n";
   } else {
      echo "Error creating PRODUCT_RECALLS table: " . $conn->error;
   }
}


function insert_recalls($conn, $recall_number, $reason_for_recall, $report_date, $recalling_firm, $status) {
	$report_date=date("Ymd",strtotime($report_date));
	$reason_for_recall = $conn->real_escape_string($reason_for_recall);
	$recalling_firm = $conn->real_escape_string($recalling_firm);
	if (strlen($reason_for_recall) >= 1024) {
		$reason_for_recall = substr($reason_for_recall, 0, 1023);
	}
     $sql = "INSERT INTO recalls (recall_number, reason_for_recall, report_date, recalling_firm, status)
	     VALUES ('$recall_number', '$reason_for_recall', '$report_date', '$recalling_firm', '$status')
             ON DUPLICATE KEY UPDATE status='$status'";

     print($sql);
     if ($conn->query($sql) === TRUE) {
        echo "RECALLS $recall_number added successfully\n";
     } else {
        echo "Error creating RECALS $recall_number : " . $conn->error;
     }
}

function insert_product_recalls($conn, $ndc, $recall_number) {
     $sql = "INSERT INTO product_recalls (recall_number, product_ndc)
	     VALUES ('$recall_number', '$ndc')
             ON DUPLICATE KEY UPDATE product_ndc='$ndc'";

     if ($conn->query($sql) === TRUE) {
        echo "PRODUCT RECALLS $recall_number added successfully\n";
     } else {
        echo "Error creating PRODUCT RECALLS safetyreportid $recall_number : " . $conn->error;
     }
}

function create_product_ndc_faers_table($conn) {
   $sql = "CREATE TABLE IF NOT EXISTS product_faers( safetyreportid VARCHAR(13) NOT NULL, 
	   product_ndc VARCHAR(13) NOT NULL,
           PRIMARY KEY(safetyreportid, product_ndc)
	  )";
   if ($conn->query($sql) === TRUE) {
      echo "PRODUCT_FAERS table created successfully\n";
   } else {
      echo "Error creating PRODUCT_FAERS table: " . $conn->error;
   }
}

function insert_faers($conn, $safetyreportid, $companynumb, $occurcountry, $receivedate, $reactions) {
	$date=date("Ymd",strtotime($receivedate));
	if (strlen($reactions) >= 1024) {
		$reactions = substr($reactions, 0, 1023);
	}
     $sql = "INSERT INTO faers (safetyreportid, companynumb, occurcountry, receivedate, reactions)
	     VALUES ('$safetyreportid', '$companynumb', '$occurcountry', '$date', '$reactions')
             ON DUPLICATE KEY UPDATE reactions='$reactions'";

     print($sql);
     if ($conn->query($sql) === TRUE) {
        echo "FAERS $safetyreportid added successfully\n";
     } else {
        echo "Error creating safetyreportid $safetyreportid : " . $conn->error;
     }
}

function insert_product_faers($conn, $ndc, $safetyreportid) {
     $sql = "INSERT INTO product_faers (safetyreportid, product_ndc)
	     VALUES ('$safetyreportid', '$ndc')
             ON DUPLICATE KEY UPDATE product_ndc='$ndc'";

     print($sql);
     if ($conn->query($sql) === TRUE) {
        echo "PRODUCT FAERS $safetyreportid added successfully\n";
     } else {
        echo "Error creating PRODUCT FAERS safetyreportid $safetyreportid : " . $conn->error;
     }
}

function insert_product($conn, $product_ndc, $generic_name, $brand_name, $labeler_name) {
     $product_ndc = $conn->real_escape_string($product_ndc);
     $generic_name = $conn->real_escape_string($generic_name);
     $brand_name = $conn->real_escape_string($brand_name);
     $labeler_name = $conn->real_escape_string($labeler_name);
     $sql = "INSERT INTO products (product_ndc, generic_name, brand_name, labeler_name)
	     VALUES ('$product_ndc', '$generic_name', '$brand_name','$labeler_name')
             ON DUPLICATE KEY UPDATE generic_name='$generic_name', brand_name='$brand_name', labeler_name='$labeler_name'";

     print($sql);
     if ($conn->query($sql) === TRUE) {
        echo "Product $brand_name added successfully\n";
     } else {
        echo "Error creating brand $brand_name : " . $conn->error;
     }
}

function initialize_db() {
	$conn = connect_to_db();
	create_products_table($conn);
	create_faers_table($conn);
        create_product_ndc_faers_table($conn);
        create_recalls_table($conn);
	create_product_ndc_recalls_table($conn);
	close_db($conn);
}

initialize_db();

?>
