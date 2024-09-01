<?php

    session_start();
    if(!isset($_SESSION['uname']))
    {
        header("location:login_page.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['uname']); ?>!</h2>
    <p>You have successfully logged in.</p>
    <a href="logout.php">Logout</a> 
</body>
</html>