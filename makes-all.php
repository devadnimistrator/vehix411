<?php
$page_title = "Makes";
$page_slug = "makes";
$page_sub_slug = "makes-all";
require ('library/admin_application_top.php');

$action = (isset($_GET['action']) ? $_GET['action'] : '');
$makeid = (isset($_GET['ID']) ? $_GET['ID'] : '');

if ($action == 'delete') {
	$wpdb -> delete(TABLE_MODELS, array("make_id" => $makeid));	
	$wpdb -> delete(TABLE_MAKES, array("ID" => $makeid));

	die('OK');
} elseif ($action == 'lock') {
	$wpdb -> update(TABLE_MAKES, array("status" => 0), array("ID" => $makeid));

	die('OK');
} elseif ($action == 'unlock') {
	$wpdb -> update(TABLE_MAKES, array("status" => 1), array("ID" => $makeid));

	die('OK');
}

$sql = "SELECT s.* FROM " . TABLE_MAKES . " s WHERE 1=1";
$makes = $wpdb -> get_results($sql);

require ('views/header.php');
?>

<!-- page content -->
<div class="right_col" role="main">
	<div class="">
		
		<div class="page-title">
      <div class="title_left">
        <h3><?php echo $page_title?></h3>
      </div>
    </div>
		<div class="clearfix"></div>
		
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">						
						<h2>Makes</h2>
            <div class="clearfix"></div>
					</div>
					<div class="x_content">
            <table id="table-makes" class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <!--th>Code</th-->
                  <th>Description</th>
                  <th>Models</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>

							<tbody>
							<?php if (count($makes) == 0) : ?>
								<tr>
									<td colspan="15">Empty makes.</td>
								</tr>
							<?php else: ?>
              	<?php foreach ($makes as $make) : ?>
                <tr id="make-<?php echo $make->ID ?>">
                  <td><?php echo $make->ID ?></td>
                  <td><?php echo $make->name ?></td>
                  <!--td><?php echo $make->code ?></td-->
                  <td><?php echo $make->description ?></td>
                  <td><?php echo $wpdb->get_var("SELECT count(*) FROM " . TABLE_MODELS . " WHERE make_id=".$make->ID) ?></td>
                  <td class="status-text"><?php echo($make -> status == 1 ? 'Enabled' : 'Disabled'); ?>
                  <td>
                  	<a href='makes-edit.php?ID=<?php echo $make->ID ?>' title="Edit"><i class="fa fa-edit"></i></a>
                  	&nbsp;|&nbsp;
                  	<a href='javascript:delete_make(<?php echo $make->ID ?>)' title="Delete"><i class="fa fa-remove"></i></a>
									 	&nbsp;|&nbsp;
									<?php if ($make->status == 1) : ?>
									 	<a href='javascript:lock_make(<?php echo $make->ID ?>)' title="Click for lock" class="make-status"><i class="fa fa-unlock"></i></a>
									<?php else: ?>
									 	<a href='javascript:unlock_make(<?php echo $make->ID ?>)' title="Click for unlock" class="make-status"><i class="fa fa-lock"></i></a>
									<?php endif; ?>
                  </td>
                </tr>
                <?php endforeach; ?>
              <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
			</div>
		</div>
	</div>
</div>

<?php include_once 'views/table.js.php' ?>
<!-- Datatables -->
<script>
	var $tableMakes;
	$(document).ready(function() {
		try {
			$tableMakes = $('#table-makes').DataTable({
				"order" : [[1, "asc"]],
				//"pageLength": 50
			});
		} catch(e) {
		}
	});

	function delete_make(makeid) {
		if (confirm("Are you sure delete selected make?")) {
			$.get("makes-all.php?action=delete&ID=" + makeid, function() {
				$tableMakes.row($("#make-" + makeid)).remove().draw();
			})
		}
	}

	function lock_make(makeid) {
		if (confirm("Are you sure lock selected make?\nLocked make can't login to our dashboard.")) {
			$.get("makes-all.php?action=lock&ID=" + makeid, function() {
				$statusObj = $("#make-" + makeid).find(".status-text");
				$statusObj.text("Disabeld");

				$linkObj = $("#make-" + makeid).find("a.make-status");
				$linkObj.attr('href', 'javascript:unlock_make(' + makeid + ')');
				$linkObj.attr('title', 'Click for unlock');
				$linkObj.find('i.fa').removeClass('fa-unlock').addClass('fa-lock');

				$tableMakes.draw();
			})
		}
	}

	function unlock_make(makeid) {
		if (confirm("Are you sure unlock selected make?")) {
			$.get("makes-all.php?action=unlock&ID=" + makeid, function() {
				$statusObj = $("#make-" + makeid).find(".status-text");
				$statusObj.text("Enabled");

				$linkObj = $("#make-" + makeid).find("a.make-status");
				$linkObj.attr('href', 'javascript:lock_make(' + makeid + ')');
				$linkObj.attr('title', 'Click for lock');
				$linkObj.find('i.fa').removeClass('fa-lock').addClass('fa-unlock');

				$tableMakes.draw();
			})
		}
	}
</script>
<!-- /Datatables -->
<?php
require ('views/footer.php');
