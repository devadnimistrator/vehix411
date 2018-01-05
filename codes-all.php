<?php
$page_title = "Codes";
$page_slug = "codes";
$page_sub_slug = "codes-all";
require ('library/admin_application_top.php');

$action = (isset($_GET['action']) ? $_GET['action'] : '');
$codeid = (isset($_GET['ID']) ? $_GET['ID'] : '');

if ($action == 'delete') {
  $wpdb->delete(TABLE_PRODUCTS, array("ID" => $codeid));
  $wpdb->delete(TABLE_COMMENTS, array("product_id" => $codeid));

  die('OK');
} elseif ($action == 'lock') {
  $wpdb->update(TABLE_PRODUCTS, array("status" => 0), array("ID" => $codeid));

  die('OK');
} elseif ($action == 'unlock') {
  $wpdb->update(TABLE_PRODUCTS, array("status" => 1), array("ID" => $codeid));

  die('OK');
} elseif ($action == 'list') {
  $draw = $_GET['draw'];
  $start = $_GET['start'];
  $length = $_GET['length'];
  $order = $_GET['order'][0];
  $search = $_GET['search']['value'];
  $status = $_GET['status'];
  $posted = $_GET['posted'];

  $where = " WHERE 1=1";
  if ($search != '') {
    $where .= " AND p.`code` LIKE '%" . strtolower($search) . "%'";
  }
  if ($status != 'all') {
    $where .= " AND p.`status`='" . $status . "'";
  }
  if ($posted == 'user') {
    $where .= " AND p.`user_id` IS NOT NULL";
  } elseif ($posted == 'admin') {
    $where .= " AND p.`user_id` IS NULL";
  }

  $all_count = $wpdb->get_var("SELECT COUNT(*) FROM " . TABLE_PRODUCTS . " as p");
  $filtered_count = $wpdb->get_var("SELECT COUNT(*) FROM " . TABLE_PRODUCTS . " as p" . $where);

  $sql = "SELECT * FROM (SELECT p.*, count(c.`ID`) as comments, avg(IFNULL(c.rating, 0)) as avg_rating FROM "
      . TABLE_PRODUCTS . " as p LEFT JOIN " . TABLE_COMMENTS . " as c on p.`ID`=c.product_id " . $where . " GROUP BY p.`ID`) a";
  $sql .= " ORDER BY ";
  switch ($order['column']) {
    case 0 :
      $sql .= "a.`ID`";
      break;
    case 2 :
      $sql .= "a.comments";
      break;
    case 3 :
      $sql .= "a.avg_rating";
      break;
    case 4 :
      $sql .= "a.`year`";
      break;
    case 5 :
      $sql .= "a.`make_id`";
      break;
    case 6 :
      $sql .= "a.`ebay_link`";
      break;
    case 7 :
      $sql .= "a.`youtube_link`";
      break;
    case 8 :
      $sql .= "a.`status`";
      break;
    default :
      $sql .= "a.`code`";
  }
  $sql .= " " . $order['dir'];
  $sql .= " LIMIT " . $start . ", " . $length;

  $codes = $wpdb->get_results($sql);

  $returnData = array(
      'draw' => $draw,
      'recordsTotal' => $all_count,
      'recordsFiltered' => $filtered_count,
      'data' => array()
  );

  $makes = $wpdb->get_results("SELECT `ID` FROM " . TABLE_MAKES);
  $all_makes = array();
  foreach ($makes as $make) {
    $all_makes[] = $make->ID;
  }
  $all_makes = implode(",", $all_makes);

  $all_years = array();
  for ($y = 1996; $y <= date('Y'); $y++) {
    $all_years[] = $y;
  }
  $all_years = implode(",", $all_years);

  foreach ($codes as $code) {
    $make_id = "";
    if ($code->make_id) {
      if ($all_makes == $code->make_id) {
        $make_id = "All";
      } else {
        $makes = $wpdb->get_results("SELECT * FROM " . TABLE_MAKES . " WHERE `ID` IN (" . $code->make_id . ") ORDER BY `name`");
        foreach ($makes as $make) {
          $make_id .= '<a href="makes-edit.php?ID=' . $make->ID . '" class="label label-primary">' . $make->name . '</a> ';
        }
      }
    }

    $year = "";
    if ($code->year) {
      if ($code->year == $all_years) {
        $year = "All";
      } else {
        $year = str_replace(",", ", ", $code->year);
      }
    }

    $code_name = '<a href="codes-edit.php?ID=' . $code->ID . '" title="Edit"><span class="code">' . strtoupper($code->code) . "</span></a><br/>";
    if ($code->user_id) {
      $userinfo = $wpdb->get_row("select * from " . TABLE_USERS . " where `id`='" . $code->user_id . "'");
      $code_name .= 'Posted by <a href="mailto:' . $userinfo->email . '" ><span class="code">' . $userinfo->username . "</span></a> in " . $code->posted . "<br/>";
    }
    $code_name .= $code->description;
    /* if ($code -> autocodes_link) {
      $code_name .= '</a>';
      } */

    $ebay_info = "";
    if ($code->ebay_link) {
      $ebay_info .= '<i class="glyphicon glyphicon-link"></i>:&nbsp;';
//      $ebay_info .= '<a href="' . $code->ebay_link . '" target="_blank_ebay">' . tep_cut_str($code->ebay_link, 20) . '</a>';
      $ebay_info .= '<a href="' . $code->ebay_link . '" target="_blank_ebay" title="' . $code->ebay_link . '">ebay</a>';
    }
    if ($code->ebay_keywords) {
      if ($ebay_info)
        $ebay_info .= '<br/>';
      $ebay_info .= '<i class="glyphicon glyphicon-tags"></i>:&nbsp;';
      $keywords = explode(",", $code->ebay_keywords);
      foreach ($keywords as $keyword) {
        $ebay_info .= ' <span class="label label-default">' . $keyword . '</span>';
      }
    }

    $youtube_info = "";
    if ($code->youtube_link) {
      $youtube_info .= '<i class="glyphicon glyphicon-link"></i>:&nbsp;';
//      $youtube_info .= '<a href="' . $code->youtube_link . '" target="_blank_youtube">' . tep_cut_str($code->youtube_link, 20) . '</a>';
      $youtube_info .= '<a href="' . $code->youtube_link . '" target="_blank_youtube" title="' . $code->youtube_link . '">youtube</a>';
    }
    if ($code->youtube_keywords) {
      if ($youtube_info)
        $youtube_info .= '<br/>';
      $youtube_info .= '<i class="glyphicon glyphicon-tags"></i>:&nbsp;';
      $keywords = explode(",", $code->youtube_keywords);
      foreach ($keywords as $keyword) {
        $youtube_info .= ' <span class="label label-default">' . $keyword . '</span>';
      }
    }

    $actions = '<a href="codes-edit.php?ID=' . $code->ID . '" title="Edit"><i class="fa fa-edit"></i></a>';
    $actions .= '&nbsp;|&nbsp;';
    $actions .= '<a href="javascript:delete_code(' . $code->ID . ')" title="Delete"><i class="fa fa-remove"></i></a>';
    $actions .= '&nbsp;|&nbsp;';
    if ($code->status == 1) {
      $actions .= '<a href="javascript:lock_code(' . $code->ID . ')" title="Click for lock" class="code-status"><i class="fa fa-unlock"></i></a>';
    } else {
      $actions .= '<a href="javascript:unlock_code(' . $code->ID . ')" title="Click for unlock" class="code-status"><i class="fa fa-lock"></i></a>';
    }

    $returnData['data'][] = array(
        $code->ID,
        $code_name,
        $code->comments,
        round($code->avg_rating, 1),
        $year,
        $make_id,
        $ebay_info,
        $youtube_info,
        '<span class="status-text">' . ($code->status == 1 ? 'Enabled' : 'Disabled') . '</span>',
        $actions
    );
  }

  die(json_encode($returnData));
}

