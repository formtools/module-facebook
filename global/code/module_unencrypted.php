<?php


function facebook__install($module_id)
{
  global $g_table_prefix, $g_root_dir, $g_root_url, $LANG;

  $queries = array();
  $table = $g_table_prefix . "module_facebook_forms";
  $queries[] = "
    CREATE TABLE IF NOT EXISTS $table (
      fb_id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
      status enum('online','offline') NOT NULL DEFAULT 'online',
      form_id mediumint(9) NOT NULL,
      view_id mediumint(9) NOT NULL,
      offline_form_page_title varchar(255) DEFAULT NULL,
      offline_form_page_content mediumtext,
      thankyou_page_title varchar(255) DEFAULT NULL,
      thankyou_page mediumtext,
      form_title varchar(255) DEFAULT NULL,
      opening_text mediumtext,
      submit_button_label varchar(255) DEFAULT NULL,
      facebook_form_url mediumtext,
      show_like_button enum('yes','no') DEFAULT NULL,
      questions_column_width varchar(3) DEFAULT NULL,
      PRIMARY KEY (fb_id)
    ) DEFAULT CHARSET=utf8
  ";


  foreach ($queries as $query)
  {
    $result = mysql_query($query);
    if (!$result)
    {
      return array(false, $LANG["facebook"]["notify_installation_problem_c"] . " <b>" . mysql_error() . "</b> ($query)");
    }
  }

  return array(true, "");
}


function facebook__uninstall($module_id)
{
  global $g_table_prefix;
  mysql_query("DROP TABLE {$g_table_prefix}module_facebook_forms");
  return array(true, "");
}

