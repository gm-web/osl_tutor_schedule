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
include_once '../includes/db_connect.php';
include_once '../includes/functions.php';

sec_session_start();
?>
<!DOCTYPE html>
<html>
<head>
	
	<!-- Basic Page Needs
	–––––––––––––––––––––––––––––––––––––––––––––––––– -->
	<meta charset="UTF-8">
	<title><?php echo htmlentities($_SESSION['username']); ?> Logged In</title>
	<meta name="description" content="">
	<meta name="author" content="">

	<!-- CSS -->
	<link rel="stylesheet" href="../assets/css/materialize.css">
	<link rel="stylesheet" href="../assets/css/parallax.css">

	<!-- Fonts / Icons -->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">

	<!-- Viewport -->
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Google ReCaptcha -->
	<script src='https://www.google.com/recaptcha/api.js'></script>

	<!-- REQUIRED Pre-loading Scripts -->
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script type="text/javascript" src="../assets/js/materialize.min.js"></script>

</head>
<body>
	<?php if (login_check($mysqli) == true) : ?>
			<?php include("../includes/superadmin/sa_menu.php"); ?>
	<div class="container" style="margin-top: 100px; margin-bottom: 200px;">
		<p>To add a new tutor and their schedule to the database, enter their 900 number, their NMT email address and name. Once this is entered, select the classes the tutor is capable of working with, and their schedule and hit "submit".</p>
		<div class="row">
			<script>
				$(document).ready(function () {
					$('input').attr('autocomplete', 'false');
					$('select').material_select();
				});

			</script>
			<form autocomplete="off" action="#" class="col s12">
				<div class="row">
					<div class="input-field col l6 m6 s12">
						<input id="name" type="text" class="validate">
						<label for="name">John Smith</label>
					</div>
				</div>
				<div class="row">
					<div class="input-field col l6 m6 s12">
						<input id="id_num" type="text" class="validate">
						<label for="id_num">900</label>
					</div>
				</div>
				<div class="row">
					<div class="input-field col l6 m6 s12">
						<input id="email" type="email" class="validate">
						<label for="email">name@student.nmt.edu</label>
					</div>
				</div>
				<div class="row">
					<div class="input-field col l6 m6 s12">
						<input id="email" type="email" class="validate">
						<label for="email">name@student.nmt.edu</label>
					</div>
				</div>
				<p style="margin-top: 100px;">Check the classes the tutor is qualified to tutor in.</p>
				<div class="row">
					<div class="col l4 m6 s12">
						<p>Math</p>
						<p>
							<input type="checkbox" id="MATH101" />
							<label for="MATH101">MATH101 (College Algebra)</label>
						</p>
						<p>
							<input type="checkbox" id="MATH103" />
							<label for="MATH103">MATH103 (Pre Calc)</label>
						</p>
						<p>
							<input type="checkbox" id="MATH104" />
							<label for="MATH104">MATH104 (Trig)</label>
						</p>
						<p>
							<input type="checkbox" id="MATH131" />
							<label for="MATH131">MATH131 (Calc I)</label>
						</p>
						<p>
							<input type="checkbox" id="MATH132" />
							<label for="MATH132">MATH132 (Calc II)</label>
						</p>
						<p>
							<input type="checkbox" id="MATH231" />
							<label for="MATH231">MATH231 (CALC III)</label>
						</p>
						<p>
							<input type="checkbox" id="MATH254" />
							<label for="MATH254">MATH254 (Intro to Linear Algebra)</label>
						</p>
						<p>
							<input type="checkbox" id="MATH283" />
							<label for="MATH283">MATH283 (Intro to Applied Statistics)</label>
						</p>
						<p>
							<input type="checkbox" id="MATH332" />
							<label for="MATH332">MATH332 (Vector Analysis)</label>
						</p>
						<p>
							<input type="checkbox" id="MATH335" />
							<label for="MATH335">MATH335 (ODEs)</label>
						</p>
						<p>
							<input type="checkbox" id="MATH336" />
							<label for="MATH336">MATH336 (Intro to PDEs)</label>
						</p>
						<p>
							<input type="checkbox" id="MATH337" />
							<label for="MATH337">MATH337 (Engineering Math)</label>
						</p>
						<p>
							<input type="checkbox" id="MATH352" />
							<label for="MATH352">MATH352 (Basic Concepts)</label>
						</p>
						<p>
							<input type="checkbox" id="MATH382" />
							<label for="MATH382">MATH382 (Probability and Statistics)</label>
						</p>
					</div>
					<div class="col l4 m6 s12">
						<p>Chemistry</p>
						<p>
							<input type="checkbox" id="CHEM109" />
							<label for="CHEM109">CHEM109 (Intro to Chemistry)</label>
						</p>
						<p>
							<input type="checkbox" id="CHEM121" />
							<label for="CHEM121">CHEM121 (Gen. Chem 1)</label>
						</p>
						<p>
							<input type="checkbox" id="CHEM122" />
							<label for="CHEM122">CHEM122 (Gen. Chem 2)</label>
						</p>
						<p>
							<input type="checkbox" id="CHEM311" />
							<label for="CHEM311">CHEM311 (Quantitative Analysis)</label>
						</p>
						<p>
							<input type="checkbox" id="CHEM331" />
							<label for="CHEM331">CHEM331 (Physical Chemistry I)</label>
						</p>
						<p>
							<input type="checkbox" id="CHEM332" />
							<label for="CHEM332">CHEM332 (Physical Chemistry II)</label>
						</p>
						<p>
							<input type="checkbox" id="CHEM333" />
							<label for="CHEM333">CHEM333 (Organic Chemistry I)</label>
						</p>
						<p>
							<input type="checkbox" id="CHEM334" />
							<label for="CHEM334">CHEM334 (Organic Chemistry II)</label>
						</p>
					</div>
					<div class="input-field col l4 m6 s12">
						<p>Physics</p>
						<p>
							<input type="checkbox" id="PHYS109" />
							<label for="PHYS109">PHYS109 (Intro to Physics)</label>
						</p>
						<p>
							<input type="checkbox" id="PHYS121" />
							<label for="PHYS121">PHYS121 (Gen. Phys. I)</label>
						</p>
						<p>
							<input type="checkbox" id="PHYS122" />
							<label for="PHYS122">PHYS122 (Gen. Phys. II)</label>
						</p>
						<p>
							<input type="checkbox" id="PHYS221" />
							<label for="PHYS221">PHYS221 (Comprehensive Physics I)</label>
						</p>
						<p>
							<input type="checkbox" id="PHYS222" />
							<label for="PHYS222">PHYS222 (Comprehensive Physics II</label>
						</p>
						<p>
							<input type="checkbox" id="PHYS241" />
							<label for="PHYS241">PHYS241 (Computational Physics I)</label>
						</p>
						<p>
							<input type="checkbox" id="PHYS242" />
							<label for="PHYS242">PHYS242 (Computational Physics II)</label>
						</p>
					</div>
					<div class="input-field col l4 m6 s12">
						<p>Biology</p>
						<p>
							<input type="checkbox" id="" />
							<label for="">MATH131</label>
						</p>
					</div>
					<div class="input-field col l4 m6 s12">
						<p>Chemical Engineering</p>
						<p>
							<input type="checkbox" id="" />
							<label for="">MATH131</label>
						</p>
					</div>
					<div class="input-field col l4 m6 s12">
						<p>Civil Engineering</p>
						<p>
							<input type="checkbox" id="" />
							<label for="">MATH131</label>
						</p>
					</div>
					<div class="input-field col l4 m6 s12">
						<p>Computer Science / IT</p>
						<p>
							<input type="checkbox" id="" />
							<label for="">MATH131</label>
						</p>
					</div>
					<div class="input-field col l4 m6 s12">
						<p>Earth Science</p>
						<p>
							<input type="checkbox" id="" />
							<label for="">MATH131</label>
						</p>
					</div>
					<div class="input-field col l4 m6 s12">
						<p>Economics</p>
						<p>
							<input type="checkbox" id="" />
							<label for="">MATH131</label>
						</p>
					</div>
					<div class="input-field col l4 m6 s12">
						<p>Engineering Science</p>
						<p>
							<input type="checkbox" id="" />
							<label for="">MATH131</label>
						</p>
					</div>
					<div class="input-field col l4 m6 s12">
						<p>Environmental Engineering</p>
						<p>
							<input type="checkbox" id="" />
							<label for="">MATH131</label>
						</p>
					</div>
					<div class="input-field col l4 m6 s12">
						<p>Materials Engineering</p>
						<p>
							<input type="checkbox" id="" />
							<label for="">MATH131</label>
						</p>
					</div>
					<div class="input-field col l4 m6 s12">
						<p>Mechanical Engineering</p>
						<p>
							<input type="checkbox" id="" />
							<label for="">MATH131</label>
						</p>
					</div>
					<div class="input-field col l4 m6 s12">
						<p>Petroleum Engineering</p>
						<p>
							<input type="checkbox" id="" />
							<label for="">MATH131</label>
						</p>
					</div>
					<div class="input-field col l4 m6 s12">
						<p>Psychology</p>
						<p>
							<input type="checkbox" id="" />
							<label for="">MATH131</label>
						</p>
					</div>
					<div class="input-field col l4 m6 s12">
						<p>Workshop Skills</p>
						<p>
							<input type="checkbox" id="" />
							<label for="">MATH131</label>
						</p>
					</div>
				</div>
				<p style="margin-top: 100px;">Select the date and the time the tutor works (i.e Monday at 12:00-14:00)</p>

				<div class="row" id="dayview">
					<div id="days">
						<div class="input-field col s12 m4 l4">
							<select>
								<option value="" disabled selected>Choose your option</option>
								<option value="1">Sunday</option>
								<option value="2">Monday</option>
								<option value="3">Tuesday</option>
								<option value="4">Wednesday</option>
								<option value="5">Thursday</option>
								<option value="6">Friday</option>
								<option value="7">Saturday</option>
							</select>
							<label>Day</label>
						</div>
						<div class="input-field col s12 m3 l3">
							<input type="text" class="timepicker" id="starttime">
							<label for="starttime">Start Time (i.e 1400)</label>
						</div>
						<div class="input-field col s12 m3 l3">
							<input type="text" class="timepicker" id="endtime">
							<label for="endtime">End Time (i.e 1630)</label>
						</div>
						<div class="input-field col s12 m2 l2">
							<button id="addDay" class="btn-floating btn-large waves-effect waves-light"><i class="material-icons">add</i></button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
	<?php else : ?>
		<p>
			<span class="error">You are not authorized to access this page.</span> Please <a href="index.php">login</a>.
		</p>
	<?php endif; ?>
	<script>
		$(document).ready(function() {
			$(".button-collapse").sideNav();
			$('.modal').modal();
			$('select').material_select();
		});
	</script>
	<script type="text/javascript">
		(function($){
			$countForms = 1;
			$.fn.addForms = function(){
				var myform = '<div class="input-field col s12 m4 l4">'+
						'<select>'+
							'<option value="" disabled selected>Choose your option</option>'+
							'<option value="1">Sunday</option>'+
							'<option value="2">Monday</option>'+
							'<option value="3">Tuesday</option>'+
							'<option value="4">Wednesday</option>'+
							'<option value="5">Thursday</option>'+
							'<option value="6">Friday</option>'+
							'<option value="7">Saturday</option>'+
						'</select>'+
						'<label>Day</label>'+
					'</div>'+
					'<div class="input-field col s12 m3 l3">'+
						'<input type="text" class="timepicker" id="starttime'+$countForms+'">' +
						'<label for="starttime'+$countForms+'">Start Time (i.e 14:00)</label>' +
					'</div>'+
					'<div class="input-field col s12 m3 l3">' +
						'<input type="text" class="timepicker" id="endtime'+$countForms+'">' +
						'<label for="endtime'+$countForms+'">End Time (i.e 14:00)</label>' +
					'</div>';

				//myform = $("<div>"+myform+"</div>");
				$("button", $(myform)).click(function(){ $(this).parent().parent().remove(); });

				$(this).append(myform);
				$countForms++;
			};
		})(jQuery);
		$(function(){
			$("#addDay").bind("click", function(){
				$("#days").addForms();
				$('select').material_select();
			});
		});
	</script>
</body>
</html>
