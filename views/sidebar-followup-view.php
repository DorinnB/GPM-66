<!-- Sidebar -->
<div id="sidebar-wrapper">
	<ul class="sidebar-nav" id="tools-nav">
		<li class="sidebar-brand">
			MENU
		</li>
		<li id="button">
		</li>
		<li>
			<a href="index.php?page=followup&filtreFollowup=SubC">Follow Up SubC</a>
		</li>
		<li>
			<a href="index.php?page=followup&filtreFollowup=ALL">Follow Up ALL</a>
		</li>
		<li>
			<a href="index.php?page=followup&filtreFollowup=NoTime">Follow Up All No Time</a>
		</li>
		<li>
			<a href="index.php?page=ListeEssais">Test list</a>
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
