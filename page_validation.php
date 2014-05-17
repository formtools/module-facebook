<?php

if (isset($request["update"]))
{
  list($g_success, $g_message) = fb_update_form($fb_id, "validation", $request);
}

$config_info = fb_get_form($fb_id);
$grouped_fields = ft_get_grouped_view_fields($config_info["view_id"], "", $config_info["form_id"]);

// deserialize the list of error fields and pass them to the template
$serialized_error_fields = ft_get_module_settings("serialized_error_fields_{$fb_id}");

$pairs = explode("`", $serialized_error_fields);
$error_field_ids = array();
$error_strings = array();
foreach ($pairs as $pair)
{
  if (empty($pair))
    continue;

  list($field_id, $field_error) = explode("|", $pair);
  $error_field_ids[] = $field_id;
  $error_strings["error_{$field_id}"] = $field_error;
}

$page_vars["form_name"] = ft_get_form_name($config_info["form_id"]);
$page_vars["config_info"] = $config_info;
$page_vars["grouped_fields"] = $grouped_fields;
$page_vars["error_field_ids"] = $error_field_ids;
$page_vars["error_strings"] = $error_strings;

ft_display_module_page("templates/edit.tpl", $page_vars);
