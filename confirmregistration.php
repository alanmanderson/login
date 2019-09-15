<?php
include_once('authconnect.php');
$registration_id = $_GET['id'];
$username = $_GET['un'];
try {
  mysql_query('BEGIN');
  $query="SELECT id,first, Unix_Timestamp(registration_timestamp) AS reg_timestamp FROM Users WHERE registration_id='$registration_id' AND username='$username'";
  echo $query;
  $result=mysql_query($query);
  $count=mysql_num_rows($result);
  if ($count==0){
    echo "This is not a valid address or you registered more than 72 hours ago.  Please re-register.  If you think you are receiving this message in error please contact me.";
    exit();
  }
  $row=mysql_fetch_array($result);
  $registration_timestamp=$row['reg_timestamp'];
  $now=time();
  $diff= $now-$registration_timestamp;
  if ($diff>259200){
    echo "It has been too long since you registered. Please re-register.";
    #change the users info in the db so he can reuse the username if desired.
    $updateQuery="UPDATE Users SET username='$registration_id' WHERE username='$username' AND registration_id='$registration_id'";
    exit();
  }
  echo "Thank you $row[first].  Your registration has been confirmed.  Please login to continue.";
  $confirmQuery="UPDATE Users SET registration_confirmed=1 WHERE username='$username' AND registration_id='$registration_id'";
  $result=mysql_query($confirmQuery) or die(mysql_error());
  mysql_query('COMMIT');
} catch(Exception $e) {
  mysql_query('ROLLBACK');
}
?>