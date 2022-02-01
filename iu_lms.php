<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
ini_set("display_errors", 1);
header('Content-Type: text/html; charset=utf-8');
session_start();
?>
<html>
<head>
  <title>IMS Learning Tools Interoperability</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body style="font-family:sans-serif;">
<p><b>IMS LTI 1.1 Consumer Launch</b></p>
<p>This is a very simple reference implementation of the
LMS side (i.e. consumer) for
<a href="http://developers.imsglobal.org/" target="_blank">IMS Learning 
Tools Interoperability</a>.</p>
<?php

require_once("util/lti_util.php");
require_once("iu_cert.php");

$cert_num = 0;
if ( isset($_REQUEST['cert_num']) ) $cert_num = $_REQUEST['cert_num'] + 0;

$cur_url = curPageURL();
echo("<p>Pre-configured certification launch data sets: ");
for ( $i=0; $i < count($lmsdata_cert); $i++) {
   echo('<a href="'.$curPageUrl.'?cert_num='.$i.'">'.$i."</a>\n");
}
echo("</p>");

$lmsdata = $lmsdata_cert[$cert_num]["lmsdata"];
$lms_custom_data = $lmsdata_cert[$cert_num]["custom_fields"];
$lms_special_data = $lmsdata_cert[$cert_num]["special_fields"];

foreach ($lmsdata as $k => $val ) {
    if ( $_POST[$k] && strlen($_POST[$k]) > 0 ) {
      $lmsdata[$k] = $_POST[$k];
    }
}

$key = "12345";
if ( isset($_SESSION["key"]) ) $key = $_SESSION["key"];
if ( isset($_REQUEST["key"]) ) {
  $key = trim($_REQUEST["key"]);
} elseif ( isset($lms_special_data["key"]) ) {
  $key = trim($lms_special_data["key"]);
}
$_SESSION["key"] = $key;

$secret = "secret";
if ( isset($_SESSION["secret"]) ) $secret = $_SESSION["secret"];
if ( isset($_REQUEST["secret"]) ) {
  $secret = trim($_REQUEST["secret"]);
} elseif ( isset($lms_special_data["secret"]) ) {
  $secret = trim($lms_special_data["secret"]);
}
$_SESSION["secret"] = $secret;

$endpoint = trim($_REQUEST["endpoint"]);
if ( ! $endpoint ) {
    if ( isset($_REQUEST['cert_num']) ) {
        $endpoint = trim($lms_special_data["endpoint"]);
    } else {
        $endpoint = str_replace("iu_lms.php","tool.php",$cur_url);
    }
}
$cssurl = str_replace("iu_lms.php","lms.css",$cur_url);
$content_url = str_replace("iu_lms.php","content_return.php",$cur_url);

$b64 = base64_encode($key.":::".$secret.":::".uniqid());
$outcomes = str_replace("iu_lms.php","common/tool_consumer_outcome.php",$cur_url);
$outcomes .= "?b64=" . $b64;

$tool_consumer_instance_guid = $lmsdata['tool_consumer_instance_guid'];
$tool_consumer_instance_description = $lmsdata['tool_consumer_instance_description'];

?>
<script language="javascript"> 
  //<![CDATA[ 
function lmsdataToggle() {
    var ele = document.getElementById("lmsDataForm");
    if(ele.style.display == "block") {
        ele.style.display = "none";
    }
    else {
        ele.style.display = "block";
    }
} 
  //]]> 
</script>
<?php
  echo("<form method=\"post\">\n");
  echo("<input type=\"submit\" name=\"launch\" value=\"Launch\">\n");
  echo("<input type=\"submit\" name=\"debug\" value=\"Debug Launch\">\n");
