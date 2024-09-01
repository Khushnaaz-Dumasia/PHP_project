<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login page</title>
    <!-- <script src="https://www.google.com/recaptcha/api.js" async defer></script> -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 300px;
            width: 100%;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .remember-me {
            margin-bottom: 15px;
            color: #555;
            display: flex;
            align-items: center;
        }

        .verify{
            display: flex;
            align-items: center;
        }
        .form-actions {
            display: flex;
            justify-content: space-between;
        }

        input[type="submit"] {
            width: 48%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            margin-right: 2%;
        }

        input[type="submit"]:last-child {
            margin-right: 0;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            font-size: 0.9em;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <form action="login_page.php" method="post" enctype="multipart/form-data">
        <h2>Student Login and Registration</h2>
        <span class="error">*</span>Username:
        <input type="text" name="uname" id="uname" value="<?php echo isset($_COOKIE['username']) ? $_COOKIE['username'] : ''; ?>" required>

        <br>
        <span class="error">*</span>Password:
        <input type="password" name="pwd" id="pwd">
        <!-- <input type="password" name="pwd" id="pwd" value="<?php echo isset($_COOKIE['pwd']) ? $_COOKIE['pwd'] : ''; ?>" required> -->

        <br>

        <div class="error">
            <?php
            if (empty($unameErr)) {
                echo "Username not entered </br>";
            }
            if (empty($passErr)) {
                echo "Password not entered";
            }
            ?>
        </div>
        <div class="remember-me">
            <input type="checkbox" name="remember" id="remember" <?php echo isset($_COOKIE['uname']) ? 'checked' : ''; ?>>
            <label for="remember">Remember Me</label>
        </div>

        <!-- <div class="g-recaptcha" data-sitekey="YOUR_SITE_KEY_HERE"></div> -->
         <div class="verify">
            <label>Verifaction Code : </label>
            <img src="captcha_page.php">
            <input type="number" name="vercode" id="vercode">
         </div>

        <div class="form-actions">
            <input type="submit" name="login" value="Login">
            <input type="submit" name="register" value="Register">
        </div>
    </form>
    <?php

    session_start();

    include("connection.php");

    $uname = $pwd = "";
    $unameErr = $passErr = "";


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //username validation
        if (empty($_POST['uname'])) {
            $unameErr = "Username is required";
            $uploadOk = 0;
        } else {
            $uname = $_POST['uname'];
            if (!preg_match("/^[a-zA-Z-' ]*$/", $uname)) {
                $unameErr = "Only letters and white spaces are allowed";
                $uploadOk = 0;
            }
        }


        //password validation
        if (empty($_POST['pwd'])) {
            $passErr = "Password is required";
            $uploadOk = 0;
        } else {
            $pwd = $_POST['pwd'];
        }


        //remember me feature
        if (isset($_POST['remember']) && !empty($_POST['uname']) && !empty($_POST['pwd'])) {
            setcookie('uname', $_POST['uname'], time() + (86400 * 30), "/");
            //setcookie('pwd', $_POST['pwd'], time() + (86400 * 30), "/");
        } else {
            setcookie('uname', '', time() - 3600, "/");
            //setcookie('pwd', '', time() - 3600, "/");
        }

        // if (empty($unameErr) && empty($passErr)) {
        //     $_SESSION['uname'] = $uname; // Store username in session
        //     header("Location: welcome.php"); // Redirect to welcome page
        //     exit();
        // }
    }

    // data insertion on register
    if (isset($_POST['register']) && empty($unameErr) && empty($passErr)) {
        if ($conn === false) {
            die("ERROR: Could not connect. " . mysqli_connect_error());
        }
        $uname = $_POST['uname'];
        $pwd = $_POST['pwd'];

        $sql = "insert into user_info values ('$uname', '$pwd')";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }

        mysqli_close($conn);
    }

    if(isset($_POST['submit']))
    {
        if($_POST["vercode"]!=$_SESSION["vercode"])
        {
            echo "Incorrect";
        }
        else
        {
            echo "Correct";
        }
    }


    //login click
    if (isset($_POST['login']) && empty($unameErr) && empty($passErr)) {
        // SQL query to check if the username and password match
        $sql = "SELECT * FROM user_info WHERE uname = '$uname' AND pwd = '$pwd'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 1) {
            // Username and password match
            $_SESSION['uname'] = $uname; // Store username in session
            header("Location: welcome.php"); // Redirect to welcome page
            exit();
        } else {
            // Invalid username or password
            echo "Invalid username or password";
        }

        mysqli_close($conn);
    }

    //function to clean user input
    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }


    ?>
</body>

</html>