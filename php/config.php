<?php

//  connect to db

  $dbhost = "localhost";
  $dbuser = "root";
  $dbpass = "root";
  $dbname = "ants";

  	$link = mysqli_connect($dbhost,$dbuser,$dbpass);
   	if (!$link) {
    	die("Database connection failed: " . mysqli_error());
	}

	// 2. Select a database to use 
	$db_select = mysqli_select_db($link, $dbname);
	if (!$db_select) {
		die("Database selection failed: " . mysqli_error());
	}
  

//  function for escaping the data


  function escape_data ($data) {
	if (ini_get('magic_quotes_gpc')) {
  		$data = stripslashes($data);
	}

	if (function_exists('mysql_real_escape_string')) {
  		global $connection;

 		$data = mysql_real_escape_string(trim($data)); 
	}
	
	return $data;

  }


?>