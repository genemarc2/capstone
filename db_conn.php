<?php

$sname= "localhost";
$unmae= "root";
$password = "";

$db_name = "brew_cafe";

$conn = mysqli_connect($sname, $unmae, $password, $db_name);

if (!$conn) {
	echo "Connection failed!";
}