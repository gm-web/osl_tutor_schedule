<div class="navbar-fixed">
	<nav>
		<div class="nav-wrapper">
			<a href="#!" class="brand-logo">Welcome <?php echo htmlentities($_SESSION['username']); ?></a>
			<ul class="right hide-on-med-and-down">
				<li><a class="dropdown-button" href="#!" data-activates="schedule_dd">Schedule<i class="material-icons right">arrow_drop_down</i></a></li>
				<li><a class="dropdown-button" href="#!" data-activates="users_dd">Users<i class="material-icons right">arrow_drop_down</i></a></li>
				<li><a href="#">Admin</a></li>
				<li><a href="includes/logout.php">Log Out</a></li>
			</ul>
		</div>
	</nav>
	<ul id="schedule_dd" class="dropdown-content">
		<li><a id="view">View Schedule</a></li>
		<li><a id="edit_schedule">Edit Schedule</a></li>
		<li class="divider"></li>
		<li><a id="recalc">Recalculate Schedule</a></li>
	</ul>
	<ul id="users_dd" class="dropdown-content">
		<li><a id="view_users">View Users</a></li>
		<li><a id="add_users">Add User</a></li>
	</ul>
</div>