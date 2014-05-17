<?php

require_once("../../global/library.php");
ft_init_module_page();
$request = array_merge($_POST, $_GET);
$num_configurations_per_page = 10;

if (isset($request["add"]))
{
  list($g_success, $g_message) = fb_add_form($request);
}
if (isset($request["delete"]))
{
  list($g_success, $g_message) = fb_delete_form($request["delete"]);
}


$page = ft_load_module_field("facebook_forms", "page", "page", 1);

$configured_forms = fb_get_forms($num_configurations_per_page, $page);
$results     = $configured_forms["results"];
$num_results = $configured_forms["num_results"];

$js = fb_get_form_view_mapping_js();

$page_vars = array();
$page_vars["results"] = $results;
$page_vars["num_results"] = $num_results;
$page_vars["js_messages"] = array("phrase_please_select", "phrase_please_select_form", "word_edit", "word_delete");
$page_vars["pagination"] = ft_get_page_nav($num_results, $num_configurations_per_page, $page);
$page_vars["head_string"] =<<< END
<script src="global/scripts/configuration.js"></script>
<link type="text/css" rel="stylesheet" href="global/css/styles.css">
END;

$page_vars["head_js"] =<<< END

var page_ns = {};
page_ns.delete_dialog = $("<div></div>");
page_ns.add_new_form_response = function(data) {
  console.log(data);
}

$js

page_ns.delete_configuration = function(fb_id) {
  ft.create_dialog({
    dialog:     page_ns.delete_dialog,
    title:      "{$LANG["phrase_please_confirm"]}",
    content:    "{$L["confirm_delete_configuration"]}",
    popup_type: "warning",
    buttons: [{
      text:  "{$LANG["word_yes"]}",
      click: function() {
        window.location = "index.php?delete=" + fb_id;
      }
    },
    {
      text:  "{$LANG["word_no"]}",
      click: function() {
        $(this).dialog("close");
      }
    }]
  });
}

$(function() {
  $(".canvas_url_link").bind("click", function() {
    var fb_id = $(this).closest("tr").find(".fb_id").html();
    $("#canvas_url_field").val("$g_root_url/modules/facebook/form.php?id=" + fb_id);
    ft.create_dialog({
      title: "{$L["phrase_facebook_canvas_url"]}",
      dialog: $("#canvas_url"),
      min_width: 650,
      open: function() {
        $("#canvas_url_field").select();
      },
      buttons: [{
        text:  "{$LANG["word_close"]}",
        click: function() {
          $(this).dialog("close");
        }
      }]
    });
    return false;
  });

  $("#configure_form_button").bind("click", function() {
    ft.create_dialog({
      title: "{$L["phrase_configure_new_facebook_form"]}",
      dialog: $("#configure_form_dialog"),
      min_width: 600,
      buttons: [{
        text:  "{$LANG["word_add"]}",
        click: function() {
          var form_id = $("#form_id").val();
          var view_id = $("#view_id").val();

          if (!form_id || !view_id) {
            alert("{$L["validation_missing_form_or_view_id"]}");
          } else {
            ft.dialog_activity_icon(this, "show");
            $.ajax({
              url: g.root_url + "/modules/facebook/global/code/actions.php",
              data: { action: "add_new_form", view_id: view_id, form_id: form_id },
              type:     "POST",
              dataType: "json",
              success:  function(data) {
                if (data.success) {
                  window.location = "edit.php?page=main&fb_id=" + data.fb_id;
                } else {
                  ft.dialog_activity_icon($("#configure_form_dialog"), "hide");
                  $("#configure_form_dialog").dialog("close");
                  ft.display_message("ft_message", 0, data.message);
                }
              }
            });
          }
        }
      },
      {
        text:  "{$LANG["word_close"]}",
        click: function() {
          $(this).dialog("close");
        }
      }]
    });
  });

  fb_ns.init_configure_form_page("add");
});

END;

ft_display_module_page("templates/index.tpl", $page_vars);
