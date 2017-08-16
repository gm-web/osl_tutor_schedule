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
include_once 'includes/recover.inc.php';
include_once 'includes/functions.php';

sec_session_start();

if (login_check($mysqli) == true) {
    header( 'Location: protected_page.php');
} else {
    $logged = 'out';
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Secure Login: Log In</title>
        <link rel="stylesheet" href="styles/main.css" />
        <script type="text/JavaScript" src="js/sha512.js"></script> 
        <script type="text/JavaScript" src="js/recoveryforms.js"></script> 
    </head>
    <body>
        <form action="includes/process_recovery.php" method="post" name="code_form"> 			
            Email: <input type="text" name="email" />
            Code: <input type="text" name="password" id="password"/>
            <input type="button" value="Login" onclick="formsub(this.form, this.form.email, this.form.password);" /> 
        </form>
        <p>Please enter the code you received in your email.</a></p>
    </body>
</html>
