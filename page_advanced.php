<?php

if (isset($request["update"]))
{
  list($g_success, $g_message) = fb_update_form($fb_id, "advanced", $request);
}
$config_info = fb_get_form($fb_id);

$page_vars["config_info"] = $config_info;

ft_display_module_page("templates/edit.tpl", $page_vars);
