<?php
	require_once "config.php";
	
	$title_err="";
	$article_head=$article_descr="";
	$published=0;
	
	$result=mysqli_query($link,"SHOW TABLES LIKE 'News'");
	if (mysqli_num_rows($result) > 0){
		include('layout.php');?>
		<div class="row">
			
			<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" enctype="multipart/form-data">
					<div class="form-group" <?php echo (!empty($title_err)) ? 'has-error' : ''?>;>
						<label name="article_head">Title</label>
						<input type='text' name="article_head" value="<?php $article_head ?>">
						<span class="help-block"><?php echo $title_err; ?></span>
					</div/
					
					<div class="form-group"
						<label name="article_descr">Content</label>
						<textarea name="article_descr" rows='5' cols='40' value="<?php $article_descr ?>"></textarea>
					</div>
					<div class="form-group"
						<label name="ImgToUpload">Image</label>
						<input type="file" name="ImgToUpload" id="ImgToUpload">
					</div>
					<div class="form-group"
						<label name="published">Publish</label>
						<input type="checkbox" name="published" value="<?php $published?>">
					</div>
					<input type='submit' class='btn btn-primary' value='Save'>
					
			</form>
			
		</div>
		<?php 
		
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			
			if(empty(trim($_POST["article_head"]))){
				$title_err = "Please enter your title.";
			} else{
				$article_head = $_POST["article_head"];
			}
			if (empty($title_err)){
				
				$article_descr=$_POST["article_descr"];
				$published=isset($_POST["published"]) ? 1 : 0;
				
				
				$target_dir = "uploads/";
				$target_file = $target_dir . basename($_FILES["ImgToUpload"]["name"]);
				$uploadOk = 1;
				$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

				
				  $check = getimagesize($_FILES["ImgToUpload"]["tmp_name"]);
				  if($check !== false) {
					echo "File is an image - " . $check["mime"] . ".";
					$uploadOk = 1;
				  } else {
					echo "File is not an image.";
					$uploadOk = 0;
				  }
				

				// Check if file already exists
				if (file_exists($target_file)) {
				  echo "Sorry, file already exists.";
				  $uploadOk = 0;
				}

				// Check file size
				if ($_FILES["ImgToUpload"]["size"] > 500000) {
				  echo "Sorry, your file is too large.";
				  $uploadOk = 0;
				}

				// Allow certain file formats
				if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
				&& $imageFileType != "gif" ) {
				  echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
				  $uploadOk = 0;
				}

				// Check if $uploadOk is set to 0 by an error
				if ($uploadOk == 0) {
				  echo "Sorry, your file was not uploaded.";
				  $target_file=null;
				// if everything is ok, try to upload file
				} else {
				  if (move_uploaded_file($_FILES["ImgToUpload"]["tmp_name"], $target_file)) {
					echo "The file ". htmlspecialchars( basename( $_FILES["ImgToUpload"]["name"])). " has been uploaded.";
				  } else {
					echo "Sorry, there was an error uploading your file.";
				  }
				}
				
				$sql="INSERT into News(head,descr,published,image) 
					VALUES('$article_head','$article_descr','$published','$target_file')";
					
				if($link->query($sql)===TRUE){
					echo 'Successfully created a row';
				}else{
					echo "Error inserting into table: " . $link->error;
				}
				
			}
		}?>
	<?php
	}else{
		// sql to create table
		$sql = "CREATE TABLE News (
		id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		head VARCHAR(50) NOT NULL,
		descr TEXT,
		image BLOB,
		created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		published BOOLEAN)";

		if ($link->query($sql) === TRUE) {
		echo "Table News created successfully";
		} else {
		echo "Error creating table: " . $link->error;
		}
	}
	
	$link->close();
?>