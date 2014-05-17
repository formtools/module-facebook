<?php
require_once("../../global/session_start.php");
require_once("../../global/smarty/plugins/function.edit_custom_field.php");
require_once("../../global/smarty/plugins/function.template_hook.php");
require_once("library.php");
$request = array_merge($_POST, $_GET);

$fb_id = (isset($request["id"])) ? $request["id"] : "";

// check the configuration ID is valid
if (empty($fb_id))
{
  header("location: response.php?message=invalid_id");
  exit;
}

$config_info = fb_get_form($fb_id);
$_SESSION["fb_id"] = $fb_id;

if (empty($config_info))
{
  header("location: response.php?message=invalid_id");
  exit;
}

$form_id = $config_info["form_id"];
$view_id = $config_info["view_id"];

$form_info      = ft_get_form($form_id);
$view_info      = ft_get_view($view_id);
$grouped_fields = ft_get_grouped_view_fields($view_id, "", $form_id);

$settings = ft_get_settings("", "core");
$field_types = ft_get_field_types(true);

$shared_resources_list = $settings["edit_submission_onload_resources"];
$shared_resources_array = explode("|", $shared_resources_list);
$shared_resources = "";
foreach ($shared_resources_array as $resource)
{
  $shared_resources .= ft_eval_smarty_string($resource, array("g_root_url" => $g_root_url)) . "\n";
}

// needed to trigger the appropriate hooks so the field type information is included
$g_smarty->assign("page", "admin_edit_submission");

// deserialize the list of error fields and pass them to the template
$serialized_error_fields = ft_get_module_settings("serialized_error_fields");
$pairs = explode("`", $serialized_error_fields);
$error_field_ids = array();
$rules = array();
foreach ($pairs as $pair)
{
  if (empty($pair))
    continue;

  list($field_id, $field_error) = explode("|", $pair);
  $error_field_ids[] = $field_id;

  $field_error = htmlspecialchars($field_error);

  // this is kludgy, but simple. I *should* extract the field name from $grouped_fields, but this is easier
  $form_field_info = ft_get_form_field($field_id);
  $field_name = $form_field_info["field_name"];
  $rules[] = "rules.push(\"required,$field_name,$field_error\")";
}

$rules_str = implode(";\n", $rules);
?>
<html>
<head>
  <?php
  smarty_function_template_hook(array("location" => "head_top"), $g_smarty);
  ?>
  <script>
  //<![CDATA[
  var g = {
    root_url:       "<?php echo $g_root_url?>",
    error_colours:  ["ffbfbf", "ffb5b5"],
    notify_colours: ["c6e2ff", "97c7ff"],
    js_debug:       false
  }
  //]]>
  </script>
  <link href="<?php echo $g_root_url?>/themes/default/css/smoothness/jquery-ui-1.8.6.custom.css" rel="stylesheet" type="text/css"/>
  <script src="<?php echo $g_root_url?>/global/scripts/jquery.js"></script>
  <script src="<?php echo $g_root_url?>/themes/default/scripts/jquery-ui-1.8.6.custom.min.js"></script>
  <script src="<?php echo $g_root_url?>/global/scripts/general.js?v=2_1_0"></script>
  <script src="<?php echo $g_root_url?>/global/scripts/rsv.js?v=2_1_0"></script>
  <link href="global/css/facebook_styles.css" rel="stylesheet" type="text/css" />
  <script src="<?php echo $g_root_url?>/global/scripts/field_types.php"></script>
  <link rel="stylesheet" href="<?php echo $g_root_url?>/global/css/field_types.php" type="text/css" />
  <?php
  echo $shared_resources;

  smarty_function_template_hook(array("location" => "head_bottom"), $g_smarty);
  ?>

  <script>
  rsv.customErrorHandler = function(f, errorInfo) {
    var has_errors = false;
    var error_strings = "<ul>";
    for (var i=0; i<errorInfo.length; i++) {
      $(errorInfo[i][0]).addClass("error");
      error_strings += "<li>" + errorInfo[i][1] + "</li>";
      has_errors = true;
    }
    error_strings += "</ul>";

    if (has_errors) {
      $("#error_dialog").html(error_strings);
      ft.create_dialog({
        dialog:     $("#error_dialog"),
        title:      "Please fix the following errors",
        popup_type: "error",
        buttons: [{
          text: "Close",
          click: function() {
            $(this).dialog("close");
          }
        }]
      });
      return false;
    }
    return true;
  }

  var rules = [];
  <?php echo $rules_str; ?>
  </script>
