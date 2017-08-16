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

$error_msg = "";

if (isset($_POST['p'], $_POST['val'])) {
    
    $val = filter_input(INPUT_POST, 'val', FILTER_SANITIZE_NUMBER_INT);
    $val = filter_var($val, FILTER_VALIDATE_INT);

    echo "Val: ".$val."<br>";

    $password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);
    if (strlen($password) != 128) {
        // The hashed pwd should be 128 characters long.
        // If it's not, something really odd has happened
        $error_msg .= '<p class="error">Invalid password configuration.</p>';
    }

    // Username validity and password validity have been checked client side.
    // This should should be adequate as nobody gains any advantage from
    // breaking these rules.
    //
    
    $prep_stmt = "SELECT email FROM recovery WHERE URLhash = ?";
    $stmt = $mysqli->prepare($prep_stmt);
    
    if ($stmt) {
        $stmt->bind_param('i', $val);
        $stmt->execute();
        $stmt->store_result();

        /* if ($stmt->num_rows == 1) {
            echo "Email: ".$row['email']."<br>";
        }
        else {
            echo "No email exists<br>";
        } */
        $stmt->bind_result($email);
        $stmt->fetch();
        //echo $email."<br>";

    } else {
        $error_msg .= '<p class="error">Database error</p>';
    }
    
    // TODO: 
    // We'll also have to account for the situation where the user doesn't have
    // rights to do registration, by checking what type of user is attempting to
    // perform the operation.

    if (empty($error_msg)) {
        // Create a random salt
        $random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));

        // Create salted password 
        $password = hash('sha512', $password . $random_salt);

        // Insert the new user into the database 
        if ($update_stmt = $mysqli->prepare("UPDATE members SET password = ?, salt = ? WHERE email = ? LIMIT 1")) {
            $update_stmt->bind_param('sss', $password, $random_salt, $email);
            // Execute the prepared query.
            if (! $update_stmt->execute()) {
                //echo $password."\n";
                //echo $random_salt."\n";
                //echo $email;
                header('Location: ./error.php?err=Registration failure: UPDATE');
                exit();
            }
        }
        //echo "hashed password: ".$password."<br>";
        //echo "Random Salt: ".$random_salt."<br>";
        //echo "Email: ".$email;
        header('Location: ./register_success.php');
        exit();
    }
}