echo('<input type="submit" onclick="javascript:lmsdataToggle();return false;" value="Toggle Input Data">');
  if ( isset($_POST['launch']) || isset($_POST['debug']) ) {
    echo("<div id=\"lmsDataForm\" style=\"display:none\">\n");
  } else {
    echo("<div id=\"lmsDataForm\" style=\"display:block\">\n");
  }
  echo("<fieldset><legend>LTI Resource</legend>\n");
  $disabled = '';
  echo("Launch URL: <input size=\"120\" type=\"text\" $disabled name=\"endpoint\" value=\"$endpoint\">\n");
  echo("<br/>Key: <input type\"text\" name=\"key\" $disabled size=\"90\" value=\"$key\">\n");
  echo("<br/>Secret: <input type\"text\" name=\"secret\" $disabled size=\"90\" value=\"$secret\">\n");
  $iframe = isset($_REQUEST["iframe"]) && $_REQUEST["iframe"] == "true";
  $checked = '';
  if ( $iframe ) $checked = 'checked';
  echo("<br/>Launch in iFrame: <input type=\"checkbox\" name=\"iframe\" $checked $disabled value=\"true\">\n");
  $sha256 = isset($_REQUEST["sha256"]) && $_REQUEST["sha256"] == "true";
  if ( $sha256 ) $checked = 'checked';
  echo("<br/>Sign with SHA256: <input type=\"checkbox\" name=\"sha256\" $checked $disabled value=\"true\">\n");
  echo("</fieldset><p>");
  echo("<fieldset><legend>Launch Data</legend>\n");
  foreach ($lmsdata as $k => $val ) {
      echo($k.": <input type=\"text\" size=\"60\" name=\"".$k."\" value=\"");
      echo(htmlspec_utf8($val));
      echo("\"><br/>\n");
  }
  $ck1 = isset($_REQUEST["ck1"]) ? $_REQUEST["ck1"] : "";
  if ( isset($lms_custom_data["ck1"]) ) $ck1 = trim($lms_custom_data["ck1"]);

  $cv1 = isset($_REQUEST["cv1"]) ? $_REQUEST["cv1"] : "";
  if ( isset($lms_custom_data["cv1"]) ) $cv1 = trim($lms_custom_data["cv1"]);

  echo("<br/>Custom:");
  echo("<br/><input type\"text\" name=\"ck1\" size=\"20\" value=\"$ck1\"> = \n");
  echo("<input type\"text\" name=\"cv1\" size=\"20\" value=\"$cv1\">\n");
  $ck2 = isset($_REQUEST["ck2"]) ? $_REQUEST["ck2"] : "";
  if ( isset($lms_custom_data["ck2"]) ) $ck2 = trim($lms_custom_data["ck2"]);
  $cv2 = isset($_REQUEST["cv2"]) ? $_REQUEST["cv2"] : "";
  if ( isset($lms_custom_data["cv2"]) ) $cv2 = trim($lms_custom_data["cv2"]);
  echo("<br/><input type\"text\" name=\"ck2\" size=\"20\" value=\"$ck2\"> = \n");
  echo("<input type\"text\" name=\"cv2\" size=\"20\" value=\"$cv2\">\n");
  $ck3 = isset($_REQUEST["ck3"]) ? $_REQUEST["ck3"] : "";
  if ( isset($lms_custom_data["ck3"]) ) $ck3 = trim($lms_custom_data["ck3"]);
  $cv3 = isset($_REQUEST["cv3"]) ? $_REQUEST["cv3"] : "";
  if ( isset($lms_custom_data["cv3"]) ) $cv3 = trim($lms_custom_data["cv3"]);
  echo("<br/><input type\"text\" name=\"ck3\" size=\"20\" value=\"$ck3\"> = \n");
  echo("<input type\"text\" name=\"cv3\" size=\"20\" value=\"$cv3\">\n");
  $ck4 = isset($_REQUEST["ck4"]) ? $_REQUEST["ck4"] : "";
  if ( isset($lms_custom_data["ck4"]) ) $ck4 = trim($lms_custom_data["ck4"]);
  $cv4 = isset($_REQUEST["cv4"]) ? $_REQUEST["cv4"] : "";
  if ( isset($lms_custom_data["cv4"]) ) $cv4 = trim($lms_custom_data["cv4"]);
  echo("<br/><input type\"text\" name=\"ck4\" size=\"20\" value=\"$ck4\"> = \n");
  echo("<input type\"text\" name=\"cv4\" size=\"20\" value=\"$cv4\">\n");
  $ck5 = isset($_REQUEST["ck5"]) ? $_REQUEST["ck5"] : "";
  if ( isset($lms_custom_data["ck5"]) ) $ck5 = trim($lms_custom_data["ck5"]);
  $cv5 = isset($_REQUEST["cv5"]) ? $_REQUEST["cv5"] : "";
  if ( isset($lms_custom_data["cv5"]) ) $cv5 = trim($lms_custom_data["cv5"]);
  echo("<br/><input type\"text\" name=\"ck5\" size=\"20\" value=\"$ck5\"> = \n");
  echo("<input type\"text\" name=\"cv5\" size=\"20\" value=\"$cv5\">\n");
  echo("</fieldset>\n");
  echo("</form>\n");
  echo("</div>\n");
  echo("<hr>");

  $parms = $lmsdata;
  // Cleanup parms before we sign
  foreach( $parms as $k => $val ) {
    if (strlen(trim($parms[$k]) ) < 1 ) {
       unset($parms[$k]);
    }
  }

  // Add oauth_callback to be compliant with the 1.0A spec
  $parms["oauth_callback"] = "about:blank";
  $parms["lis_outcome_service_url"] = $outcomes;
  $parms["content_item_return_url"] = $content_url;
  $parms["accept_media_types"] = "application/vnd.ims.lti.v1.ltilink,application/vnd.ims.imsccv1p*";
  $parms["lis_result_sourcedid"] = '{"zap" : "Siân JSON 1234 Sourcedid <>&lt;"}';
    
if ( strpos($cur_url, "localhost" ) === FALSE ) $parms['launch_presentation_css_url'] = $cssurl;

$custom = array(
    "simple_key" => "custom_simple_value",
    "Complex!@#$^*(){}[]KEY" => "Complex!@#$^*(){}[]½Value"
);

if ( strlen($ck1) > 0 ) $custom[$ck1] = $cv1;
if ( strlen($ck2) > 0 ) $custom[$ck2] = $cv2;
if ( strlen($ck3) > 0 ) $custom[$ck3] = $cv3;
if ( strlen($ck4) > 0 ) $custom[$ck4] = $cv4;
if ( strlen($ck5) > 0 ) $custom[$ck5] = $cv5;

addCustom($parms, $custom);

if ( isset($_POST['launch']) || isset($_POST['debug']) ) {

  if ( $sha256 ) {
      $parms['oauth_signature_method'] = 'HMAC-SHA256';
  }

  $parms = signParameters($parms, $endpoint, "POST", $key, $secret, 
  "Finish Launch", $tool_consumer_instance_guid, $tool_consumer_instance_description);

  $where = '_blank';
  if ( $iframe ) $where = "width=\"100%\" height=\"900\" scrolling=\"auto\" frameborder=\"1\" transparency";

  $content = postLaunchHTML($parms, $endpoint, isset($_POST['debug']), $where);

  global $LastOAuthBodyBaseString;
  if ( isset($LastOAuthBodyBaseString) && isset($_POST['debug']) ) {
      echo("\n");
      echo('<a href="basecheck.php?b='.urlencode($LastOAuthBodyBaseString).'" target="_blank">Base String Comparison Tool</a><br/>');
      echo("\n");
  }
  print($content);



}

?>
