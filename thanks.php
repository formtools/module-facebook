<?php

require_once("../../global/session_start.php");
require_once("library.php");
$request = array_merge($_POST, $_GET);
$fb_id = $_SESSION["fb_id"];
$config_info = fb_get_form($fb_id);

?>
<html>
<head>
  <link href="global/css/facebook_styles.css" rel="stylesheet" type="text/css" />
</head>
<body class="fbbody">

  <?php
  if (!empty($config_info["thankyou_page_title"]))
    echo "<h1>{$config_info["thankyou_page_title"]}</h1>";

  echo "<div class=\"fbbluebox\">{$config_info["thankyou_page"]}</div>";
  ?>

  <br />

  <div class="ft_link">
    Form created by <a href="http://www.formtools.org" target="_blank">Form Tools</a>
  </div>

</body>
</html>
