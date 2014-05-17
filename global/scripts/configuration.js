var fb_ns = {};
fb_ns.form_fields = {};


/**
 * Called on the Configure (Add) form page, ensuring that all the values are set to their default
 * values & states.
 */
fb_ns.init_configure_form_page = function(context) {
  $("#form_id").bind("change keyup", function() {
    fb_ns.select_form(this.value);
  });

  if (context == "add") {
    $("#view_id").attr("disabled", "disabled");
  }
}


/**
 * Called when the user selects a form from one of the dropdowns in the first column. It shows
 * the appropriate View content in the second column.
 */
fb_ns.select_form = function(form_id) {
  if (form_id == "") {
    $("#view_id")[0].options.length = 0;
    $("#view_id")[0].options[0] = new Option(g.messages["phrase_please_select_form"], "");
    $("#view_id").attr("disabled", "disabled");
    return false;
  } else {
    $("#view_id").attr("disabled", "");
    fb_ns.populate_view_dropdown("view_id", form_id);
  }

  // query the database for the complete list of form fields
  fb_ns.get_form_fields(form_id);
  return false;
}


/**
 * This function is called in the Add Form process, and on the Edit Form -> main tab. It dynamically
 * adds rows to the "Form URLs" section, letting the user add as many page URLs as their form contains.
 */
fb_ns.add_multi_page_form_page = function() {
  var curr_row = ++fb_ns.num_multi_page_form_pages;

  var li1 = $("<li class=\"col1 sort_col\"></li>");
  var li2 = $("<li class=\"col2\"><select name=\"field_ids[]\" class=\"fields\" disabled><option value=\"\">" + g.messages["phrase_please_select"] + "</option></select></li>");
  var li3 = $("<li class=\"col3 colN del\"></li>");
  var ul  = $("<ul></ul>").append(ft.group_nodes([li1, li2, li3]));

  var hidden_sort_field = $("<input type=\"hidden\" value=\"1\" class=\"sr_order\">");
  var clr = $("<div class=\"clear\"></div>");
  var row_group  = $("<div class=\"row_group\"></div>").append(ft.group_nodes([hidden_sort_field, ul, clr]));

  var html = sortable_ns.get_sortable_row_markup({row_group: row_group, is_grouped: false });

  $(".visualization_field_list .rows").append(html);
  sortable_ns.reorder_rows($(".visualization_field_list"), true);

  return false;
}


/**
 * Populates a dropdown element with a list of Views including a "Please Select" default
 * option.
 */
fb_ns.populate_view_dropdown = function(element_id, form_id) {
  var form_index = null;
  for (var i=0; i<page_ns.form_views.length; i++) {
    if (form_id == page_ns.form_views[i][0]) {
      form_index = i;
    }
  }

  $("#" + element_id)[0].options.length = 0;
  $("#" + element_id)[0].options[0] = new Option(g.messages["phrase_please_select"], "");

  for (var i=0; i<page_ns.form_views[form_index][1].length; i++) {
    var view_id   = page_ns.form_views[form_index][1][i][0];
    var view_name = page_ns.form_views[form_index][1][i][1];
    $("#" + element_id)[0].options[i+1] = new Option(view_name, view_id);
  }
}


/**
 * This Ajax function queries the database for a list of form fields - NOT VIEW FIELDS! - to populate the
 * email, username and password dropdowns. It requests the form fields rather than View fields because it's
 * entirely possible the administrator will want the user to be able to sign up using a certain username &
 * password, but not want them to be able to edit those values. Hence: we display ALL form fields, regardless
 * of View.
 *
 * Couple of additional things to note: (a) it shows a "loading" icon while executing, to let the user know
 * something is happening; (b) it stores all View fields in memory in case the user flips back and forth
 * between Forms, eliminating unnecessary database calls.
 */
fb_ns.get_form_fields = function(form_id) {
  if (form_id == "") {
    return false;
  }

  if (typeof fb_ns.form_fields["form_" + form_id] != 'undefined') {
    var form_info = fb_ns.form_fields.get("form_" + form_id);
    if (!form_info.is_loaded) {
      return;
    }
    fb_ns.populate_field_dropdowns(form_id);
  } else {
    // make a note of the fact that we're loading the fields for this form
    fb_ns.form_fields["form_" + form_id] = { is_loaded: false };

    $("#loading_icon").show();
    var url = g.root_url + "/modules/facebook/global/code/actions.php?action=get_form_fields&form_id=" + form_id;
    $.ajax({
      url:      url,
      type:     "get",
      dataType: "json",
      success:  fb_ns.process_json_field_data,
      error:    ft.error_handler
    });
  }
}


/**
 * This function is passed the result of the database query for the View fields. It populates fb_ns.view_fields
 * with the View field info.
 */
fb_ns.process_json_field_data = function(data) {
  var form_id = data.form_id;

  var form_info = fb_ns.form_fields["form_" + form_id];
  form_info.fields = data.fields;
  form_info.is_loaded = true;
  fb_ns.form_fields["form_" + form_id] = form_info;

  // now, if the form is still selected, update the field list
  var selected_form_id = $("#form_id").val();
  $("#loading_icon").hide();

  if (selected_form_id == form_id) {
//    fb_ns.populate_field_dropdowns(form_id);
  }
}


fb_ns.populate_field_dropdowns = function(form_id) {
  var form_info = fb_ns.form_fields["form_" + form_id];
  var fields = form_info.fields;

  var options = "<option value=\"\">" + g.messages["phrase_please_select"] + "</option>";
  for (var i=0; i<fields.length; i++) {
    var field_id    = fields[i][0];
    var field_title = fields[i][1];
    options += "<option value=\"" + field_id + "\">" + field_title + "</option>";
  }
  $(".fields, #single_field_id").html(options).removeAttr("disabled");
}



