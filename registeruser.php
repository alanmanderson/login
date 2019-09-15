<?php
  error_reporting(E_ALL);
  include_once("authconnect.php");

  function GUID()
  {
    if (function_exists('com_create_guid') === true)
      {
        return trim(com_create_guid(), '{}');
      }

    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
  }

  $successfulregistration = false;
  $firstname = htmlspecialchars($_POST['firstname']);
  $lastname = htmlspecialchars($_POST['lastname']);
  $username = htmlspecialchars($_POST['username']);
  $email = htmlspecialchars(strtolower($_POST['email']));
  $password = hash("sha256",$_POST['password']);
  $referrer = $_POST['referrer'];
  if (!isset($referrer)) {
    $referrer="index.php";
  }

  if ($username && $password) {
    $query = "SELECT * from Users WHERE username='$username' AND password='$password'";
    $results = mysql_query($query) or die(mysql_error());
    if (mysql_num_rows($results)==0){
      $registration_id = GUID();
      $query = "INSERT INTO Users (username,first, last, email, member_since, password,registration_id) VALUES('$username','$firstname','$lastname','$email',now(),'$password','$registration_id')";
      $results = mysql_query($query) or die(mysql_error());
      $successfulregistration= true;
    }
    if ($successfulregistration) {
      $body = <<<emailMarker
Please click <a href="alanmanderson.com/Login/confirmregistration.php?id=$registration_id&un=$username">here</a> to confirm your registration.  If you believe you are receiving this message in error, please delete and ignore.  Thank you! Alan
emailMarker;
      $headers = "From: alan@alanmanderson.com\r\n";
      $headers .= "MIME-Version: 1.0\r\n";
      $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
      mail($email, "alanmanderson.com registration", $body, $headers);
      header("Location: $referrer");
    }
  }
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <script type="text/javascript">
      var passwordsMatch;
      function validateInput(){
        verifyPW();
        if (passwordsMatch == false) {
        } else {
          document.forms["regForm"].submit();
        }
      }
		
      function verifyPW(){
	if (document.forms["regForm"]["password"].value == document.forms["regForm"]["confirmpassword"].value){
	  passwordsMatch = true;
	  document.getElementById("warning").innerHTML = "";
	} else {
	  passwordsMatch = false;
	  document.getElementById("warning").innerHTML = "Passwords don't match";
	}
      }
		
    </script>
  </head>
  <body>
    <form id="regForm" method="POST" action="<?php echo $PHP_SELF;?>">
      <table>
	<tr>
	  <td><label for="firstname">First Name: </label></td>
	  <td><input type="text" name="firstname"/></td>
	</tr>
	<tr>
	  <td><label for="lastname">Last Name: </label></td>
	  <td><input type="text" name="lastname"/></td>
	</tr>
        <tr>
	  <td><label for="username">Username*: </label></td>
	  <td><input type="text" name="username"/></td>
	<tr>
	  <td><label for="email">Email*: </label></td>
	  <td><input type="text" name="email"/></td>
	</tr>
	<tr>
	  <td><label for="password">Password*: </label></td>
	  <td><input type="password" name="password" onchange="verifyPW()"/></BR></td>
	</tr>
	<tr>
	  <td><label for="confirmpassword">Confirm Password*: </label></td>
	  <td><input type="password" name="confirmpassword"/ onchange="verifyPW()"></td>
	</tr>
	<tr>
	  <td colspan="2" id="warning"></td>
	</tr>
	<tr>
	  <td colspan="2"><input type="button" name="register" value="register" onClick="validateInput()"/></td>
	</tr>	
      </table>
    </form>
  </body>
</html>