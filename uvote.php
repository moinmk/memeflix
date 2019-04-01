<?php
    session_start();
    $userid=$_SESSION['id'];
    // $postid=$_REQUEST['pid'];
    // $ud=$_REQUEST['ud'];
    $alldata = utf8_encode($_POST['info']);
    $data = json_decode($alldata);
    include("db_connection.php");
    $postid=$data->postid;
    $ud=$data->ud;
    // echo $postid." ".$ud;
    $query="select * from user_details where id='$userid'"; 
    $result=mysqli_query($connection,$query);
    if(mysqli_num_rows($result)>0){
        $rowdata=mysqli_fetch_assoc($result);
    }
    $udvotedpost=$rowdata["upvoted_post"];
    $str=explode(' ',$udvotedpost);
    $found="";
    foreach($str as $x){
        if($x==$postid){ //check if postid is available in upvotedpost
            $found="upvoted";
        } 
        elseif($x==("-".$postid)){
            $found="downvoted";
        }
    }
    if($ud=="upvote"){
        if($found=="upvoted"){// if post id available add 0 to udvote of post
            $notoadd=0;
        }
        elseif($found=="downvoted"){    
            $i=0;
            foreach($str as $x){
                if($x==("-".$postid)){
                    $str[$i]=$postid;
                }
                $i++;
            }
            $udvotedpost=implode(" ",$str);
            $query="update user_details set upvoted_post='$udvotedpost' where id='$userid'";//add post id in user_details table
            mysqli_query($connection,$query);
            $notoadd=2;
        }
        else{   //else add 1 to upvote of post and add postid in upvoted_post
            $query="update user_details set upvoted_post=concat(upvoted_post,' $postid') where id='$userid'";//add post id in user_details table
            mysqli_query($connection,$query);
            $notoadd=1;
        }
        $status="upvoted";
    }
   
    elseif($ud=="downvote"){
        if($found=="downvoted"){// if post id available add 0 to udvote of post
            $notoadd=0;
        }
        elseif($found=="upvoted"){
            $i=0;
            foreach($str as $x){
                if($x==$postid){
                    $str[$i]=("-".$postid);
                }
                $i++;
            }
            $udvotedpost=implode(" ",$str);
            $query="update user_details set upvoted_post='$udvotedpost' where id='$userid'";//add post id in user_details table
            mysqli_query($connection,$query);
            $notoadd=-2;
        }
        else{   //else add -1 to udvote of post and add postid in upvoted_post
            $query="update user_details set upvoted_post=concat(upvoted_post,' -','$postid') where id='$userid'";//add post id in user_details table
            mysqli_query($connection,$query);
            $notoadd=-1;
        }
        $status="downvoted";
    }
    

    
    $query="update post set udvote=udvote+'$notoadd' where id='$postid'";
    if(mysqli_query($connection,$query)){
        $query="select * from post where id='$postid'";
        $result=mysqli_query($connection,$query);
        if (mysqli_num_rows($result) > 0) {
            while($data = mysqli_fetch_assoc($result)) {
            $nov= $data['udvote'];
        // header('location: index.php');
               $arr[]=array("noofvote"=>$nov,
               "udstatus"=>$status
            );
            echo json_encode($arr);
            }     
        }
    }
    
?>