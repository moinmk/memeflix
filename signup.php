<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>signup</title>
	</head>
		<body>

			<script>  
				function validateform(){  
					var password=document.myform.password.value;  
					var confpassword=document.myform.confpassword.value;  
				
						if (password!=confpassword){  
		 					alert("recheck password");  
							return false;  
						}
				}  
			</script>

			<form name="myform" method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>" onsubmit="return validateform()">
			<link rel="stylesheet" href="signup_logincss.css" type="text/css">
			<div class="mainframesignup">
				<div>
					<ul class="loginsignupnav">
	 				  <li><a href="login.php">LOGIN</a></li>
					  <li><a class="active" >SIGNUP</a></li>
					</ul>
				</div>
				<br>
				<input type="text" name="name" placeholder="Username" size=33 required  ><br>
				<!-- GENDER:<input type="radio" name="gender">male<input type="radio" name="gender">female<br>-->
				<input type="date" name="dob" placeholder="DOB" size=33 required><br>
				<input type="email" name="email" placeholder="E-mail" size=33 required><br>
				<select id="gender" name="gender">
		        	<option value="male">Male</option>
		        	<option value="female">Female</option>
		        </select><br>
				<input type="password" name="password" placeholder="Password" size=33 required><br>
				<input type="password" name="confpassword" placeholder="Confirm Password" size=33 required><br>
				<button class="signupbutton" name="signup">Sign Up</button>
			</div>
		</form>
		</body>
</html>

<?php
include("db_connection.php");
	$exist=false;
	$name=$date=$email=$gender=$password="";
    if((isset($_POST['signup']))!=NULL){
	
		$name=mysqli_real_escape_string($connection,$_POST['name']);
		$date=mysqli_real_escape_string($connection,$_POST['dob']);
		$email=mysqli_real_escape_string($connection,$_POST['email']);
		$gender=mysqli_real_escape_string($connection,$_POST['gender']);
		$password=mysqli_real_escape_string($connection,$_POST['password']);

		$sql="select * from user_details";
		$res=mysqli_query($connection,$sql);
		while($row=mysqli_fetch_assoc($res)){
			if($row['name']==$name){
				$exist=true;
				echo'<script>alert("username already taken use some other user name");</script>';
			}
		}
		if(!$exist){
		$query="INSERT INTO user_details(name,dob,email,gender,password,upvoted_post) VALUES ('$name','$date','$email','$gender','$password','')";
        
        if(mysqli_query($connection,$query))
		{
			echo "Added Successfully";
			header('location: login.php');
		}
		else
		{
			echo "Error";
		}
	}
}
	mysqli_close($connection);
?>