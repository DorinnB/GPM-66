<!-- Sidebar -->
<div id="sidebar-wrapper">
	<ul class="sidebar-nav" id="tools-nav">
		<li class="sidebar-brand">
			MENU
		</li>
		<li>
			<a href="../ticket/index.php?project=1">Issues Tracker</a>
		</li>
	</ul>

</div>
<!-- /#sidebar-wrapper -->
<!-- Menu Toggle Script -->
<script>


$("#menu-toggle").click(function(e) {
	e.preventDefault();
	if (($("#tools-nav").is(":visible")) && ($("#wrapper:not(.toggled)"))) {
		$("#wrapper").toggleClass("toggled");
	}
	else if ($("#wrapper").hasClass("toggled")) {
		$("#wrapper").removeClass("toggled");
	}
});

</script>
