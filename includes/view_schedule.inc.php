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
	// iterates through the days of the week
	/* for ($i=0; $i <= 6; $i++) { 
		// inside loop finds the blocks
		// of time the class is tutored
		$day_time = array(array());
		$j = $result->num_rows();
		for ($j = 1000; $j <= 1700; $j+= 30) {
			while ($row = $result->fetch_assoc()) {
				$day_time[]
			}
		}
	} */
}