require ('views/header.php');
?>

<style>
  span.code {
    color: #333;
    font-weight: bold;
  }
</style>

<!-- page content -->
<div class="right_col" role="main">
  <div class="">

    <div class="page-title">
      <div class="title_left">
        <h3><?php echo $page_title ?></h3>
      </div>
    </div>
    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">						
            <h2>Codes</h2>

            <div class="panel_toolbox">
              Status: <select id="filter-status"><option value="all">All</option><option value="1">Enabled</option><option value="0">Disabled</option></select>
              &nbsp;&nbsp;&nbsp;
              Posted by: <select id="filter-posted"><option value="">All</option><option value="user">User</option><option value="admin">Admin</option></select>
            </div>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <table id="table-codes" class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th width=0>ID</th>
                  <th>Code</th>
                  <th width="70">Comments</th>
                  <th width="40">Rating</th>
                  <!--th>Description</th-->
                  <!--th>Posible Corsee</th-->
                  <th width="10%" class="nosort">Years</th>
                  <th width="10%" class="nosort">Makes</th>
                  <th width="10%" class="nosort">Ebay</th>
                  <th width="10%" class="nosort">Youtube</th>
                  <th width="40">Status</th>
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
  var $tableCodes;
  $(document).ready(function () {
    try {
      $tableCodes = $('#table-codes').DataTable({
        "order": [[1, "asc"]],
        "processing": true,
        "serverSide": true,
        "ajax": {
          url: "codes-all.php",
          type: "GET",
          data: function (d) {
            return $.extend({}, d, {
              "action": "list",
              "status": $("#filter-status").val(),
              "posted": $("#filter-posted").val()
            });
          }
        },
        'aoColumnDefs': [{
            'bSortable': false,
            'aTargets': ['nosort']
          }],
        "createdRow": function (row, data, index) {
          $(row).attr('id', "code-" + data[0]);
        },
        responsive: true,
        language: {
          searchPlaceholder: "Search by code"
        }
      });
    } catch (e) {
    }

    $("#filter-status").change(function () {
      reload_code(true);
    });

    $("#filter-posted").change(function () {
      reload_code(true);
    });
  });
  function delete_code(codeid) {
    if (confirm("Are you sure delete selected code?")) {
      $.get("codes-all.php?action=delete&ID=" + codeid, function () {
        reload_code(false);//$tableCodes.draw();
        //$tableCodes.row($("#code-" + codeid)).remove().draw();
      });
    }
  }

  function reload_code(resetPaging) {
    $tableCodes.ajax.reload(function () {
    }, resetPaging);
  }

  function lock_code(codeid) {
    if (confirm("Are you sure lock selected code?\nLocked code can't login to our dashboard.")) {
      $.get("codes-all.php?action=lock&ID=" + codeid, function () {
        $statusObj = $("#code-" + codeid).find(".status-text");
        $statusObj.text("Disabled");
        $linkObj = $("#code-" + codeid).find("a.code-status");
        $linkObj.attr('href', 'javascript:unlock_code(' + codeid + ')');
        $linkObj.attr('title', 'Click for unlock');
        $linkObj.find('i.fa').removeClass('fa-unlock').addClass('fa-lock');
        //$tableCodes.draw();
      })
    }
  }

  function unlock_code(codeid) {
    if (confirm("Are you sure unlock selected code?")) {
      $.get("codes-all.php?action=unlock&ID=" + codeid, function () {
        $statusObj = $("#code-" + codeid).find(".status-text");
        $statusObj.text("Enabled");
        $linkObj = $("#code-" + codeid).find("a.code-status");
        $linkObj.attr('href', 'javascript:lock_code(' + codeid + ')');
        $linkObj.attr('title', 'Click for lock');
        $linkObj.find('i.fa').removeClass('fa-lock').addClass('fa-unlock');
        //$tableCodes.draw();
      })
    }
  }
</script>
<!-- /Datatables -->
<?php
require ('views/footer.php');
