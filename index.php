<!-- adding post in database -->

<?php
	session_start();
	if($_SESSION['id']==""){
        header('location:login.php');  
    }   
    if(isset($_REQUEST['cat'])){ //for categories i.e.gaming,moive...
    $cate=$_REQUEST['cat'];
    }
    else{
        $cate="";
    }
    if(isset($_REQUEST['type'])){
        $type=$_REQUEST['type'];//for hot,trending,fresh
    }
    else{
        $type="";
    }
?>

<html>
    <head>
       <title>INDEX</title> 
    </head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <body>
    <script src="jquery.js"></script>
    
        <link rel="stylesheet" href="index_css.css" type="text/css">
		<form name="myform" method="post" action="addpost.php" enctype="multipart/form-data">
            <header>
            <a href="index.php"><img class="logo" src="memeflixlogo.png"></a>
                <!-- <h3 style="display:inline-block; float:right;margin-top:35;">user name</h3> -->
                
                    <?php
                    include("db_connection.php");
                    $userid=$_SESSION['id'];
                    //code to display all posts 
                    $query= "SELECT * FROM user_details"; //fetching user data from user_details table
                    $result=mysqli_query($connection,$query);
                    if (mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)) {
                            if($row['id']==$userid){
                                if($row['uimage']==""){
                                    echo '<h3 style="font-family: corbel">'.$row['name'].'</h3>';
            /* div usermenu*/       echo '<div class="usermenu">'; 
                                    echo '<img class="user" src="user.png">'; 
                                }
                                else{
                                    echo '<h3>'.$row['name'].'</h3>';
                                    echo '<div class="usermenu">';
                                    echo '<img class="user" src="data:image/jpeg;base64,'.base64_encode( $row['uimage'] ).'"/>';
                                }
                            }
                    
                        }
                    }
                    ?>
                    <!-- <img class="user" src="user.png"/> -- -->
                    <div class="menucontent">
                            <a href="index.php">Home</a>
                            <a id="updatedetails">Update Details</a>
                            <!-- <button type="button" id="updatedetails">user details</button> -->
                            <a href="login.php">Logout</a>
                            </div>
                </div><!--usermenu div ends -->
            </header>
            <nav>
                <button type="button" class="navbutton <?php if($type==hot) echo("actbtn");?>" id="hot">Hot</button>
                <button type="button" class="navbutton <?php if($type==trending) echo("actbtn");?>" id="trending">Trending</button>
                <button type="button" class="navbutton <?php if($type==fresh) echo("actbtn");?>" id="fresh">Fresh</button>
            </nav>
                <!-- ///////////////////////////////////////////////////////////////////////////////// -->
                <!-- ///////////////////////script for category selection///////////////////////////// -->
                <!-- ///////////////////////////////////////////////////////////////////////////////// -->
                <script>
                     $(document).ready(function(){
                        $("nav button").click(function(){
                            var name=(this.id);
                            // alert(name);
                            window.location="index.php?type="+name;
                            // $(this).addClass('current');
                        });
                     });
                </script>

            <div class="categories">
                
                <div class="list <?php if($cate=='funny')echo("current"); ?>" style="margin-top:25%" id="funny">
                    <img src="logo/funny.jpg"><p>Funny</p>
                </div> 
                <div class="list <?php if($cate=='anime')echo("current"); ?>" id="anime">
                    <img src="logo/anime.jpg"><p>Anime</p>
                </div>
                <div class="list <?php if($cate=='cars')echo("current"); ?>" id="cars">                   
                    <img src="logo/car.jpg"><p>Cars</p>
                </div>
                <div class="list <?php if($cate=='coding')echo("current"); ?>" id="coding">
                    <img src="logo/coding.jpg"><p>Coding</p>
                </div>
                <div class="list <?php if($cate=='gaming')echo("current"); ?>" id="gaming">
                    <img src="logo/gaming.jpg"><p>Gaming</p>
                </div>
                <div class="list <?php if($cate=='movies and tv')echo("current"); ?>" id="movies and tv">
                    <img src="logo/movie.jpg"><p>Movies and tv</p>
                </div>
                <div class="list <?php if($cate=='music')echo("current"); ?>" id="music">
                    <img src="logo/music.jpg"><p>Music</p>
                </div>    
                <div class="list <?php if($cate=='pc master race')echo("current"); ?>" id="pc master race">
                    <img src="logo/pc.png"><p>Pc master race</p>
                </div>
                <div class="list <?php if($cate=='superheroes')echo("current"); ?>" id="superheroes">
                    <img src="logo/batman.jpg"><p>Superheroes</p>
                </div>
                    
            </div><!-- categories div end -->
                <!-- ///////////////////////////////////////////////////////////////////////////////// -->
                <!-- ///////////////////////script for category selection///////////////////////////// -->
                <!-- ///////////////////////////////////////////////////////////////////////////////// -->
                <script>
                     $(document).ready(function(){
                        $(".categories div").click(function(){
                            var name=(this.id);
                            // alert(name);
                            window.location="index.php?cat="+name;
                            // $(this).addClass('current');
                        });
                     });
                </script>

            <div class="mainsection">  
                <?php
                include("db_connection.php");
                // set_time_limit(0);
                $userid=$_SESSION['id'];
                //code to display all posts 
                if($cate!=""){
                $query= "SELECT * FROM post where category='$cate' order by id desc "; //fetching post data from post table
                }
                else{
                $query= "SELECT * FROM post order by id desc "; //fetching post data from post table
                }
                $result=mysqli_query($connection,$query);
                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        
                        $postid=$row['id'];
                        //code to check if user have already liked the post
                        $query="select * from user_details where id='$userid'"; 
                        $dataresult=mysqli_query($connection,$query);
                        if(mysqli_num_rows($dataresult)>0){
                            $data=mysqli_fetch_assoc($dataresult);
                        }
                        $upvotedpost=$data["upvoted_post"];
                        $found=false;
                        $str=explode(' ',$upvotedpost);
                        $udvotestatus="";
                        $foru=false;
                        $ford=false;
                        foreach($str as $x){
                            if($x==$postid){ //check if postid is available in upvotedpost
                                $udvotestatus="upvoted";
                                $foru=true;
                            }
                            elseif($x==("-".$postid)){//check if post available in downvote
                                $udvotestatus="downvoted";
                                $ford=true;
                            }
                        }
                        if($row['issensitive']==0)
                            $sans="none";
                        else
                            $sans="block";
                        
                        //counting days from the date of content added
                        $from = strtotime($row['time']);
                        $today = time();
                        $difference = $today - $from;
                        $days=floor($difference / 86400); //60*60*24

                        if($type=="fresh"){
                            if($days>2)
                                continue;
                        }
                        elseif($type=="trending"){
                            // echo $row['udvote'];
                           
                            if($days>7 || $row['udvote']<30 || $row['udvote']>50){
                                continue;
                            }
                        }
                        elseif($type=="hot"){
                            // echo $row['udvote'];
                            
                            if($days>5 || $row['udvote']<50){
                                continue;
                            }
                        }

                        ?>
                        <div class="post">
                            <p style="font-family: Calibri Light;"><?php echo $row['caption'] ;?>
                            </p>
                            <div class="vote">
                                <button type="button" id="uvote" class="upvotebutton <?php if($foru)echo " $udvotestatus";?>" name="upvote">&#9650;</button>
                                <input id="postid" name="postid" type="text" style="display:none" value="<?php echo $row['id']?>"><br>
                                <!-- <button type="button" class="upvotebutton <?php if($foru)echo " $udvotestatus";?>" name="upvote" onclick="location.href='uvote.php?pid=<?php echo $row['id']?>&ud=upvote';">&#9650;</button><br> -->
                                <h1 class="noofvotes" name="nofovote"><?php echo $row['udvote']?></h1><br>
                                <button type="button" id="dvote" class="downvotebutton <?php if($ford)echo" $udvotestatus";?>" name="downvote">&#9660;</button>
                                <input id="postid2" name="postid" type="text" style="display:none" value="<?php echo $row['id']?>"><br>
                                <!-- <button type="button" class="downvotebutton <?php if($ford)echo" $udvotestatus";?>" name="downvote" onclick="location.href='uvote.php?pid=<?php echo $row['id']?>&ud=downvote';">&#9660;</button> -->
                            </div>    
                            <div class="image">
                                <div class="sensitive" style="display:<?php echo $sans;?>"><h1 style="font-family: Corbel;margin-top:50%;color:gray;">sensitive content<br>click to see content</h1></div>
                                <?php echo '<img class="mainpost" src="data:image/jpeg;base64,'.base64_encode( $row['image'] ).'"/>';?>
                            </div>
                                <input class="pid" type="text" style="display:none" value="<?php echo $row['id']?>">
                                <input class="commentbar" type="text" name="comments"> 
                                <input class="commentbarbutton" type="button" name="commentbutton" value="comment">
                            <div class="othercomment">
                                <?php
                                $postid=$row['id'];
                                $query="select * from post_comments where postid='$postid' order by srno desc";//fetching comments from post_comment of particular post 
                                $res=mysqli_query($connection,$query);         
                                echo("<center>COMMENTS</center>");
                                if (mysqli_num_rows($res) > 0) {
                                    while($com = mysqli_fetch_assoc($res)) {
                                        $id=$com['userid'];
                                        $query="select name from user_details where id='$id'";//fetching name of user by userid
                                        $allname=mysqli_query($connection,$query);
                                        $name = mysqli_fetch_assoc($allname);
                                        ?><h3><?php echo $name['name']; ?></h3><?php
                                        echo $com['comment'];         
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <?php          
                    }
                }
                ?>
            </div><!-- main section div end -->
                            <!-- ///////////////////////////////////////////////////////////////////////////////// -->
                            <!-- ///////////////////////script for upvote and downvote post/////////////////////// -->
                            <!-- ///////////////////////////////////////////////////////////////////////////////// -->
                            <script>
                                // $(".vote button").click(function(){
                                //     alert(this.name);
                                // });
                                $(document).ready(function(){
                                    $(".vote button").click(function(){
                                        var button=(this.name);
                                        var thisbutton=this;
                                        if(button=="upvote"){
                                            var pid=$(this).next("#postid").val();
                                            var udstatus="upvote";
                                           
                                        }
                                        else if(button=="downvote"){
                                            var pid=$(this).next("#postid2").val();
                                            var udstatus="downvote";
                                        }
                                        var data={
                                        postid:pid,ud:udstatus
                                        }; 
                                        $.ajax({
                                            data:{info:JSON.stringify(data)},
                                            type:"POST",
                                            url:"uvote.php",
                                            success:function(response){
                                            // $(thisbutton).siblings(".noofvotes").html(response);
                                            // $(thisbutton).addClass('upvoted');
                                            var some=JSON.parse(response);
                                            var nov=some[0].noofvote;
                                            var ud=some[0].udstatus;
                                            // alert(some[0].udstatus);
                                            // alert(some[0].noofvote);
                                            $(thisbutton).siblings(".noofvotes").html(nov);
                                            if(ud=="upvoted"){
                                                $(thisbutton).addClass('upvoted');
                                                $(thisbutton).siblings('#dvote').removeClass('downvoted');
                                            }
                                            else if(ud=="downvoted"){
                                                $(thisbutton).addClass('downvoted');
                                                $(thisbutton).siblings('#uvote').removeClass('upvoted');
                                                
                                            }
                                            }
                                        });                                    
                                    }); 
                                    });

                            </script>

                            <!-- ///////////////////////////////////////////////////////////////////////////////// -->
                            <!-- ////////////////script to add new comment in database using ajax///////////////// -->
                            <!-- ///////////////////////////////////////////////////////////////////////////////// -->
                            <script>
                            // $(".commentbarbutton").click(function(){
                            //     alert("button clicked");});

                             $(document).ready(function(){
                             $(".commentbarbutton").click(function(){
                                
                                var comm=$(this).prev(".commentbar").val();
                                var pid=$(this).prev(".commentbar").prev(".pid").val();
                                var button=this;//save current object into a variable
                                var data={
                                    comment:comm,id:pid
                                 }; 
                                $.ajax({
                                    data:{info:JSON.stringify(data)},
                                    type:"POST",
                                    url:"addcomment.php",
                                    success:function(response){
                                        $(button).next(".othercomment").html(response);
                                        $(".commentbar").val("");
                                    }
                                });
                             }); 
                             });

                            </script>

                            <!-- ///////////////////////////////////////////////////////////////////////////////// -->
                            <!-- ////////////////////////script for sensitive content //////////////////////////// -->
                            <!-- ///////////////////////////////////////////////////////////////////////////////// -->
                            <script>
                            $(document).ready(function(){
                                $(".sensitive").click(function(){
                                    $(this).css('display','none');
                                });
                            });
                            </script>


            <button type="button" class="addpostbtn" id="addpostbtn"><span>+</span></button>
            <div id="addpost">
                <div id="addpostcontent">
                  
                    <textarea name="caption" class="caption" rows="3" placeholder="caption(optional)"></textarea><br>
                    <!-- <label class="addimage">SELECT IMAGE -->
                    <input type="file" name="img"><br>
                    <input type="text" name="tags" placeholder="tags(optional) (ex. #tag1#tag2)"><br>
                    <select name="category">
                        <option value="">--SELECT CATEGORY--</option>
                        <option value="funny">Funny</option>
                        <option value="anime">Anime</option>
    		        	<option value="cars">cars</option>
    		        	<option value="coding">coding</option>
    		        	<option value="gaming">gaming</option>
    		        	<option value="movies and tv">movies and tv</option>
                        <option value="music">music</option>
                        <option value="pc master race">pc master race</option>
                        <option value="superheroes">superheroes</option>
                    </select><br>
                    <select name="senspost">
                        <option value="not sensitive">This Post Is Not Sensitive</option>
                        <option value="sensitive">This Post Is Sensitive</option>
    		        	</select><br>
                    <button name="add">ADD</button> 
                    <button type="button" class="close">CANCEL</button>
                    
                </div>
            </div>
            </form>
            <form action="udetails.php" method="post" enctype="multipart/form-data">
            <div id="udetails">
                <div id="udcontent" style="margin-top:-5%;">
                <h3 style="font-family: Century gothic;color:gray;">add only those details you want to update</h3>
                    <label>user image</label><br>
                    <input type="file" name="userimg"><br>
                    <label>user name</label><br>
                    <input type="text" name="uname"><br>
                    <label>user DOB</label><br>
                    <input type="date" name="udate"><br>
                    <label>user email</label><br>
                    <input type="email" name="umail"><br>
                    <label>user gender</label><br>
                    <select name="ugen">
                        <option value="">select gender</option>
                        <option value="male">male</option>
                        <option value="female">female</option>
                    </select><br>
                    <label>user password</label><br>
                    <input type="password" name="upass"><br>
                    <button name="update">ADD</button> 
                    <button type="button" class="cls">CANCEL</button>
                    
                </div>
            </div>
            </form>
            <!-- ////////////////////script for add post div//////////////////////////// -->
            <script>
                var modal = document.getElementById('addpost');
                var btn = document.getElementById("addpostbtn");
                var close = document.getElementsByClassName("close")[0];

                btn.onclick = function() {
                    modal.style.display = "block";
                }
                close.onclick = function() {
                   modal.style.display = "none";
                }
                window.onclick = function(event) {
                    if (event.target == modal) {
                        modal.style.display = "none";
                    }
                }
            </script> 
            <!-- //////////////////////////script for update user details div//////////////////////////////// -->
            <script>
                var modal1=document.getElementById('udetails');
                var btn1=document.getElementById('updatedetails');
                var close1=document.getElementsByClassName("cls")[0];

                btn1.onclick = function() {
                    modal1.style.display = "block";
                }
                close1.onclick = function() {
                   modal1.style.display = "none";
                }

                // window.onclick = function(event1) {
                //     if (event1.target == modal1) {
                //         modal1.style.display = "none";
                //     }
                // }
            </script>
        <!-- </form> -->
    </body>
</html>




