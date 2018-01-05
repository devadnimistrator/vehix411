<?php
$page_title = "Home";
$page_slug = "home";
require ('library/admin_application_top.php');

require ('views/header.php');
?>

<!-- Chart.js -->
<script src="assets/plugins/Chart.min.js"></script>
<!-- bootstrap-progressbar -->
<script src="assets/plugins/bootstrap/js/bootstrap-progressbar.min.js"></script>

<!-- page content -->
<div class="right_col" role="main">
	<?php
	include ('views/home/counts.php');
	?>
</div>
<!-- /page content -->

<?php
require ('views/footer.php');
