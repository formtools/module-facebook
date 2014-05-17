<?php

if (isset($request["update"]))
{
  list($g_success, $g_message) = fb_update_form($fb_id, "main", $request);
}

$config_info = fb_get_form($fb_id);
$js = fb_get_form_view_mapping_js();

$page_vars["form_name"] = ft_get_form_name($config_info["form_id"]);
$page_vars["js_messages"] = array("phrase_please_select", "phrase_please_select_form", "word_edit", "word_delete");
$page_vars["config_info"] = $config_info;
$page_vars["head_js"] =<<< EOF
$js

var rules = [];
rules.push("required,form_id,{$L["validation_no_form_id"]}");
rules.push("required,view_id,{$L["validation_no_view_id"]}");

$(function() { fb_ns.init_configure_form_page("edit"); });
EOF;

ft_display_module_page("templates/edit.tpl", $page_vars);
