<HTML>
	<TITLE>Medication Alerts</TITLE>
        <link rel="stylesheet" href="../style.css"></link>
	<BODY>
               
		<?php
		  include "./session_util.php";
		  destroy_session();
		  redirect("./login.html");
    	        ?>
		<ul>
		  <li>	<a class="active" href="./logout.php">Logout</a> </li>
	          <li>	<a href="./login.html">Login</a> </li>
		</ul>
	</BODY>
</HTML>
