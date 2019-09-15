<?php
  session_start();
  $referrer = $_SESSION['referrer'];
  if (!isset($referrer)){
    $referrer = "../index.php";
  }
  session_unset();
  session_destroy();
  header("Location: $referrer");
?>