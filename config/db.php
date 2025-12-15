<?php

$host = "localhost";
$username = "root";
$password = "";
$db = "gym";

$conn = mysqli_connect($host , $username , $password , $db);

if(!$conn){
    die("Connection Failed");
}

?>