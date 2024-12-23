<?php 
    $config = include "./config.php";

    // Validate config keys
    $db_host = $config['DATABASE_HOSTNAME'] ?? die("DATABASE_HOSTNAME not defined in config.php");
    $db_user = $config['DATABASE_USERNAME'] ?? die("DATABASE_USERNAME not defined in config.php");
    $db_pass = $config['DATABASE_PASSWORD'] ?? die("DATABASE_PASSWORD not defined in config.php");
    $db_name = $config['DATABASE_NAME'] ?? die("DATABASE_NAME not defined in config.php");

    // Establish connection
    $link = mysqli_connect($db_host, $db_user, $db_pass) or die("Error establishing the connection with a DB: " . mysqli_connect_error());
    mysqli_select_db($link, $db_name) or die("Error connecting to the DB: " . mysqli_error($link));
   

