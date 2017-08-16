<?php

/*
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

include_once 'db_connect.php';
include_once 'functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if (isset($_POST['email'], $_POST['password'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $code = $_POST['password'];
    //code_check($email, $code, $mysqli);
    $val = 1;
    if ($stmt = $mysqli->prepare("SELECT email, code, URLhash, valid FROM recovery WHERE email = ? AND valid = 1")) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        $stmt->bind_result($db_email, $db_code, $db_hash, $validity);
        $stmt->fetch();

        if($stmt->num_rows == 1) {
            if ($validity == 1) {
                if ($code == $db_code && $email == $db_email) {
                    if($stmt2 = $mysqli->prepare("UPDATE recovery SET valid = 0 WHERE code = ? AND email = ?")) {
                        $stmt2->bind_param('ss', $code, $email);
                        $stmt2->execute();
                    }
                    header('Location: ../pass.php?'.$db_hash);
                    exit();
                }
                else {
                    header('Location: ../pass.php?fail');
                    exit();
                }
            }
            else {
                header('Location: ../pass.php?fail');
                exit();
            }
        }
    }
}