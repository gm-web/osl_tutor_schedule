<?php 
function class_schedule($mysqli, $course) {

	if (isset($_POST['course'])) {
		$course = filter_input(INPUT_POST, 'course', FILTER_SANITIZE_STRING);
		if($stmt = $mysqli->prepare("SELECT members.username, members.id, tutor_schedule.day, tutor_schedule.start_time, tutor_schedule.end_time, tutor_class.class_id FROM members, tutor_schedule, tutor_class WHERE tutor_class.tutor_id = tutor_schedule.tutor_id AND members.id = tutor_schedule.tutor_id AND tutor_class.class_id = 'MATH131' ORDER BY tutor_schedule.day ASC")) {
			$stmt->bind_param('s', $course);
			$stmt->execute();   // Execute the prepared query.
			$stmt->store_result();
		}
		if ($stmt->num_rows > 1) {
			// If the user exists get variables from result.
			$stmt->bind_result($results);
			$stmt->fetch();
		}
		return $results;
	}
}


function day_calc($d) {
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

function create_table($results) {
	if ($results->num_rows > 0) {
		//echo "<p>Schedule for ".$row['class_id']."</p>";
		echo "<table>
				<thead>
					<tr>
						<th>Tutor</th>
						<th>Day</th>
						<th>Start Time</th>
						<th>End Time</th>
					</tr>
				</thead>";
		while ($row = $result->fetch_assoc()) {
			echo 
			"<tbody>
				<tr>
					<td>" . $row['username'] . "</td>
					<td>" . day_calc($row['day']) . "</td>
					<td>" . $row['start_time'] . "</td>
					<td>" . $row['end_time'] . "</td></tr>";
		}
		echo "</tbody></table>";
	}
}
