<!-- Sidebar -->
<div id="sidebar-wrapper">
	<ul class="sidebar-nav" id="tools-nav">
		<li class="sidebar-brand">
			MENU
		</li>
		<li>
			<a href="index.php?page=IT&tool=cookie">Cookie</a>
		</li>
		<li>
			<a href="controller/createUBR-controller.php">UBR</a>
		</li>
		<li>
			<a href="../ticket/index.php?project=1">Issues Tracker</a>
		</li>
	</ul>





</div>
<!-- /#sidebar-wrapper -->
<!-- Menu Toggle Script -->
<script>
$("#wrapper").toggleClass("toggled");

$("#menu-toggle").click(function(e) {
	e.preventDefault();
	$("#wrapper").toggleClass("toggled");
});

</script>
