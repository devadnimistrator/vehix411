<?php
$page_title = "Oil Services";
$page_slug = "oilservices";
$page_sub_slug = "oilservices-all";
require ('library/admin_application_top.php');

$action = (isset($_GET['action']) ? $_GET['action'] : '');
$oilserviceid = (isset($_GET['ID']) ? $_GET['ID'] : '');

if ($action == 'delete') {
	$wpdb -> delete(TABLE_OILSERVICE_YEARS, array("oilservice_id" => $oilserviceid));
	$wpdb -> delete(TABLE_OILSERVICE_MODELS, array("oilservice_id" => $oilserviceid));
	$wpdb -> delete(TABLE_OILSERVICES, array("ID" => $oilserviceid));

	die('OK');
} elseif ($action == 'lock') {
	$wpdb -> update(TABLE_OILSERVICES, array("status" => 0), array("ID" => $oilserviceid));

	die('OK');
} elseif ($action == 'unlock') {
	$wpdb -> update(TABLE_OILSERVICES, array("status" => 1), array("ID" => $oilserviceid));

	die('OK');
} elseif ($action == 'list') {
	$draw = $_GET['draw'];
	$start = $_GET['start'];
	$length = $_GET['length'];
	$order = $_GET['order'][0];
	$search = $_GET['search']['value'];

	$where = "";
	if ($search != '') {
		$where = " WHERE LOWER(`header`) LIKE '%" . strtolower($search) . "%'";
	}

	$all_count = $wpdb -> get_var("SELECT COUNT(*) FROM " . TABLE_OILSERVICES);
	$filtered_count = $wpdb -> get_var("SELECT COUNT(*) FROM " . TABLE_OILSERVICES . $where);

	$sql = "SELECT * FROM " . TABLE_OILSERVICES . $where . " ORDER BY ";
	switch ($order['column']) {
		case 0 :
			$sql .= "`ID`";
			break;
		case 5 :
			$sql .= "`ebay_link`";
			break;
		case 6 :
			$sql .= "`youtube_link`";
			break;
		case 7 :
			$sql .= "`status`";
			break;
		default :
			$sql .= "`header`";
	}
	$sql .= " " . $order['dir'];
	$sql .= " LIMIT " . $start . ", " . $length;

	$oilservices = $wpdb -> get_results($sql);

	$returnData = array(
		'draw' => $draw,
		'recordsTotal' => $all_count,
		'recordsFiltered' => $filtered_count,
		'data' => array()
	);

	$all_years = array();
	for ($y = 1996; $y <= date('Y'); $y++) {
		$all_years[] = $y;
	}
	$all_years = implode(",", $all_years);

	foreach ($oilservices as $oilservice) {
		$oilservice_name = '<a href="oilservices-edit.php?ID=' . $oilservice -> ID . '" title="Edit"><span class="oilservice">' . $oilservice -> header . "</span></a><br/>";

		$year = "";
		if ($oilservice -> year) {
			if ($oilservice -> year == $all_years) {
				$year = "All";
			} else {
				$year = str_replace(",", ", ", $oilservice -> year);
			}
		}

		$ebay_info = "";
		if ($oilservice -> ebay_link) {
			$ebay_info .= '<i class="glyphicon glyphicon-link"></i>:&nbsp;';
			$ebay_info .= '<a href="' . $oilservice -> ebay_link . '" target="_blank_ebay">' . tep_cut_str($oilservice -> ebay_link, 40) . '</a>';
		}
		if ($oilservice -> ebay_keywords) {
			if ($ebay_info)
				$ebay_info .= '<br/>';
			$ebay_info .= '<i class="glyphicon glyphicon-tags"></i>:&nbsp;';
			$keywords = explode(",", $oilservice -> ebay_keywords);
			foreach ($keywords as $keyword) {
				$ebay_info .= ' <span class="label label-default">' . $keyword . '</span>';
			}
		}

		$youtube_info = "";
		if ($oilservice -> youtube_link) {
			$youtube_info .= '<i class="glyphicon glyphicon-link"></i>:&nbsp;';
			$youtube_info .= '<a href="' . $oilservice -> youtube_link . '" target="_blank_youtube">' . tep_cut_str($oilservice -> youtube_link, 40) . '</a>';
		}
		if ($oilservice -> youtube_keywords) {
			if ($youtube_info)
				$youtube_info .= '<br/>';
			$youtube_info .= '<i class="glyphicon glyphicon-tags"></i>:&nbsp;';
			$keywords = explode(",", $oilservice -> youtube_keywords);
			foreach ($keywords as $keyword) {
				$youtube_info .= ' <span class="label label-default">' . $keyword . '</span>';
			}
		}

		$actions = '<a href="oilservices-edit.php?ID=' . $oilservice -> ID . '" title="Edit"><i class="fa fa-edit"></i></a>';
		$actions .= '&nbsp;|&nbsp;';
		$actions .= '<a href="javascript:delete_oilservice(' . $oilservice -> ID . ')" title="Delete"><i class="fa fa-remove"></i></a>';
		$actions .= '&nbsp;|&nbsp;';
		if ($oilservice -> status == 1) {
			$actions .= '<a href="javascript:lock_oilservice(' . $oilservice -> ID . ')" title="Click for lock" class="oilservice-status"><i class="fa fa-unlock"></i></a>';
		} else {
			$actions .= '<a href="javascript:unlock_oilservice(' . $oilservice -> ID . ')" title="Click for unlock" class="oilservice-status"><i class="fa fa-lock"></i></a>';
		}

		$model_id = "";
		if ($oilservice -> model_id) {
			if ($wpdb -> get_var("SELECT COUNT(*) FROM " . TABLE_MODELS . " WHERE make_id='" . $oilservice -> make_id . "'") == count(explode(",", $oilservice -> model_id))) {
				$model_id = "All";
			} else {
				$models = $wpdb -> get_results("SELECT * FROM " . TABLE_MODELS . " WHERE `ID` IN (" . $oilservice -> model_id . ") ORDER BY `name`");
				foreach ($models as $model) {
					$model_id .= ($model_id == '' ? '' : ', ') . $model -> name;
				}
			}
		}

		$returnData['data'][] = array(
			$oilservice -> ID,
			$oilservice_name,
			$year,
			'<a href="makes-edit.php?ID=' . $oilservice -> make_id . '" class="label label-primary">' . $wpdb -> get_var("SELECT `name` FROM " . TABLE_MAKES . " WHERE `ID` = '" . $oilservice -> make_id . "'") . '</a>',
			$model_id,
			$ebay_info,
			$youtube_info,
			'<span class="status-text">' . ($oilservice -> status == 1 ? 'Enabled' : 'Disabled') . '</span>',
			$actions
		);
	}

	die(json_encode($returnData));
}

