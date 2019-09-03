




<?php

$servername = "localhost";
$username = "thebor62_danblog";
$password1 = "*&1239987.";
$dbname = "thebor62_myblog";
$conn = new mysqli($servername, $username, $password1, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
function queryMysql($query){
	global $conn;
	$result = $conn->query($query);
	if (!$result) die("Fatal Error");
	return $result;
}

function destroySession(){
	$_SESSION = array();
	if (session_id() != "" || isset($_COOKIE[session_name()]))
		setcookie(session_name(), '', time()-25000000, '/');
	session_destroy();
}

//anti-hack
function sanitizeString($var){
	global $conn;
	$var = strip_tags($var);
	$var = htmlentities($var);
	if (get_magic_quotes_gpc())
		$var = stripcslashes($var);
	return $conn->real_escape_string($var);
}


if (isset($_POST['title'])){
	$input_writing=($_POST['writing']);
	$input_title=($_POST['title']);
	$append_writingslist = "\nName: $input_title \nUrl: www.danieljhkim.com/posts/$input_title \n --------------------------------------------------------";
	$filepath_newfile= "posts/$input_title";
  $tags = ($_POST['tags']);
  $date = ($_POST['date']);
  $heading = ($_POST['heading']);
  $headings = str_replace(".", "d", $input_title);
  $headingz = str_replace(" ", "", $heading);

  $append_writingslist .= file_get_contents('writings.txt');
  file_put_contents ('writings.txt', $append_writingslist);

  $append_myblogposts =
      "<article><div style='width:100%;padding:3px;'> \n
      <p id='$headings' style='cursor: pointer;text-align:center'><b>$date:</b> <span style='margin-left:5vw;margin-right:5vw;font-size:24px;color:purple'><b>$heading</b></span>  <span style='text-align:right'><i>$tags</i> </span> </p>\n
      <div id='$headingz'> \n" .
      "<? echo file_get_contents('$filepath_newfile'); ?> \n" .
      "</div> \n
      <script> $('#$headings').click(function() { $('#$headingz').toggle()}) </script>
      <script> $('document').ready(function() { $('#$headingz').hide()}) </script>
      <hr> \n
      </div> </article>\n";


  $append_myblogposts .= file_get_contents('myblogposts.php');
  file_put_contents ('myblogposts.php', $append_myblogposts);

	$fopen_newfile = fopen($filepath_newfile, "w");
	fwrite($fopen_newfile, $input_writing) or die('Attempt failed somewhere. Please try a different way');
	fclose($fopen_newfile);
	echo "<script>$('#newfilesubmission1').html('<b>File URL:</b> www.danieljhkim.com/posts/$input_title');$('#newfilefocus').focus();</script>";
}

if (isset($_POST['delete'])){
	$input_filename=($_POST['delete']);
	$filelocation="/home/thebor62/public_html/posts/$input_filename";
	$str_to_delete = "Name: " + $input_filename;
	if (file_exists($filelocation)){
		unlink($filelocation);
		$file_contents = file_get_contents('writings.txt');
		$file_contents = str_replace($str_to_delete, "DELETED", $file_contents);
		file_put_contents('writings.txt', $file_contents);
	  echo "<script>alert ('Deletion successful.')</script>";
	} else echo "<script>alert('The file does not exist.')</script>";
}

if (isset($_POST['imgdelte'])){
	$input_filename=($_POST['img_to_delete']);
	$filelocation="/home/thebor62/public_html/images/$input_filename";
	if (file_exists($filelocation)){
		unlink($filelocation);
		$file_contents = file_get_contents('imagelist.txt');
		$file_contents = str_replace($input_filename, "DELETED", $file_contents);
		file_put_contents('imagelist.txt', $file_contents);
	  echo "<script>alert('Deletion successful.')</script>";
	} else echo "<script>alert('The file does not exist.')</script>";
}


?>
