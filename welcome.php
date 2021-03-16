<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
require_once "config.php";
require_once 'layout.php'; 
?>
<div class="row">

	<div class="leftcolumn">
		
		<?php 
			$sql="SELECT head, descr,created_date,image FROM News";
			$result=$link->query($sql);
			
			if($result->num_rows>0){
				echo '<table>';
					while($row=$result->fetch_assoc()){
						echo '<tr>';
							echo '<td class="card">
									<h2>'.$row['head'].'</h2>
									<img class="fakeimg" style="height:200px;" src="'.$row['image'].'">
									<p>'.$row['descr'].'</p>
								  </td>';
						echo '</tr>';
					}
				echo '</table>';
			}
		?>	    
	</div>
	<?php include 'rightside.php'?>  
	</div>	
</div>
<video width="320" height="240" controls>
  <source src="1.mp4" type="video/mp4">
  
	Your browser does not support the video tag.
</video>
<?php include 'footer.php' ?>

    <p>
        <a href="reset-password.php" class="fas fa-user-cog"></a>
        <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
    </p>
