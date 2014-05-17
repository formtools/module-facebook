<?php

require_once("../../global/library.php");
$request = array_merge($_POST, $_GET);

// check the configuration ID is valid

if (isset($request["message"]) && $request["message"] == "invalid_id")
{
  $content = "<div class=\"fberrorbox\">Oops! The Form Tools form configuration ID being passed isn't correct.</div>"
           . "<p>This means that the developer who set up the form didn't use the correct Canvas URL, or the Facebook form configuration no longer exists in Form Tools.</p>";
}

?>
<html>
<head>
  <link href="global/css/facebook_styles.css" rel="stylesheet" type="text/css" />
</head>
<body class="fbbody">

<?php echo $content ?>

</body>
</html>
