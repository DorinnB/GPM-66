
jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.location = "http://gpm/gpm/index.php?page=split&id_tbljob="+$(this).data("id");
    });
});
