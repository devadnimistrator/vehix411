<?php
$page_title = "Comments";
$page_slug = "comments";
require ('library/admin_application_top.php');

$action = (isset($_GET['action']) ? $_GET['action'] : '');
$commentid = (isset($_GET['ID']) ? $_GET['ID'] : '');

if ($action == 'delete') {
  $wpdb->delete(TABLE_COMMENTS, array("ID" => $commentid));

  die('OK');
} elseif ($action == 'lock') {
  $wpdb->update(TABLE_COMMENTS, array("status" => 0), array("ID" => $commentid));

  die('OK');
} elseif ($action == 'unlock') {
  $wpdb->update(TABLE_COMMENTS, array("status" => 1), array("ID" => $commentid));

  die('OK');
} elseif ($action == 'list') {
  $draw = $_GET['draw'];
  $start = $_GET['start'];
  $length = $_GET['length'];
  $order = $_GET['order'][0];
  $search = $_GET['search']['value'];

  $status = $_GET['status'];

  $from = " FROM ".TABLE_COMMENTS . " as c join " . TABLE_PRODUCTS . " as p on c.product_id=p.`ID` join " . TABLE_USERS . " as u on c.user_id=u.`ID`";
  
  $where = " WHERE 1=1";
  if ($search != '') {
    $where .= " AND (p.`code` LIKE '%" . strtolower($search) . "%' or u.`username` LIKE '%" . $search . "%')";
  }
  if ($status != 'all') {
    $where .= " AND c.`status`='" . $status . "'";
  }

  $all_count = $wpdb->get_var("SELECT COUNT(*)" . $from);
  $filtered_count = $wpdb->get_var("SELECT COUNT(*)" . $from . $where);

  $sql = "select c.*, p.code, u.username" . $from . $where;
  $sql .= " ORDER BY ";
  switch ($order['column']) {
    case 0 :
      $sql .= "c.`ID`";
      break;
    case 1 :
      $sql .= "p.code";
      break;
    case 2 :
      $sql .= "c.comment";
      break;
    case 3 :
      $sql .= "c.rating";
      break;
    case 4 :
      $sql .= "c.`posted`";
      break;
    case 5 :
      $sql .= "u.`username`";
      break;
    case 6 :
      $sql .= "c.`status`";
      break;
    default :
      $sql .= "c.`ID`";
  }
  $sql .= " " . $order['dir'];
  $sql .= " LIMIT " . $start . ", " . $length;

  $comments = $wpdb->get_results($sql);

  $returnData = array(
      'draw' => $draw,
      'recordsTotal' => $all_count,
      'recordsFiltered' => $filtered_count,
      'data' => array()
  );

  foreach ($comments as $comment) {
    $actions = '<a href="javascript:delete_comment(' . $comment->ID . ')" title="Delete"><i class="fa fa-remove"></i></a>';
    $actions .= '&nbsp;|&nbsp;';
    if ($comment->status == 1) {
      $actions .= '<a href="javascript:lock_comment(' . $comment->ID . ')" title="Click for lock" class="comment-status"><i class="fa fa-unlock"></i></a>';
    } else {
      $actions .= '<a href="javascript:unlock_comment(' . $comment->ID . ')" title="Click for unlock" class="comment-status"><i class="fa fa-lock"></i></a>';
    }

    $returnData['data'][] = array(
        $comment->ID,
        $comment->code,
        $comment->comment,
        $comment->rating,
        $comment->posted,
        $comment->username,
        '<span class="status-text">' . ($comment->status == 1 ? 'Enabled' : 'Disabled') . '</span>',
        $actions
    );
  }

  die(json_encode($returnData));
}

require ('views/header.php');
?>

<style>
  span.comment {
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
            </div>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <table id="table-comments" class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th width=50>ID</th>
                  <th width="70">Code</th>
                  <th>Comment</th>
                  <th width="40">Rating</th>
                  <th width="120" class="nosort">Posted</th>
                  <th width="100" class="nosort">By</th>
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
      $tableCommnets = $('#table-comments').DataTable({
        "order": [[1, "asc"]],
        "processing": true,
        "serverSide": true,
        "ajax": {
          url: "comments.php",
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
          $(row).attr('id', "comment-" + data[0]);
        },
        responsive: true,
        language: {
          searchPlaceholder: "Search by comment"
        }
      });
    } catch (e) {
    }

    $("#filter-status").change(function () {
      reload_comment(true);
    });

    $("#filter-posted").change(function () {
      reload_comment(true);
    });
  });
  function delete_comment(commentid) {
    if (confirm("Are you sure delete selected comment?")) {
      $.get("comments.php?action=delete&ID=" + commentid, function () {
        reload_comment(false);
      });
    }
  }

  function reload_comment(resetPaging) {
    $tableCommnets.ajax.reload(function () {
    }, resetPaging);
  }

  function lock_comment(commentid) {
    if (confirm("Are you sure lock selected comment?\nLocked comment can't login to our dashboard.")) {
      $.get("comments.php?action=lock&ID=" + commentid, function () {
        $statusObj = $("#comment-" + commentid).find(".status-text");
        $statusObj.text("Disabled");
        $linkObj = $("#comment-" + commentid).find("a.comment-status");
        $linkObj.attr('href', 'javascript:unlock_comment(' + commentid + ')');
        $linkObj.attr('title', 'Click for unlock');
        $linkObj.find('i.fa').removeClass('fa-unlock').addClass('fa-lock');
        //$tableCommnets.draw();
      })
    }
  }

  function unlock_comment(commentid) {
    if (confirm("Are you sure unlock selected comment?")) {
      $.get("comments.php?action=unlock&ID=" + commentid, function () {
        $statusObj = $("#comment-" + commentid).find(".status-text");
        $statusObj.text("Enabled");
        $linkObj = $("#comment-" + commentid).find("a.comment-status");
        $linkObj.attr('href', 'javascript:lock_comment(' + commentid + ')');
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
