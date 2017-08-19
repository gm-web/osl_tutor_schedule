<?php 
	include_once '../../includes/db_connect.php';
	include_once '../../includes/functions.php';
?>
<script type="text/JavaScript" src="./js/schedule_form.js"></script>
<!--  <script>
// 	$('#loading_spinner').show();
// 	$("#table_content").load("../../includes/superadmin/view_schedule.inc.php", function() {
// 		$('#loading_spinner').hide();
// 	});
// </script> -->

<div class="col s12 m12 l12">
	<form method="submit" name="class_select" id="class_select">
		<div class="input-field col s12 m4 l4">
			<select name="course">
				<option value="0" selected>Select a class</option>
				<option value="MATH131">MATH131</option>
				<option value="MATH132">MATH132</option>
				<option value="MATH231">MATH231</option>
			</select>
			<label>Jump to class:</label>
			<input type="button" value="Select" onclick="return class_form(this.form, this.form.course);" />
		</div>
	</form>
</div>
<!-- <div class="col s12 m12 l12" id="loading_spinner" style="margin-top: 15%;">
	<div class="center-align">
		<div class="preloader-wrapper big active">
			<div class="spinner-layer spinner-blue-only">
				<div class="circle-clipper left">
					<div class="circle"></div>
				</div>
				<div class="gap-patch">
					<div class="circle"></div>
				</div>
				<div class="circle-clipper right">
					<div class="circle"></div>
				</div>
			</div>
		</div>
	</div>
</div> -->
<div class="col s12 m12 l12">
	<div class="table_content" id="table_content">
		<?php
			default_class($mysqli);
		?>
	</div>
</div>
	<script>
		$(document).ready(function() {
			$('select').material_select();
		});
	</script>
</div>