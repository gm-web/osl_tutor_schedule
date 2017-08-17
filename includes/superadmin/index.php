	<?php include_once("includes/superadmin/sa_menu.php"); ?>
	<div class="container" style="margin-top: 100px;">
		<div class="row">
			<div id="content">
				
			</div>
		</div>
	</div>
	<script type="text/javascript" src="assets/js/materialize.min.js"></script>
	<script>
		$(document).ready(function() {
			$(".button-collapse").sideNav();
			$('.modal').modal();
			$('select').material_select();

		});
		$(document).ready(function(){
			$("#view").click(function(){
				$("#content").load("includes/superadmin/view_schedule.php");
			});
			$("#edit_schedule").click(function(){
				$("#content").load("includes/superadmin/edit_schedule.php");
			});
			$("#recalc").click(function(){
				$("#content").load("includes/superadmin/recalc.php");
			});
			$("#view_users").click(function(){
				$("#content").load("includes/superadmin/view_users.php");
			});
			$("#add_users").click(function(){
				$("#content").load("includes/superadmin/add_user_opt.php");
			});
		});
	</script>