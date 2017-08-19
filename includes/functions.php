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

include_once 'psl_config.php';

function sec_session_start() {
	$session_name = 'sec_session_id';   // Set a custom session name 
	$secure = SECURE;

	// This stops JavaScript being able to access the session id.
	$httponly = true;

	// Forces sessions to only use cookies.
	if (ini_set('session.use_only_cookies', 1) === FALSE) {
		header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
		exit();
	}

	// Gets current cookies params.
	$cookieParams = session_get_cookie_params();
	session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);

	// Sets the session name to the one set above.
	session_name($session_name);

	session_start();            // Start the PHP session 
	session_regenerate_id();    // regenerated the session, delete the old one. 
}

function user_check($mysqli, $email) {

	$prep_stmt = "SELECT id FROM members WHERE email = ? LIMIT 1";
	$stmt = $mysqli->prepare($prep_stmt);

	if ($stmt) {
		$stmt->bind_param('s', $email);
		$stmt->execute();
		$stmt->store_result();

		if ($stmt->num_rows == 1) {
			// the email is valid and the user exists
			return true;
		}
		else {
			return false;
		}
	} 
	else {
		return false;
	}
}

function recovery_login($mysqli, $code, $email) {
	if ($stmt = $mysqli->prepare("SELECT id, username FROM members")) {
		$stmt->bind_param('s', $email);
		$stmt->execute();
		$stmt->store_result();

		$stmt->bind_result($user_id, $username, $recovery_code);
		$stmt->fetch();

		if($stmt->num_rows == 1) {
			if ($recovery_code == 0) {
				return false;
			}
			else {
				if ($recovery_code == $code) {
					//code is correct
					//log in
					// Get the user-agent string of the user.
					$user_browser = $_SERVER['HTTP_USER_AGENT'];

					// XSS protection as we might print this value
					$user_id = preg_replace("/[^0-9]+/", "", $user_id);
					$_SESSION['user_id'] = $user_id;

					// XSS protection as we might print this value
					$username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username);

					$_SESSION['username'] = $username;
					$_SESSION['login_string'] = hash('sha512', $code . $user_browser);

					// Login successful. 
					return true;
				}
				else {
					//recovery code entered was incorrect
					return false;
				}
			}
		}
		else {
			return false;
		}
	}
	else {
		header("Location: ../error.php?err=Database error: cannot prepare statement");
		exit();
	}
}

