<?php
/**
 * Copyright (C) 2013 peredur.net
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();
?>
<!DOCTYPE html>
<html>
<head>
	
	<!-- Basic Page Needs
	–––––––––––––––––––––––––––––––––––––––––––––––––– -->
	<meta charset="UTF-8">
	<title><?php echo htmlentities($_SESSION['username']); ?> Logged In</title>
	<meta name="description" content="">
	<meta name="author" content="">

	<!-- CSS -->
	<link rel="stylesheet" href="assets/css/materialize.css">

	<!-- Fonts / Icons -->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">

	<!-- Viewport -->
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Google ReCaptcha -->
	<script src='https://www.google.com/recaptcha/api.js'></script>

	<!-- Essential Scripts -->
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
</head>
<body>
	<?php 
		/* checks to make sure the user is logged in */
		if (login_check($mysqli) == true) {
			/* checks if the user is a superadmin */
			if (sa_check($mysqli) == true) {
				include_once('includes/superadmin/index.php');
			}
			/* checks if the user is an admin */
			else if (admin_check($mysqli) == true) {
				include_once('includes/admin/index.php');
			}
			/* checks if the user is a neither admin or superadmin */
			else if (user_check($mysqli) == true) {
				include_once('includes/user/index.php');
			}
			/* if the user is none of the categories above, push javascript alert */
			else {
				echo '<script>alert("Why are you here? Either you messed up, the developer messed up, or you somehow found a backdoor. Please go away.");</script>';
			}
		}

		/* if user is not logged in, direct them to the login page */
		else {
			echo '<p><span class="error">You are not authorized to access this page.</span> Please <a href="index.php">log in</a>.</p>';
		}
	?>
	<script>
		$(document).ready(function() {
			$(".button-collapse").sideNav();
		});
	</script>
</body>
</html>
