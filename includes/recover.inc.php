<?php

/* 
 * Copyright (C) 2013 peter
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

include_once 'db_connect.php';
include_once 'psl_config.php';
include_once 'functions.php';

$error_msg = "";

if (isset($_POST['email'])) {
    // Sanitize and validate the data passed in
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Not a valid email
        $error_msg .= '<p class="error">The email address you entered is not valid</p>';
    }
    
    if (user_check($mysqli, $email) == true) {
        //generates the random hash for the url
        //$random_hash = hash('sha512', uniqid(openssl_random_pseudo_bytes(8), FALSE));
        $random_hash = mt_rand(10000000, 99999999);

        //generates a random hash for the recovery code
        //$code = hash('sha512', uniqid(openssl_random_pseudo_bytes(8), FALSE));
        $code = mt_rand(10000000, 99999999);

        if ($random_hash == $code) {
            $code = mt_rand(10000000, 99999999);
        }

        $status = 1;

        if ($insert_stmt = $mysqli->prepare("INSERT INTO recovery (email, URLhash, code, valid) VALUES (?, ?, ?, ?)")) {
            $insert_stmt->bind_param('ssss', $email, $random_hash, $code, $status);

            if (! $insert_stmt->execute()) {
                $message = "There was a problem. Please <a href=\"mailto:garrett.massey.web@gmail.com\">contact the developer.</a>";
            }
            else {
                // redirect the user to the recover.php page
                $email_mssg = "Hello. We have sent this email because you forgot your password. Enter the recovery code: \n\n".$code."\n\nIf you closed the tab, click here to send a new code. If you received this email by mistake, or you didn't request this, please click here.\n";
                $email_mssg = wordwrap($email_mssg,70);
                // Send email (look into using a php mail service)

                mail($email,"Reset Password",$email_mssg);
                $recovery_url = "./recover.php?token=".$random_hash;
                header('Location: ./recover.php?token='.$random_hash);
            }
        }
    }
}