<?php

    session_start();
    // header('Content-Type: text/plain');    if not working remove this line comment
    $comm = utf8_encode($_POST['info']);
    $data = json_decode($comm);
    // echo $data->comment;
    $userid=$_SESSION['id'];
    $postid=$data->id;
    $comment=$data->comment;
    include("db_connection.php");
    $query="INSERT into post_comments(postid,userid,comment)values('$postid','$userid','$comment')";//isnerting data into post_comment table
    if(mysqli_query($connection,$query)){
       $query="select * from post_comments where postid='$postid' order by srno desc";//fetching comments from post_comment of particular post 
       $result=mysqli_query($connection,$query);
       if (mysqli_num_rows($result) > 0) {
           echo("<center>COMMENTS</center>");
        while($row = mysqli_fetch_assoc($result)) {
            $id=$row['userid'];
            $query="select name from user_details where id='$id'";//fetching name of user by userid
            $res=mysqli_query($connection,$query);
            $name = mysqli_fetch_assoc($res);
            ?><h3><?php echo $name['name']; ?></h3><?php
            echo $row['comment'];         
        }
    }
    
    }
    else{
        // echo "not added";
    }
?>