<?php

if (isset($request["update"]))
{
  list($g_success, $g_message) = fb_update_form($fb_id, "advanced", $request);
}
$config_info = fb_get_form($fb_id);

$page_vars["config_info"] = $config_info;
$page_vars["form_name"] = ft_get_form_name($config_info["form_id"]);

ft_display_module_page("templates/edit.tpl", $page_vars);
