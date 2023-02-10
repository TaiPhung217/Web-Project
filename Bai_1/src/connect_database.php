<?php
    $servername='localhost:3307';
    $username='root';
    $password='';
    $dbname = "dtbase";
    $db=mysqli_connect($servername,$username,$password,"$dbname");

      if(!$db){
          die('Could not Connect MySql Server:' .mysql_error());
        }
?>