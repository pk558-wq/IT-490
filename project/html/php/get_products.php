<!DOCTYPE HTML>
<HTML>
	<HEAD>
           <meta name="viewport" content="width=device-width, initial-scale=1">  
	   <TITLE>Get Products</TITLE>
	   <LINK rel="stylesheet" href="./style.css"></LINK>
	</HEAD>
	<BODY>
		<?php
		  include_once "./session_util.php";
		  validate_session();
?>
<ul>
  <li><a class="active" href="./get_products.php">Search</a></li>
  <li><a href="./get_user_products_mq.php">My Medications</a></li>
  <li><a href="./logout.php">Logout</a></li>
</ul>
                <center> <h1>Search</h1> </center>
                <form action="./get_products_mq.php" method="post">
                </span>
                    <div class="container">
	                <input type="hidden" id="type" name="type" value="get_products">
			<label for="search-id">Product Search:</label> <br/>
			<input type="text" id="search-id" name="search" maxLength=32/> <br/>
                        <button type="submit">Search</button>
		    </div>
		</form>
	</BODY>
</HTML>
