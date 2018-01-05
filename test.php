<?php
$page_title = "Test APIs";
$page_slug = "test-apis";
require ('library/admin_application_top.php');

require ('views/header.php');
?>

<style>
  #testApiLabel {
    font-style: italic;
  }

  #testApiList button {
    text-align: left;
  }	
</style>

<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="row">
      <div class="col-sm-2">
        <div class="x_panel">
          <div class="x_title">
            <h2>APIs</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div class="btn-group-vertical" id="testApiList">
              <?php
              $apis = array(
                  "signup" => "SignUp",
                  "verify_email" => "Verify Email",
                  "signin" => "SignIn",
                  "forgot_pwd" => "Forgot Password",
                  "reset_pwd" => "Reset Password",
                  "change_pwd" => "Change Password",
                  "add_code" => "Add Code",
                  "add_comment" => "Add Commnet",
                  "get_comments" => "Get Commnets",
                  "get_makes" => "Get Makes",
                  "get_models" => "Get Models",
                  "find_codes" => "Find Codes",
                  "oilservices_year" => "OilServices Year List",
                  "oilservices_make" => "OilServices Make List",
                  "oilservices_model" => "OilServices Model List",
                  "oilservices_list" => "OilServices List",
                  "oilservices_find" => "Find OilServices",
                  "oilservices_detail" => "OilServices Detail View",
                  "how_to_works" => "How To Works",
                  "get_promotion" => "Get Promotion",
                  "find_codes_v2" => "<small style='color:red'>new</small> Find Codes - V2",
                  "oilservices_detail_v2" => "<small style='color:red'>new</small> OilServices Detail View - V2",
				  "find_codes_str" => "<small style='color:red'>new</small> Find Codes -By String",
              );
              ?>
              <?php foreach ($apis as $api => $label): ?>
                <button class="btn btn-default" type="button" data-api="<?php echo $api; ?>" href="#testApiContent">
                  <?php echo $label; ?>
                </button>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>

      <div class="col-sm-10" id="testApiContent">
        <div class="x_panel margin-bottom-50">
          <div class="x_title">
            <h2>Test Input Form - <span id="testApiLabel">Call URL: <?php echo HTTP_CATALOG_SERVER; ?>webservice.php</span></h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div id="testApiForm">
              <p>
                Please select API
              </p>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-8">
            <div class="x_panel">
              <div class="x_title">
                <h2>Test Result</h2>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                <iframe name="testApiResult" id="testApiResult" style="width: 100%; height: 400px; border: 1px dotted #ccc;"></iframe>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="x_panel">
              <div class="x_title">
                <h2>Result Structure</h2>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                <strong>Success: {</strong>
                <br/>
                &nbsp;&nbsp;&nbsp;&nbsp;errorcode: 0
                <br/>
                &nbsp;&nbsp;&nbsp;&nbsp;message: (string)
                <br/>
                &nbsp;&nbsp;&nbsp;&nbsp;result: (object)
                <br/>
                }
                <br/>
                <br/>
                <br/>
                <strong>Error: {</strong>
                <br/>
                &nbsp;&nbsp;&nbsp;&nbsp;errorcode: (>0)
                <br/>
                &nbsp;&nbsp;&nbsp;&nbsp;errors: (array)
                <br/>
                }
                <br/>
                <br/>
                <br/>
                <strong>Error Codes:</strong>
                <br/>
                <ul style="list-style:decimal;">
                  <li>
                    ERRORCODE_INPUT_VALUES
                  </li>
                  <li>
                    ERRORCODE_USERID
                  </li>
                  <li>
                    ERRORCODE_PASSWORD
                  </li>
                  <li>
                    ERRORCODE_SECURITY
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /page content -->

<script>
  $(function () {
    $("#testApiList button").click(function () {
      $("#testApiList button").removeClass("selected");
      $(this).addClass("selected");
      $("#testApiResult").attr('src', "testapiforms/empty.php");

      var api = $(this).attr("data-api");
      $.post("testapiforms/" + api + ".php", {
        "api": api
      }, function (data) {
        $("#testApiForm").html(data);
        $("#form-" + api).find("#em-api").attr("readonly", "readonly");
        $("#form-" + api).find("#em-device").attr("readonly", "readonly");
      });
    });
  })
</script>

<?php
require ('views/footer.php');
