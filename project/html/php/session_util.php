<?php

function redirect($url) {
    header('Location: '.$url);
    die();
}

function validate_session() {
	session_start();
	if (!isset($_SESSION['user'])) {
		redirect("../login.html");
	}
	echo "Logged in as " . $_SESSION['user'];
	echo "<br/>";
}

function destroy_session() {
	session_start();
	unset($_SESSION["user"]);
}
?>
