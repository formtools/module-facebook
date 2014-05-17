<?php

require_once("../../global/library.php");
ft_init_module_page();
$request = array_merge($_POST, $_GET);

$fb_id = isset($request["fb_id"]) ? $request["fb_id"] : "";
if (empty($fb_id))
{
  header("location: index.php");
  exit;
}

$page = ft_load_module_field("facebook", "page", "edit_configuration", "main");

$same_page = ft_get_clean_php_self();
$tabs = array(
  "main" => array(
      "tab_label" => $LANG["word_main"],
      "tab_link" => "{$same_page}?page=main&fb_id={$fb_id}"
        ),
  "labels" => array(
      "tab_label" => $L["phrase_labels_text"],
      "tab_link" => "{$same_page}?page=labels&fb_id={$fb_id}"
        ),
  "validation" => array(
      "tab_label" => $L["word_validation"],
      "tab_link" => "{$same_page}?page=validation&fb_id={$fb_id}"
        ),
  "advanced" => array(
      "tab_label" => $LANG["word_advanced"],
      "tab_link" => "{$same_page}?page=advanced&fb_id={$fb_id}"
        )
    );

$page_vars = array();
$page_vars["tabs"] = $tabs;
$page_vars["page"] = $page;
$page_vars["head_string"] =<<< END
<script src="global/scripts/configuration.js"></script>
<link type="text/css" rel="stylesheet" href="global/css/styles.css">
END;

switch ($page)
{
  case "main":
    require("page_main.php");
    break;
  case "labels":
    require("page_labels.php");
    break;
  case "validation":
    require("page_validation.php");
    break;
  case "advanced":
    require("page_advanced.php");
    break;
}

