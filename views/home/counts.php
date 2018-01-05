<div class="tile_count">
	<div class="row">
		<div class="col-md-12 tile_stats_count">
			<span class="count_top"><i class="fa fa-clock-o"></i> Current Time</span>
			<div class="count" id="current-day">
				<?php echo date('M j, Y'); ?>
			</div>
			<span class="count_bottom" id="current-time"><?php echo date('g:i:s A'); ?></span>
		</div>
	</div>
	<div class="row">
    <div class="col-md-4 col-xs-12 tile_stats_count">
			<span class="count_top"><i class="fa fa-qrcode"></i> <a href="codes-all.php">Total Codes</a></span>
			<div class="count">
				<a href="codes-all.php"><?php echo number_format($wpdb -> get_var("SELECT count(*) FROM " . TABLE_PRODUCTS)); ?></a>
			</div>
		</div>
		<div class="col-md-4 col-xs-12 tile_stats_count">
			<span class="count_top"><i class="fa fa-car"></i> <a href="makes-all.php">Total Makes</a></span>
			<div class="count">
				<a href="makes-all.php"><?php echo number_format($wpdb -> get_var("SELECT count(*) FROM " . TABLE_MAKES)); ?></a>
			</div>
		</div>
	</div>
</div>	
<script>
	var showdatetimeTimeout = null;
	function showDateTime() {
		var day_options = {
    	timeZone: '<?php echo DEFAULT_TIMEZONE; ?>',
    	year: 'numeric', month: 'short', day: 'numeric',
		};
		
		var time_options = {
    	timeZone: '<?php echo DEFAULT_TIMEZONE; ?>',
    	hour: 'numeric', minute: 'numeric', second: 'numeric',
		};
		
		try {
			$("#current-day").text((new Date()).toLocaleString([], day_options));
			$("#current-time").text((new Date()).toLocaleString([], time_options));
		} catch(e){}
		
		if (showdatetimeTimeout) {
			clearTimeout(showdatetimeTimeout);
			showdatetimeTimeout = null;
		}
		
		showdatetimeTimeout = setTimeout(function() {
			showDateTime();
		}, 1000);
	}

	$(function() {
		showDateTime();
	})
</script>