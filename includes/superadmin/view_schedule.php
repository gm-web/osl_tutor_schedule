<?php 
	include_once 'includes/db_connect.php';
	include_once 'includes/view_schedule.inc.php';
	include_once 'includes/functions.php';
?>

<div class="col s12 m12 l12">
	<form>
		<div class="input-field col s12 m4 l4">
			<select>
				<option value="" disabled selected>Select a class</option>
				<option value="1">Option 1</option>
				<option value="2">Option 2</option>
				<option value="3">Option 3</option>
			</select>
			<label>Class:</label>
		</div>
		</form>
	<table>
		<thead>
			<tr>
				<th>Day</th>
				<th>Time</th>
				<th>Location</th>
			</tr>
		</thead>
		<?php 

		?>
		<tbody>
			<tr>
				<td>Sunday</td>
				<td>--:--</td>
				<td>location</td>
			</tr>
			<tr>
				<td>Monday</td>
				<td>--:--</td>
				<td>location</td>
			</tr>
			<tr>
				<td>Tuesday</td>
				<td>--:--</td>
				<td>location</td>
			</tr>
			<tr>
				<td>Wednesday</td>
				<td>--:--</td>
				<td>location</td>
			</tr>
			<tr>
				<td>Thursday</td>
				<td>--:--</td>
				<td>location</td>
			</tr>
			<tr>
				<td>Friday</td>
				<td>--:--</td>
				<td>location</td>
			</tr>
		</tbody>
	</table>
	<script>
		$(document).ready(function() {
			$('select').material_select();
		});
	</script>
</div>