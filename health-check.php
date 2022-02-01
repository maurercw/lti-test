<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
ini_set("display_errors", 1);
require_once("util/lti_util.php");
header('Content-Type: text/html; charset=utf-8');
session_start();
?>
<html>
<head>
  <title>LTI Health Check</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body style="font-family:sans-serif;">
    <p>Healthy!</p>
</body>
</html>