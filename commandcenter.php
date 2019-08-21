


<?php

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
  <link rel='stylesheet' href='stylesmyblog.css'>
_INIT;

require_once 'myblogfunctions.php';

echo <<<_MAIN
<title>Daniel's Command Center</title>
</head>
<body>
<div style="text-align:center">
<h3>Command Center</h3>
</div>
_MAIN;

$filepath_writingslist="writings.txt";
$filepath_imagelist = "imagelist.txt";
$url_writingslist = "http://www.danieljhkim.com/writings.txt";


echo<<<_END

<div class='row'>
	<div class='col-sm-8'>
		<div class='jumbotron' id='txtfile'>
			<ul class="nav nav-tabs">
			<li class="nav-item">
			<a class="nav-link active" data-toggle="tab" href="#tabfilenew">New File</a>
			</li>
			<li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#tabfiledelete">Delete File</a>
			</li>
			<li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#tabfilelist">File List</a>
			</li>
			</ul>
			<div class='jumbotron' id='fsubmission'>
				<div class="tab-content">
					<div class="tab-pane active container" style='padding:0' id="tabfilenew">
						<form method='post' action='commandcenter.php'>
						<h4>New File Submission:</h4><hr>
						<textarea id='writingtextbox' name='writing'>Type content here.</textarea><br><br>
						<label><b>Name of the file: </b></label>
					<input type="text" class='inputtxt' id='newfilefocus' name='title'>
            <label><b>Heading: </b></label>
						<input type="text" class='inputtxt' name='heading'>
            <label><b>Date: </b></label>
						<input type="text" class='inputtxt' name='date'>
            <label><b>Tags: </b></label>
						<input type="text" class='inputtxt' name='tags'>
						<input type='submit' value='Submit'><br>
						</form>
						<div id='newfilesubmission1'>
						</div>
					</div>

					<div class="tab-pane container" style='padding:0' id="tabfiledelete">
						<form method='post' action='commandcenter.php'>
						<h4>Delete Files:</h4><hr>
						<label><b>Name: </b></label>
						<input type='text' class='inputtxt' name="delete" value='e.g. home.txt'>
 						<br><br>
						<input type='submit' value='Delete'>
						</form>
					</div>

					<div class="tab-pane container" style='padding:0' id="tabfilelist">
						<h4> List of All Files: </h4><hr>
						<div id ='listbox' style='height:120px'>
							<pre id='list'>
_END;

echo file_get_contents("writings.txt");

echo <<<_END
							</pre>
						</div> <br>
						<button type='button' onclick='loadlist()'>Refresh</button>

<script>
function loadlist() {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
     document.getElementById("list").innerHTML = this.responseText;}};
  xhttp.open("GET", "writings.txt?t=" + Math.random(), true);
  xhttp.send();}
</script>

					</div>
				</div>
			</div>
		</div>
	</div>

	<div class='col-sm-4'>
		<div class='jumbotron' id='txtfile'>
			<ul class="nav nav-tabs">
			<li class="nav-item">
			<a class="nav-link active" data-toggle="tab" href="#tabuploadimg">Upload Image</a>
			</li>
			<li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#tabdeleteimg">Delete Image</a>
			</li>
			<li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#tabimglist">Image List</a>
			</li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active container" style='padding:0' id="tabuploadimg">
					<div class='jumbotron' id='fsubmission'>
						<form method='post' action='commandcenter.php' enctype="multipart/form-data">
						<h5>Upload Image:</h5><hr>
						<input type='file' id='inputtxt' name="fileToUpload">
						<input type='submit' name='imgupload' value='Submit'>
						</form>

_END;

/////imgae function
if(isset($_POST["imgupload"])) {
	$target_dir = "/home/thebor62/public_html/images/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
	if (file_exists($target_file)) {
		echo "Sorry, file already exists.";
		$uploadOk = 0;
	}
	if ($_FILES["fileToUpload"]["size"] > 500000) {
		echo "Sorry, your file is too large.";
		$uploadOk = 0;
	}
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
		echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		$uploadOk = 0;
	}
	if ($uploadOk == 0) {
		echo "Sorry, your file was not uploaded.";
	} else {
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			$path_image = "www.danieljhkim.com/images/".basename( $_FILES["fileToUpload"]["name"]);
			$image_name= basename( $_FILES["fileToUpload"]["name"]);
			$append_writingslist = "\nName: $image_name [img] \nUrl: $path_image \n --------------------------------------------";
			echo "<h5>Image URL: </h5>";
			echo "<span style='background:white;border-style:inset;width:100%'>";
			echo "<pre>";
			echo $path_image;
			echo "</pre></span><br>";
			$fopen_writingslist = fopen($filepath_writingslist, "a");
			fwrite($fopen_writingslist,$append_writingslist) or die('appending into list failed');
			fclose($fopen_writingslist);
		} else {
			echo "Sorry, there was an error uploading your file.";
		}
	}
}

echo <<<_END
      </div>
    </div>
		<div class="tab-pane container" style='padding:0' id="tabdeleteimg">
			<div class='jumbotron' id='fsubmission'>
				<form method='post' action='commandcenter.php'>
				<h5>Delete Image:</h5><hr>
				<label><b>Name: </b></label>
				<input type='text' id='inputtxt' name="img_to_delete">
				<input type='submit' name='imgdelete' value='Submit'>
				</form>
				<div id='deletedimg'>
				</div>
			</div>
		</div>
		<div class="tab-pane container" style='padding:0' id="tabimglist">
			<div class='jumbotron' id='fsubmission'>
				<h5> List of Images: </h5><hr>
				<div id ='listbox' style='height:70px'>
_END;

echo file_get_contents("imagelist.txt");

echo <<<_END
					</div> <br>
					<button type='button' onclick='loadimglist()'>Refresh</button>

<script>
function loadimglist() {
var xhttp = new XMLHttpRequest();
xhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
	 document.getElementById("list_img").innerHTML = this.responseText;}};
xhttp.open("GET", "imagelist.txt?t=" + Math.random(), true);
xhttp.send();}
</script>

			</div>
		</div>
		</div>
	</div>
</div>
</div>
</div>
</div>
_END;






?>
