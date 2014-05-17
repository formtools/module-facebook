<?php

function fb_get_forms($num_per_page, $page_num = 1)
{
  global $g_table_prefix;

  if ($num_per_page == "all")
  {
    $query = mysql_query("
      SELECT *
      FROM   {$g_table_prefix}module_facebook_forms
      ORDER BY fb_id
        ");
  }
  else
  {
    // determine the offset
    if (empty($page_num)) { $page_num = 1; }
    $first_item = ($page_num - 1) * $num_per_page;

    $query = mysql_query("
      SELECT *
      FROM   {$g_table_prefix}module_facebook_forms
      ORDER BY fb_id
      LIMIT $first_item, $num_per_page
        ") or handle_error(mysql_error());
  }

  $count_query = mysql_query("SELECT count(*) as c FROM {$g_table_prefix}module_facebook_forms");
  $count_hash = mysql_fetch_assoc($count_query);
  $num_results = $count_hash["c"];

  $infohash = array();
  while ($field = mysql_fetch_assoc($query))
  {
    $infohash[] = $field;
  }

  $return_hash["results"] = $infohash;
  $return_hash["num_results"] = $num_results;

  return $return_hash;
}


function fb_get_form($fb_id)
{
  global $g_table_prefix;

  $query = mysql_query("SELECT * FROM {$g_table_prefix}module_facebook_forms WHERE fb_id = $fb_id");
  $result = mysql_fetch_assoc($query);

  return $result;
}


/**
 * This function returns a string of JS containing the list of forms and form Views in the page_ns
 * namespace.
 *
 * Its tightly coupled with the calling page, which is kind of crumby; but it can be refactored later
 * as the need arises.
 */
function fb_get_form_view_mapping_js()
{
  $forms = ft_get_forms();

  $js_rows = array();
  $js_rows[] = "var page_ns = {}";
  $js_rows[] = "page_ns.forms = []";
  $views_js_rows = array("page_ns.form_views = []");

  // convert ALL form and View info into Javascript, for use in the page
  foreach ($forms as $form_info)
  {
    // ignore those forms that aren't set up
    if ($form_info["is_complete"] == "no")
      continue;

    $form_id = $form_info["form_id"];
    $form_name = htmlspecialchars($form_info["form_name"]);
    $js_rows[] = "page_ns.forms.push([$form_id, \"$form_name\"])";

    $form_views = ft_get_views($form_id, "all");

    $v = array();
    foreach ($form_views["results"] as $form_view)
    {
      $view_id   = $form_view["view_id"];
      $view_name = htmlspecialchars($form_view["view_name"]);
      $v[] = "[$view_id, \"$view_name\"]";
    }
    $views = join(",", $v);

    $views_js_rows[] = "page_ns.form_views.push([$form_id,[$views]])";
  }

  $js = array_merge($js_rows, $views_js_rows);
  $js = join(";\n", $js);

  return $js;
}


/**
 * This creates a new form configuration to be added to Facebook.
 */
function fb_add_form($info)
{
  global $g_table_prefix, $L;

  $info = ft_sanitize($info);

  $form_id = $info["form_id"];
  $view_id = $info["view_id"];

  $form_info = ft_get_form($form_id);
  $form_name = ft_sanitize($form_info["form_name"]);

  $result = mysql_query("
    INSERT INTO {$g_table_prefix}module_facebook_forms (status, form_id, view_id,
      offline_form_page_title, offline_form_page_content, thankyou_page_title, thankyou_page, form_title,
      show_like_button, questions_column_width)
    VALUES ('online', $form_id, $view_id, 'Sorry!', 'This form is now offline.', 'Thanks!', 'Thanks for submitting the form. Your submission has been processed.',
      '$form_name', 'no', 180)
  ");

  if ($result)
  {
    return array(
      "success" => true,
      "message" => "",
      "fb_id"   => mysql_insert_id()
    );
  }
  else
  {
    return array(
      "success" => false,
      "message" => $L["notify_form_not_configured"]
    );
  }
}


function fb_update_form($fb_id, $tab, $info)
{
  global $g_table_prefix, $L;

  $info = ft_sanitize($info);

  $success = "";
  $message = "";
  switch ($tab)
  {
    case "main":
      $status  = isset($info["status"]) ? $info["status"] : "online";
      $form_id = $info["form_id"];
      $view_id = $info["view_id"];

      $result = mysql_query("
        UPDATE {$g_table_prefix}module_facebook_forms
        SET    status = '$status',
               form_id = $form_id,
               view_id = $view_id
        WHERE fb_id = $fb_id
      ") or die(mysql_error());

      if ($result)
      {
        $success = true;
        $message = $L["notify_form_updated"];
      }
      else
      {
        $success = true;
        $message = $L["notify_form_not_updated"];
      }
      break;

    case "labels":
      $form_title   = $info["form_title"];
      $opening_text = $info["opening_text"];
      $submit_button_label = $info["submit_button_label"];
      $offline_form_page_title   = $info["offline_form_page_title"];
      $offline_form_page_content = $info["offline_form_page_content"];
      $thankyou_page_title = $info["thankyou_page_title"];
      $thankyou_page = $info["thankyou_page"];

      $result = mysql_query("
        UPDATE {$g_table_prefix}module_facebook_forms
        SET    form_title = '$form_title',
               opening_text = '$opening_text',
               submit_button_label = '$submit_button_label',
               offline_form_page_title = '$offline_form_page_title',
               offline_form_page_content = '$offline_form_page_content',
               thankyou_page_title = '$thankyou_page_title',
               thankyou_page = '$thankyou_page'
        WHERE fb_id = $fb_id
      ") or die(mysql_error());

      if ($result)
      {
        $success = true;
        $message = $L["notify_form_updated"];
      }
      else
      {
        $success = true;
        $message = $L["notify_form_not_updated"];
      }
      break;

    case "validation":
      $error_fields = array();
      $field_ids = isset($info["field_ids"]) ? $info["field_ids"] : array();

      foreach ($field_ids as $field_id)
      {
        $error_fields[] = "$field_id|{$info["field_{$field_id}_error_string"]}";
      }

      $serialized_str = implode("`", $error_fields);
      ft_set_module_settings(array("serialized_error_fields" => $serialized_str));
      $success = true;
      $message = $L["notify_form_not_updated"];
      break;

    case "advanced":
      $show_like_button       = isset($info["show_like_button"]) ? $info["show_like_button"] : "yes";
      $questions_column_width = isset($info["questions_column_width"]) ? $info["questions_column_width"] : 180;
      $facebook_form_url      = $info["facebook_form_url"];

      $result = mysql_query("
        UPDATE {$g_table_prefix}module_facebook_forms
        SET    show_like_button = '$show_like_button',
               questions_column_width = '$questions_column_width',
               facebook_form_url = '$facebook_form_url'
        WHERE fb_id = $fb_id
      ") or die(mysql_error());

      if ($result)
      {
        $success = true;
        $message = $L["notify_form_updated"];
      }
      else
      {
        $success = true;
        $message = $L["notify_form_not_updated"];
      }
      break;
  }

  return array($success, $message);
}


function fb_delete_form($fb_id)
{
  global $L, $g_table_prefix;

  if (empty($fb_id) || !is_numeric($fb_id))
    return array(false, $L["notify_invalid_delete_id"]);

  mysql_query("DELETE FROM {$g_table_prefix}module_facebook_forms WHERE fb_id = $fb_id");

  if (mysql_affected_rows() == 1)
  {
    return array(true, $L["notify_configured_form_deleted"]);
  }
  else
  {
    return array(false, $L["notify_invalid_delete_id"]);
  }
}

