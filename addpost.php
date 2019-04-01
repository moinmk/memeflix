<?php

include("db_connection.php");
    session_start();
    $caption=$tags=$category=$issensitive="";
    $uid=$_SESSION['id'];
    if((isset($_POST['add']))!=NULL){
		$caption=mysqli_real_escape_string($connection,$_POST['caption']);
		$tags=mysqli_real_escape_string($connection,$_POST['tags']);
        $category=$_POST['category'];
        $issensitive=$_POST['senspost'];
        if($issensitive=="sensitive"){
            $issensitive=1;
        }
        else{
            $issensitive=0;
        }
        $image = addslashes(file_get_contents($_FILES['img']['tmp_name']));
        $extension=pathinfo(($_FILES['img']['name']),PATHINFO_EXTENSION);
        
        if($extension=='jpg' || $extension=='jpeg' || $extension=='png'){
        
            if($category==""){
                echo '<script>alert("select any one category");
                document.location="index.php";
                </script>';       
            }
            else{
                if($caption==NULL && $tags==NULL){
                    $query="INSERT INTO post(userid,category,image,udvote,issensitive,time) VALUES ('$uid','$category','$image','0','$issensitive',now())";   
                }
                elseif($caption==NULL){
                    $query="INSERT INTO post(userid,tags,category,image,udvote,issensitive,time) VALUES ('$uid','$tags','$category','$image','0','$issensitive',now())";   
                }
                elseif($tags==NULL){
                    $query="INSERT INTO post(userid,caption,category,image,udvote,issensitive,time) VALUES ('$uid','$caption','$category','$image','0','$issensitive',now())";   
                }
                else{
                    $query="INSERT INTO post(userid,caption,tags,category,image,udvote,issensitive,time) VALUES ('$uid','$caption','$tags','$category','$image','0','$issensitive',now())";          
                }
                if(mysqli_query($connection,$query)){
                    header('location: index.php');
                }
            }
            
        }
        // echo $extension;
        else{
            echo '<script>alert("uploaded image type should be jpg/jpeg/png");
            document.location="index.php";
            </script>';
      
		// else
		// {
		// 	echo "Error";
		// }
        }
    }
    
  
	mysqli_close($connection);
?>