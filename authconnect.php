<?php
$SERVER_NAME = $_SERVER["SERVER_NAME"];
if ($SERVER_NAME == 'localhost' || true) {
  $mydb = mysql_connect("localhost", "alanmand_auth", "daBrav3s!") or die(mysql_error());
  mysql_select_db("alanmand_authentication") or die(mysql_error());
}
if (!$mydb) {
  print ("Unable to connect to DB.");
  exit;
}
?>