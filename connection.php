<?php
    $servername = "localhost: 3306";
    $username = "root";
    $password = "";
    $dbname = "online_exam_sys";
    
    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn) {
        echo "connected";
    }
    else{
        die("Connection failed: ").mysqli_connect_error();
    }
