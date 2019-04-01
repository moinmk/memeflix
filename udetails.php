<?php
session_start();
include("db_connection.php");
$uid=$_SESSION['id'];
echo "uid"." ".$uid;
$exist=false;
	$name=$date=$email=$gender=$password="";
    if((isset($_POST['update']))!=NULL){
		$name=mysqli_real_escape_string($connection,$_POST['uname']);
		$date=mysqli_real_escape_string($connection,$_POST['udate']);
		$email=mysqli_real_escape_string($connection,$_POST['umail']);
		$gender=mysqli_real_escape_string($connection,$_POST['ugen']);
		$password=mysqli_real_escape_string($connection,$_POST['upass']);
        $dp= addslashes(file_get_contents($_FILES['userimg']['tmp_name']));
        echo "name"." ".$name;
       
		$sql="select * from user_details";
		$res=mysqli_query($connection,$sql);
		while($row=mysqli_fetch_assoc($res)){
			if($row['name']==$name){
				$exist=true;
				echo'<script>alert("username already taken use some other user name");</script>';
                header('location: index.php');
            }
            if($row['id']==$uid){
                echo "<br>here";
            if($name==""){$name=$row['name'];}
            if($date==""){$date=$row['dob'];}
            if($email==""){$email=$row['email'];}
            if($gender==""){$gender=$row['gender'];}
            if($password==""){$password=$row['password'];}
            }
        }
           
		if(!$exist){
            $query="update user_details set uimage='$dp',name='$name',dob='$date',email='$email',gender='$gender',password='$password' where id='$uid'";
        if(mysqli_query($connection,$query))
		{
			echo "Added Successfully";
			header('location: index.php');
		}
		header('location: index.php');
	}
}
	mysqli_close($connection);
?>
