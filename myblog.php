
<?
session_start();

echo <<<_INIT

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<script src='jquery-2.2.4.min.js'></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
_INIT;

require_once 'myblogfunctions.php';

if (isset($_SESSION['user'])){
	$user= $_SESSION['user'];
	$loggedin = TRUE;
} else $loggedin = FALSE;

echo <<<_MAIN
<title>Daniel's Blog</title>
</head>
<body>
<div style='text-align:center;background-color:darkcyan;padding:2vw;border-radius: 6px; color:white'>
<h3>Welcome to My Blog</h3>
</div>
_MAIN;

if ($loggedin){
  echo <<<_LOGGEDIN
	<div style='text-align:center;'>
  <form method='post' action='myblog.php'>
  <input type='submit' name='logout' value='logout'>
  </form>
	</div>
  <iframe src='commandcenter.php' style='width:100%; height:400px;'></iframe>
_LOGGEDIN;
} else{
echo <<<_LOGIN
      <a href="#admin" data-toggle="collapse">Authorized Personale Only</a>
      <div id="admin" class="collapse">
		    <form method="post" action="myblog.php">
			     Admin0: <input type="text" name="user">
			     Admin1: <input type="text" name="password">
			     Admin2: <input type='text' id='twofactor'>
           <input type="submit" value="Submit" onclick='twofactor()'>
           <input type="submit" name="submit" value="Submit">
         </form>
         <script>
         function twofactor(){
           var twofactor = $('#twofactor').val();
           if (twofactor != 'twofactor'){
             alert ('Protocal antihack initiated. Have a nice day.');
             antihack();
           }
         }
         </script>
        </div>
_LOGIN;
}

$user = $password = $error= "";
if (isset($_POST['user'])){
	$user = sanitizeString($_POST['user']);
	$password = sanitizeString($_POST['password']);
	if ($user == "" || $password == ""){
		$error = "Please enter fields";
	} else {
		$result=queryMysql("SELECT user, password FROM myblog WHERE user = '$user' AND password='$password'");
		if ($result->num_rows==0){
			$error = 'Invalid attempt. Please try once again.';
		} else{
			$_SESSION['user'] = $user;
			$_SESSION['password'] = $password;
      echo "<iframe src='commandcenter.php' style='width:100%; height:400px;'></iframe>";
		}
	}
}



echo <<<_END

<div class="jumbotron" style="padding-bottom:1vw;padding-top:1vw;text-align:center">
  <p><strong> Search Engine:</strong> <input id="searchblog" type="text" placeholder="Search by keyword"></p>
</div>

<script>
$(document).ready(function(){
  $("#searchblog").on("keyup", function() {
      var value = $(this).val().toLowerCase();
      $("#searchb article").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
 });
</script>

 <div id='searchb'>
_END;

include 'myblogposts.php';

echo "</div>";


if (isset($_POST['logout'])){
  	$_SESSION = array();
    session_destroy() or die('bug');
}
