<?php
session_start(); // Start the session at the beginning of the script

include("connection.php"); // Include your database connection
include("function.php"); // Include your helper functions if any

// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Collect and sanitize input
    $user_name = trim($_POST['user_name']);
    $password = md5(trim($_POST['password'])); // Encrypt the password using md5

    if (!empty($user_name) && !empty($password) && !is_numeric($user_name)) {
        // Prepare the SQL query to prevent SQL injection
        $query = "SELECT * FROM users WHERE username = '$user_name' AND password = '$password' LIMIT 1";

        // Execute the query
        $result = mysqli_query($conn, $query);

        // Check if the query was successful
        if ($result) {
            // Check if a user was found
            if (mysqli_num_rows($result) > 0) {
                // Fetch the user data
                $user_data = mysqli_fetch_assoc($result);
                $_SESSION['user_id'] = $user_data['id']; // Store user ID in the session

                // Redirect to the viewBus.php page
                header("Location: viewBus.php");
                exit(); // Ensure no further code is executed
            } else {
                $error = "Wrong username or password!";
            }
        } else {
            $error = "Query failed: " . mysqli_error($conn); // Show query error if needed
        }
    } else {
        $error = "Please enter valid credentials.";
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Online Bus Ticket Booking Login</title>
    <link rel="stylesheet" href="cssfile/nav.css">
    <link rel="stylesheet" href="cssfile/footer_l.css">
    <link rel="stylesheet" href="cssfile/login.css">
    <link rel="stylesheet" a href="css/font-awesome.min.css">

    <link href="http://fonts.googleapis.com/css?family=Cookie" rel="stylesheet" type="text/css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style type="text/css">
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            background-image: url(image/8.jpg);
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
        }

        .login-box {
            height: 500px;
        }

        .sign_up {
            font-size: 20px;
        }

        .sign_up:hover {
            background-color: #fff;
        }
    </style>

</head>

<body>

    <?php include("nav.php"); ?>

    <div class="login-box">
        <img src="image/avatar.png" class="avatar">
        <h1>Login For Online Bus Ticket Booking System</h1>
        <form method="post">
            <p>Username</p>
            <input type="text" name="user_name" placeholder="Enter Username" required>
            <p>Password</p>
            <input type="password" name="password" placeholder="Enter Password" required>
            <input type="submit" name="login" value="Login">
            <a href="signUp.php" class="sign_up">Sign Up</a>
            <div style="color:red"><?php echo isset($error) ? $error : ''; ?></div>
        </form>
    </div>

</body>
</html>
