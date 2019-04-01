<?php 
if (!defined('server')) define('server','localhost');
if (!defined('user')) define ('user','username');
if (!defined('password')) define ('password','password');
if (!defined('db')) define ('db','memeflix');

    $connection=mysqli_connect(server,user,password,db) or die(mysqli_error($connection));
?>