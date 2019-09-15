<?php
$username = $_POST["username"];
$pass = $_POST["password"];
$referrer = $_GET['referrer'];
if (!isset($referrer)) $referrer=$_POST['referrer'];
if (!isset($referrer)) $referrer="../index.php";

$valid = 0;

if (isset($username)) {
  $hash = hash("sha256",$pass);
  include_once("authconnect.php");
  $query = "select id,username,first from Users WHERE username='".addslashes(strtolower($username))."' AND password='$hash' AND registration_confirmed=1";
  $result = mysql_query($query) or die(mysql_error());
  
  if (mysql_num_rows($result) > 0){
    $row = mysql_fetch_array($result);
    $name = $row['first'];
    $userID = $row['id']; 
    $valid = 1;
  }
  if ($valid) {
    #mysql_disconnect();
    session_start();
    header("Cache-control: private");
    $_SESSION["access"] = "granted";
    $_SESSION["first"] = $name;
    $_SESSION["username"] = $username;
    $_SESSION["userID"] = $userID;
    
    $query = "SELECT * FROM Access_Permissions as ap
              INNER JOIN Pages as p ON ap.page_id=p.id 
              WHERE user_id=$userID";
    $results = mysql_query($query) or die(mysql_error());
    $allowedSites = Array();
    while($row=mysql_fetch_array($results)){
      $allowedSites[$row['address']]='y';
    }

    $_SESSION["allowedSites"]=$sitesAllowed;
    header("Location: $referrer");
  } else {
    print("Invalid login.");
  }
}

?>

<html>
<head>
<style type="text/css">
tr.on {
  background: #CCCCFF;
}

tr.off {
  background: #FFFFF;
}

select.caricon option {
background-repeat:no-repeat;
background-position:bottom left;
padding-left:30px;
}


h1 {font:bold 16pt "arial"}
body {font:bold 12pt "arial"}
tr {font:12pt "arial",sans-serif}


</style>
<title>AlanMAnderson.com</title>
</head>
<body>
<table bgcolor='#FFFFFF'>
<form method="post" action="<?php echo $PHP_SELF;?>">
<tr>
Login to your account
<tr><td>
   Username: <td> <input width=200 name='username' type='text'>
<tr><td>
   Password: <td><input width=200 name='password' type='password'>
<tr><td>
   <a href="registeruser.php">Sign up for an account</a>
<tr><td>
   <input type="submit" name="Login" value="Login">
   <input type="hidden" name="referrer" value="<?php echo $referrer; ?>"
</form>
</table>
</body>
</div>