function login($email, $password, $mysqli) {
	// Using prepared statements means that SQL injection is not possible. 
	if ($stmt = $mysqli->prepare("SELECT id, username, password, salt 
				  FROM members 
								  WHERE email = ? LIMIT 1")) {
		$stmt->bind_param('s', $email);  // Bind "$email" to parameter.
		$stmt->execute();    // Execute the prepared query.
		$stmt->store_result();

		// get variables from result.
		$stmt->bind_result($user_id, $username, $db_password, $salt);
		$stmt->fetch();

		// hash the password with the unique salt.
		$password = hash('sha512', $password . $salt);
		if ($stmt->num_rows == 1) {
			// If the user exists we check if the account is locked
			// from too many login attempts 
			if (checkbrute($user_id, $mysqli) == true) {
				// Account is locked 
				// Send an email to user saying their account is locked 
				return false;
			} else {
				// Check if the password in the database matches 
				// the password the user submitted.
				if ($db_password == $password) {
					// Password is correct!
					// Get the user-agent string of the user.
					$user_browser = $_SERVER['HTTP_USER_AGENT'];

					// XSS protection as we might print this value
					$user_id = preg_replace("/[^0-9]+/", "", $user_id);
					$_SESSION['user_id'] = $user_id;

					// XSS protection as we might print this value
					$username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username);

					$_SESSION['username'] = $username;
					$_SESSION['login_string'] = hash('sha512', $password . $user_browser);

					// Login successful. 
					return true;
				} else {
					// Password is not correct 
					// We record this attempt in the database 
					$now = time();
					if (!$mysqli->query("INSERT INTO login_attempts(user_id, time) 
									VALUES ('$user_id', '$now')")) {
						header("Location: ../error.php?err=Database error: login_attempts");
						exit();
					}

					return false;
				}
			}
		} else {
			// No user exists. 
			return false;
		}
	} else {
		// Could not create a prepared statement
		header("Location: ../error.php?err=Database error: cannot prepare statement");
		exit();
	}
}

function checkbrute($user_id, $mysqli) {
	// Get timestamp of current time 
	$now = time();

	// All login attempts are counted from the past 2 hours. 
	$valid_attempts = $now - (2 * 60 * 60);

	if ($stmt = $mysqli->prepare("SELECT time 
								  FROM login_attempts 
								  WHERE user_id = ? AND time > '$valid_attempts'")) {
		$stmt->bind_param('i', $user_id);

		// Execute the prepared query. 
		$stmt->execute();
		$stmt->store_result();

		// If there have been more than 5 failed logins 
		if ($stmt->num_rows > 5) {
			return true;
		} else {
			return false;
		}
	} else {
		// Could not create a prepared statement
		header("Location: ../error.php?err=Database error: cannot prepare statement");
		exit();
	}
}

function login_check($mysqli) {
	// Check if all session variables are set 
	if (isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) {
		$user_id = $_SESSION['user_id'];
		$login_string = $_SESSION['login_string'];
		$username = $_SESSION['username'];

		// Get the user-agent string of the user.
		$user_browser = $_SERVER['HTTP_USER_AGENT'];

		if ($stmt = $mysqli->prepare("SELECT password 
					  FROM members 
					  WHERE id = ? LIMIT 1")) {
			// Bind "$user_id" to parameter. 
			$stmt->bind_param('i', $user_id);
			$stmt->execute();   // Execute the prepared query.
			$stmt->store_result();

			if ($stmt->num_rows == 1) {
				// If the user exists get variables from result.
				$stmt->bind_result($password);
				$stmt->fetch();
				$login_check = hash('sha512', $password . $user_browser);

				if ($login_check == $login_string) {
					// Logged In!!!! 
					return true;
				} else {
					// Not logged in 
					return false;
				}
			} else {
				// Not logged in 
				return false;
			}
		} else {
			// Could not prepare statement
			header("Location: ../error.php?err=Database error: cannot prepare statement");
			exit();
		}
	} else {
		// Not logged in 
		return false;
	}
}

function esc_url($url) {

	if ('' == $url) {
		return $url;
	}

	$url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);
	
	$strip = array('%0d', '%0a', '%0D', '%0A');
	$url = (string) $url;
	
	$count = 1;
	while ($count) {
		$url = str_replace($strip, '', $url, $count);
	}
	
	$url = str_replace(';//', '://', $url);

	$url = htmlentities($url);
	
	$url = str_replace('&amp;', '&#038;', $url);
	$url = str_replace("'", '&#039;', $url);

	if ($url[0] !== '/') {
		// We're only interested in relative links from $_SERVER['PHP_SELF']
		return '';
	} else {
		return $url;
	}
}

function sa_check($mysqli) {
	if (isset($_SESSION['user_id'])) {
		$user_id = $_SESSION['user_id'];
		if ($stmt = $mysqli->prepare("SELECT level 
					  FROM members 
					  WHERE id = ? LIMIT 1")) {
			// Bind "$user_id" to parameter. 
			$stmt->bind_param('i', $user_id);
			$stmt->execute();   // Execute the prepared query.
			$stmt->store_result();
		}
		if ($stmt->num_rows == 1) {
			// If the user exists get variables from result.
			$stmt->bind_result($level);
			$stmt->fetch();
			$level_check = 0;
			if ($level_check == $level) {
				return TRUE;
			}
			else {
				return FALSE;
			}
		}
		else {
			return FALSE;
		}
	}
	else {
		return FALSE;
	}
}

function list_users($mysqli) {
	$stmt = "SELECT username, email, status, level FROM members";
	$result = $mysqli->query($stmt);
	if($result->num_rows > 0) {
		echo 
			"<table>
				<thead>
					<tr>
						<th>Name</th>
						<th>Status</th>
						<th>Email</th>
						<th>Schedule</th>
						<th>Classes</th>
						<th>Edit</th>
					</tr>
				</thead>";
		while ($row = $result->fetch_assoc()) {
			echo
			"<tbody>
				<tr>
					<td>" . $row['username'] . "</td>";
			if (($row['status'] == 1 && $row['level'] == 0) || ($row['status'] == 1 && $row['level'] == 1)) {
				echo "<td><span class=\"new badge green\">OSL Administrator</span></td>";
			}
			else if ($row['status'] == 1) {
				echo "<td><span class=\"new badge blue\">Current Tutor</span></td>";
			}
			else if ($row['status'] == 0) {
				echo "<td><span class=\"new badge red\">Former Tutor</span></td>";
			}
			else {
				echo "<td><span class=\"new badge grey\">Unknown Error</span></td>";
			}		
				echo "<td>" . $row['email'] . "</td>
				<td><a href=\"#\"><i class=\"material-icons\">event</i></a></td>
				<td><a href=\"#\"><i class=\"material-icons\">view_list</i></a></td>
				<td><a href=\"#\"><i class=\"material-icons\">create</i></a></td>
			</tr>";

		}
		echo "</tbody></table>";
	}
}

function default_class($mysqli) {
	$stmt = "SELECT members.username, members.id, tutor_schedule.day, tutor_schedule.start_time, tutor_schedule.end_time, tutor_class.class_id FROM members, tutor_schedule, tutor_class WHERE tutor_class.tutor_id = tutor_schedule.tutor_id AND members.id = tutor_schedule.tutor_id AND tutor_class.class_id = 'MATH131' ORDER BY tutor_schedule.day, tutor_schedule.start_time ASC";
	$result = $mysqli->query($stmt);
	if($result->num_rows > 0) {
		echo "<h5>MATH131</h5>";
		echo "<table>
				<thead>
					<tr>
						<th>Day</th>
						<th>Time</th>
					</tr>
				</thead>";
		$i = 0;
		$s = array();
		$m = array();
		$t = array();
		$w = array();
		$tr = array();
		$f = array();
		while ($row = $result->fetch_assoc()) {
			foreach ($row as $x => $value) {
				if($x == 'day') {
					if ($value == 0) {
						$s[$i] = $row['start_time'];
						$s[$i + 1] = $row['end_time'];
						$i+=2;
						//echo "i for sunday = ".$i."<br>";
					}
					else if ($value == 1) {
						$m[$i] = $row['start_time'];
						$m[$i + 1] = $row['end_time'];
						$i+=2;
					}
					else if ($value == 2) {
						$t[$i] = $row['start_time'];
						$t[$i + 1] = $row['end_time'];
						$i+=2;
					}
					else if ($value == 3) {
						$w[$i] = $row['start_time'];
						$w[$i + 1] = $row['end_time'];
						$i+=2;
					}
					else if ($value == 4) {
						$tr[$i] = $row['start_time'];
						$tr[$i + 1] = $row['end_time'];
						$i+=2;
					}
					else if ($value == 5) {
						$f[$i] = $row['start_time'];
						$f[$i + 1] = $row['end_time'];
						$i+=2;
					}
				}
			}
		}
		$Sunday = time_calc($s);
		$Monday = time_calc($m);
		$Tuesday = time_calc($t);
		$Wednesday = time_calc($w);
		$Thursday = time_calc($tr);
		$Friday = time_calc($f);
		echo
			"<tbody>";
			// echo "<tr>
			// 	<td>Sunday</td><td>";
			// 	//$times = array_values($Sunday);
			// 	for ($j = 0; $j < count($Sunday) - 1; $j+=2) {
			// 		echo $Sunday[$j]."-".$Sunday[$j+1]."<br>";
			// 	}
			// echo "</td>";
			// echo "</tr>";
			echo "<tr>
				<td>Monday</td><td>";
				//$times = array_values($Sunday);
				for ($j = 0; $j < count($Monday) - 1; $j+=2) {
					echo $Monday[$j]."-".$Monday[$j+1]."<br>";
				}
			echo "</td>";
			echo "</tr>";
		echo "</tbody></table>";
	}
}

function time_calc($arr) {
	$final_arr = array();
	$j = 0;
	$n = count($arr);
	if (count($arr)  == 2) {
		return $arr;
	}
	else {
		$t_start = $arr[0];
		$t_end = $arr[1];
		for ($i = 0; $i < $n - 2; $i+=2) {
			if ($arr[$i + 2] <= $t_end) {
				$t_end = $arr[$i + 3]; //next end time
			}
			else {
				$final_arr[$j] = $t_start;
				$final_arr[$j+1] = $t_end;
				$j+=2;
				$t_start = $arr[$i + 2];
				$t_end = $arr[$i + 3];
			}
		}
		$final_arr[$j] = $t_start;
		$final_arr[$j+1] = $t_end;
	 	return $final_arr;
	}
}

function day($d) {
	if ($d == 0) {
		return "Sunday";
	}
	elseif ($d == 1) {
		return "Monday";
	}
	elseif ($d == 2) {
		return "Tuesday";
	}
	elseif ($d == 3) {
		return "Wednesday";
	}
	elseif ($d == 4) {
		return "Thursday";
	}
	elseif ($d == 5) {
		return "Friday";
	}
	else {
		return "Error";
	}
}
