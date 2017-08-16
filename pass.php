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

include_once 'includes/functions.php';
include_once 'includes/pass.inc.php';

sec_session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Success</title>
        <link rel="stylesheet" href="styles/main.css" />
        <script type="text/JavaScript" src="js/sha512.js"></script> 
        <script type="text/JavaScript" src="js/npass.js"></script>
    </head>
    <body>
        <?php 
            $url = $_SERVER['REQUEST_URI'];
            if (parse_url($url, PHP_URL_QUERY) == 'fail') {
                echo "<p>Something went wrong. Sorry.</p>";
            }
            else {
                $val = parse_url($url, PHP_URL_QUERY);
                $val = intval($val);
                echo $val;
            }
        ?>
        <form method="post" name="new_pass_form" action="<?php echo esc_url($_SERVER['PHP_SELF']); ?>">
            New Password: <input type="password"
                             name="password" 
                             id="password"/><br>
            Confirm Password: <input type="password" 
                                     name="confirmpwd" 
                                     id="confirmpwd" /><br>
            <input type="hidden" id="val" name="val" value="<?php echo $val;?>" />
            <input type="button" 
                   value="Register" 
                   onclick="return newpass(this.form,
                                   this.form.password,
                                   this.form.confirmpwd,
                                   this.form.val);" /> 
        </form>
    </body>
</html>