require ('views/header.php');
?>

<style>
	span.oilservice {
		color: #333;
		font-weight: bold;
	}
</style>

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
						<h2>Oil Services</h2>
            <div class="clearfix"></div>
					</div>
					<div class="x_content">
            <table id="table-oilservices" class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th width=0>ID</th>
                  <th>Oil Service</th>
                  <th width="12%" class="nosort">Years</th>
                  <th width="8%" class="nosort">Make</th>
                  <th width="12%" class="nosort">Models</th>
                  <th width="15%" class="nosort">Ebay</th>
                  <th width="15%" class="nosort">Youtube</th>
                  <th width="50">Status</th>
                  <th width=60 class="nosort">Actions</th>
                </tr>
              </thead>
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
	var $tableOilServices;
	$(document).ready(function() {
		try {
			$tableOilServices = $('#table-oilservices').DataTable({
				"order" : [[1, "asc"]],
				"processing" : true,
				"serverSide" : true,
				"ajax" : "oilservices-all.php?action=list",
				'aoColumnDefs' : [{
					'bSortable' : false,
					'aTargets' : ['nosort']
				}],
				"createdRow" : function(row, data, index) {
					$(row).attr('id', "oilservice-" + data[0]);
				},
				responsive : true,
				language : {
					searchPlaceholder : "Search by oilservice"
				}
			});
		} catch(e) {
		}
	});

	function delete_oilservice(oilserviceid) {
		if (confirm("Are you sure delete selected oilservice?")) {
			$.get("oilservices-all.php?action=delete&ID=" + oilserviceid, function() {
				$tableOilServices.draw();
				//$tableOilServices.row($("#oilservice-" + oilserviceid)).remove().draw();
			})
		}
	}

	function lock_oilservice(oilserviceid) {
		if (confirm("Are you sure lock selected oilservice?\nLocked oilservice can't login to our dashboard.")) {
			$.get("oilservices-all.php?action=lock&ID=" + oilserviceid, function() {
				$statusObj = $("#oilservice-" + oilserviceid).find(".status-text");
				$statusObj.text("Disabled");

				$linkObj = $("#oilservice-" + oilserviceid).find("a.oilservice-status");
				$linkObj.attr('href', 'javascript:unlock_oilservice(' + oilserviceid + ')');
				$linkObj.attr('title', 'Click for unlock');
				$linkObj.find('i.fa').removeClass('fa-unlock').addClass('fa-lock');

				//$tableOilServices.draw();
			})
		}
	}

	function unlock_oilservice(oilserviceid) {
		if (confirm("Are you sure unlock selected oilservice?")) {
			$.get("oilservices-all.php?action=unlock&ID=" + oilserviceid, function() {
				$statusObj = $("#oilservice-" + oilserviceid).find(".status-text");
				$statusObj.text("Actived");

				$linkObj = $("#oilservice-" + oilserviceid).find("a.oilservice-status");
				$linkObj.attr('href', 'javascript:lock_oilservice(' + oilserviceid + ')');
				$linkObj.attr('title', 'Click for lock');
				$linkObj.find('i.fa').removeClass('fa-lock').addClass('fa-unlock');

				//$tableOilServices.draw();
			})
		}
	}
</script>
<!-- /Datatables -->
<?php
require ('views/footer.php');
