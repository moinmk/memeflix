<?php
	session_start();
	$_SESSION['id']="";
?>

<html>
	<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login</title>
	</head>
		<body>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
			<link rel="stylesheet" href="signup_logincss.css" type="text/css">
			<div class="mainframelogin">
				<div>
					<ul class="loginsignupnav">
						<li><a class="active" >LOGIN</a></li>
						<li><a href="signup.php">SIGNUP</a></li>
					</ul>
				</div>
				<h1 style="font-family: GulimChe;color:gray;">welcome back!</h1>
				<input class="login_input" type="text" name="name" placeholder="Username" size=33 autocomplete="off"><br>
				<input class="login_input" type="password" name="password" placeholder="Password" size=33><br>
				<button class="loginbutton" name="login">Login</button>
			</div>
		</body>
</html>

<?php
    include("db_connection.php");
    if((isset($_POST['login']))!=NULL){
        $name=mysqli_real_escape_string($connection,$_POST['name']);
        $password=mysqli_real_escape_string($connection,$_POST['password']);
        $query="SELECT id,name,password FROM user_details";
        $result=mysqli_query($connection,$query);
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
				$id=$row["id"];
                if($name==$row["name"] && $password==$row["password"]){
					$_SESSION["id"]=$id;
					header("location:index.php");
                    // header("location:redirect.php?id=$id");
				}
			}
			echo '<script>alert("username or password incorrect");</script>';
		}
		
    }
?>