</head>
<body class="fbbody">

  <?php
  if ($config_info["status"] == "offline")
  {
    if (!empty($config_info["offline_form_page_title"]))
      echo "<h1>{$config_info["offline_form_page_title"]}</h1>";

    if (!empty($config_info["offline_form_page_content"]))
      echo "<div class=\"opening_text\">{$config_info["offline_form_page_content"]}</div>";
    ?>
  <br />

  <div class="ft_link">
    Form created by <a href="http://www.formtools.org" target="_blank">Form Tools</a>
  </div>

    <?php
  }
  else
  {
    if (!empty($config_info["form_title"]))
      echo "<h1>{$config_info["form_title"]}</h1>";

    if (!empty($config_info["opening_text"]))
      echo "<div class=\"opening_text\">{$config_info["opening_text"]}</div>";
  ?>

    <form action="<?php echo $g_root_url?>/process.php" method="post" name="edit_submission_form" enctype="multipart/form-data"
      onsubmit="return rsv.validate(this, rules)">
    <input type="hidden" name="form_tools_redirect_url" value="<?php echo $g_root_url?>/modules/facebook/thanks.php" />
      <input type="hidden" name="form_tools_form_id" id="form_id" value="<?php echo $form_id?>" />

      <?php
      $questions_column_width = $config_info["questions_column_width"];

      foreach ($grouped_fields as $curr_group)
      {
        $group  = $curr_group["group"];
        $fields = $curr_group["fields"];

        if (!empty($group["group_name"]))
        {
          echo "<div class=\"fbbluebox\">{$group["group_name"]}</div>";
        }

        if (count($fields) > 0)
        {
          echo "<table class=\"list_table\" cellpadding=\"1\" cellspacing=\"1\" border=\"0\" width=\"100%\">";
        }

        foreach ($fields as $curr_field)
        {
          $field_id = $curr_field["field_id"];

          $required_field = (in_array($field_id, $error_field_ids)) ? "<span class=\"req\">*</span>" : "";

          echo <<< END
            <tr>
              <td width="$questions_column_width" class="pad_left_small" valign="top">{$curr_field["field_title"]} {$required_field}</td>
              <td valign="top">
END;

          $params = array(
             "form_id"     => $form_id,
             "field_info"  => $curr_field,
            "field_types" => $field_types,
            "settings"    => $settings
          );
          smarty_function_edit_custom_field($params, $g_smarty);

          echo "</td></tr>";
        }

        if (count($fields) > 0)
        {
          echo "</table>";
        }
      }

      $submit_button_label = (!empty($config_info["submit_button_label"])) ? $config_info["submit_button_label"] : "Submit";
      ?>


      <p>
        <div class="ft_link">
          Form created by <a href="http://www.formtools.org" target="_blank">Form Tools</a>
        </div>
        <input type="submit" name="send" value="<?php echo $submit_button_label?>" />
      </p>

    </form>

    <?php
    if ($config_info["show_like_button"] == "yes") {
    ?>
    <hr size="1" />
    <iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo $config_info["facebook_form_url"]?>" scrolling="no" frameborder="0" style="height: 62px; width: 100%" allowTransparency="true"></iframe>
     <?php
    }
    ?>

  <div id="error_dialog" class="hidden"></div>

  <?php
  }
  ?>

</body>
</html>

