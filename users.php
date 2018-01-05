<?php
$page_title = "Users";
$page_slug = "users";
require ('library/admin_application_top.php');

$action = (isset($_GET['action']) ? $_GET['action'] : '');
$userid = (isset($_GET['ID']) ? $_GET['ID'] : '');

if ($action == 'delete') {
  $wpdb->delete(TABLE_USERS, array("ID" => $userid));
  $wpdb->update(TABLE_PRODUCTS, array("user_id" => NULL), array("user_id" => $userid));
  $wpdb->delete(TABLE_COMMENTS, array("user_id" => $userid));

  die('OK');
} elseif ($action == 'lock') {
  $wpdb->update(TABLE_USERS, array("status" => 0), array("ID" => $userid));

  die('OK');
} elseif ($action == 'unlock') {
  $wpdb->update(TABLE_USERS, array("status" => 2), array("ID" => $userid));

  die('OK');
} elseif ($action == 'list') {
  $draw = $_GET['draw'];
  $start = $_GET['start'];
  $length = $_GET['length'];
  $order = $_GET['order'][0];
  $search = $_GET['search']['value'];

  $status = $_GET['status'];

  $from = " FROM " . TABLE_USERS;

  $where = " WHERE 1=1";
  if ($search != '') {
    $where .= " AND (`username` LIKE '%" . strtolower($search) . "%' or `email` LIKE '%" . $search . "%')";
  }
  if ($status != 'all') {
    $where .= " AND `status`='" . $status . "'";
  }

  $all_count = $wpdb->get_var("SELECT COUNT(*)" . $from);
  $filtered_count = $wpdb->get_var("SELECT COUNT(*)" . $from . $where);

  $sql = "select *" . $from . $where;
  $sql .= " ORDER BY ";
  switch ($order['column']) {
    case 0 :
      $sql .= "`ID`";
      break;
    case 1 :
      $sql .= "username";
      break;
    case 2 :
      $sql .= "email";
      break;
    case 3 :
      $sql .= "`signed`";
      break;
    case 4 :
      $sql .= "`last_logined`";
      break;
    case 5 :
      $sql .= "`last_ip`";
      break;
    case 6 :
      $sql .= "`last_device_type`";
      break;
    case 9 :
      $sql .= "`status`";
      break;
    default :
      $sql .= "c.`ID`";
  }
  $sql .= " " . $order['dir'];
  $sql .= " LIMIT " . $start . ", " . $length;

  $users = $wpdb->get_results($sql);

  $returnData = array(
      'draw' => $draw,
      'recordsTotal' => $all_count,
      'recordsFiltered' => $filtered_count,
      'data' => array()
  );

  foreach ($users as $user) {
    $actions = '<a href="javascript:delete_user(' . $user->ID . ')" title="Delete"><i class="fa fa-remove"></i></a>';
    $actions .= '&nbsp;|&nbsp;';
    if ($user->status == 2) {
      $actions .= '<a href="javascript:lock_user(' . $user->ID . ')" title="Click for lock" class="user-status"><i class="fa fa-unlock"></i></a>';
    } else {
      $actions .= '<a href="javascript:unlock_user(' . $user->ID . ')" title="Click for unlock" class="user-status"><i class="fa fa-lock"></i></a>';
    }

    $returnData['data'][] = array(
        $user->ID,
        $user->username,
        '<a href="mailto:' . $user->email . '">' . $user->email . '</a>',
        $user->signed,
        $user->last_logined,
        $user->last_ip,
        $user->last_device_type,
        $wpdb->get_var("select count(*) from " . TABLE_PRODUCTS . " where user_id=" . $user->ID),
        $wpdb->get_var("select count(*) from " . TABLE_COMMENTS . " where user_id=" . $user->ID),
        '<span class="status-text">' . ($user->status == 2 ? 'Enabled' : ($user->status == 0 ? 'Disabled' : 'Non verified')) . '</span>',
        $actions
    );
  }

  die(json_encode($returnData));
}

require ('views/header.php');
?>

<style>
  span.user {
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
              Status: <select id="filter-status"><option value="all">All</option><option value="2">Enabled</option><option value="0">Disabled</option><option value="1">Non Verified</option></select>
            </div>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <table id="table-users" class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th width=50>ID</th>
                  <th>Username</th>
                  <th>Email</th>
                  <th>When<br/>signup?</th>
                  <th>Last<br/>Logined</th>
                  <th>Last<br/>IP</th>
                  <th>Last<br/>Device</th>
                  <th class="nosort">Added<br/>Products</th>
                  <th class="nosort">Added<br/>Comments</th>
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
  var $tableCommnets;
  $(document).ready(function () {
    try {
      $tableCommnets = $('#table-users').DataTable({
        "order": [[1, "asc"]],
        "processing": true,
        "serverSide": true,
        "ajax": {
          url: "users.php",
          type: "GET",
          data: function (d) {
            return $.extend({}, d, {
              "action": "list",
              "status": $("#filter-status").val()
            });
          }
        },
        'aoColumnDefs': [{
            'bSortable': false,
            'aTargets': ['nosort']
          }],
        "createdRow": function (row, data, index) {
          $(row).attr('id', "user-" + data[0]);
        },
        responsive: true,
        language: {
          searchPlaceholder: "Search by user"
        }
      });
    } catch (e) {
    }

    $("#filter-status").change(function () {
      reload_user(true);
    });

    $("#filter-posted").change(function () {
      reload_user(true);
    });
  });
  function delete_user(userid) {
    if (confirm("Are you sure delete selected user?")) {
      $.get("users.php?action=delete&ID=" + userid, function () {
        reload_user(false);
      });
    }
  }

  function reload_user(resetPaging) {
    $tableCommnets.ajax.reload(function () {
    }, resetPaging);
  }

  function lock_user(userid) {
    if (confirm("Are you sure lock selected user?\nLocked user can't login to our dashboard.")) {
      $.get("users.php?action=lock&ID=" + userid, function () {
        $statusObj = $("#user-" + userid).find(".status-text");
        $statusObj.text("Disabled");
        $linkObj = $("#user-" + userid).find("a.user-status");
        $linkObj.attr('href', 'javascript:unlock_user(' + userid + ')');
        $linkObj.attr('title', 'Click for unlock');
        $linkObj.find('i.fa').removeClass('fa-unlock').addClass('fa-lock');
        //$tableCommnets.draw();
      })
    }
  }

  function unlock_user(userid) {
    if (confirm("Are you sure unlock selected user?")) {
      $.get("users.php?action=unlock&ID=" + userid, function () {
        $statusObj = $("#user-" + userid).find(".status-text");
        $statusObj.text("Enabled");
        $linkObj = $("#user-" + userid).find("a.user-status");
        $linkObj.attr('href', 'javascript:lock_user(' + userid + ')');
        $linkObj.attr('title', 'Click for lock');
        $linkObj.find('i.fa').removeClass('fa-lock').addClass('fa-unlock');
        //$tableCommnets.draw();
      })
    }
  }
</script>
<!-- /Datatables -->
<?php
require ('views/footer.php');
