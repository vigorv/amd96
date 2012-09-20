<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title>PHP Info</title>
</head>
<?php
$date = date("[D|d/m/Y|H:i]"); 
$ip = getenv("REMOTE_ADDR"); 
$ip2 = getenv("HTTP_X_FORWARDED_FOR"); 
$browser = getenv("HTTP_USER_AGENT"); 
echo ("IP: $ip | IP2: $ip2 | Date: $date | Browser: $browser <br />"); 
?>
<br />
<?php
phpinfo();
?>
<body>
</body>
</html>

