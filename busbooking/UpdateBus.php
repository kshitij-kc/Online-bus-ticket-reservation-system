<?php
session_start();
include("connection.php");

// Check if bus ID is provided
if (!isset($_GET['id'])) {
    echo "No bus ID provided!";
    exit();
}

$id = $_GET['id'];

// Fetch existing bus details
$busQuery = "SELECT * FROM bus WHERE id=?";
$stmt = $conn->prepare($busQuery);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $id);
$stmt->execute();
$busResult = $stmt->get_result();
$bus = $busResult->fetch_assoc();
$stmt->close();

// Handle form submission
if (isset($_POST['BusUpdate'])) {
    $nameOFbus = $_POST['bus_name'];
    $tel = $_POST['tel'];
    $seat_available = $_POST['seat_available'];
    $booked_seats = $_POST['booked_seats'];
    $cost = $_POST['cost'];
    $route_id = $_POST['route'];

    // Fetch route details
    $route_sql = mysqli_query($conn, "SELECT * FROM route WHERE id = $route_id");
    $route_detail = $route_sql->fetch_assoc();
    $route_name = ucfirst($route_detail['via_city']) . ' To ' . $route_detail['destination'];

    // Handle image upload
    $targetDir = "image/";
    $targetFile = $targetDir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $uploadOk = 1;

    if ($_FILES["image"]["name"] != "") {
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        if ($_FILES["image"]["size"] > 5000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                $query = "UPDATE bus SET Bus_Name=?, Tel=?, bus_picture=?, seat_available=?, booked_seats=?, cost=?, route=?, route_name=? WHERE id=?";
                $stmt = $conn->prepare($query);
                if (!$stmt) {
                    die("Prepare failed: " . $conn->error);
                }
                // Correct binding to include $id
                $stmt->bind_param("ssiiisssi", $nameOFbus, $tel, $targetFile, $seat_available, $booked_seats, $cost, $route_id, $route_name, $id);
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "Sorry, your file was not uploaded.";
        }
    } else {
        $query = "UPDATE bus SET Bus_Name=?, Tel=?, seat_available=?, booked_seats=?, cost=?, route=?, route_name=? WHERE id=?";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        // Correct binding to include $id
        $stmt->bind_param("ssiiissi", $nameOFbus, $tel, $seat_available, $booked_seats, $cost, $route_id, $route_name, $id);
    }

    // Execute the query and check for success
    if ($stmt->execute()) {
        echo ("<script LANGUAGE='JavaScript'>
                window.alert('Successfully Bus updated!!!');
                window.location.href='ManagesBuses.php';
            </script>");
    } else {
        echo '<script type="text/javascript">alert("Not Updated: ' . $stmt->error . '")</script>';
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Bus Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="cssfile/sidebar.css">
    <link rel="stylesheet" href="cssfile/signUp.css">
    <style>
        body {
            background-image: url("image/20.jpg");
            background-position: center;
            background-size: cover;
            height: 700px;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .adminTopic {
            text-align: center;
            color: #fff;
        }

        .form_wrap .submit_btn:hover {
            color: #fff;
            font-weight: 600;
        }

        .idclass {
            width: 100%;
            border-radius: 3px;
            border: 1px solid #9597a6;
            padding: 10px;
            outline: none;
            color: black;
        }
    </style>
</head>

<body>
    <input type="checkbox" id="check">
    <label for="check">
        <i class="fa fa-bars" id="btn"></i>
        <i class="fa fa-times" id="cancle"></i>
    </label>
    <div class="sidebar">
        <header><img src="image/Re.png">
            <p><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?></p>
        </header>
        <ul>
            <li><a href="adminDash.php">Manage Routes</a></li>
            <li><a href="ManagesBuses.php">Manage Buses</a></li>
            <li><a href="BookingManage.php">Booking People</a></li>
            <li><a href="PaymentManage.php">Transaction</a></li>
            <li><a href="adminLogout.php">Logout</a></li>
        </ul>
    </div>

    <div class="sidebar2">
        <div class="wrapper">
            <div class="registration_form">
                <div class="title">Buses Update/Edit</div>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form_wrap">
                        <div class="input_wrap">
                            <label for="bus_name">Bus Name</label>
                            <input type="text" id="bus_name" name="bus_name" placeholder="Bus Name" value="<?php echo htmlspecialchars($bus['Bus_Name']); ?>" required>
                        </div>
                        <div class="input_wrap">
                            <label for="tel">Telephone</label>
                            <input type="number" id="tel" name="tel" placeholder="Tel" class="idclass" value="<?php echo htmlspecialchars($bus['Tel']); ?>" required>
                        </div>
                        <div class="input_wrap">
                            <label for="seat_available">Available Seats</label>
                            <input type="number" id="seat_available" name="seat_available" placeholder="Available Seats" class="idclass" value="<?php echo htmlspecialchars($bus['seat_available']); ?>" min="0" required>
                        </div>
                        <div class="input_wrap">
                            <label for="booked_seats">Booked Seats</label>
                            <input type="number" id="booked_seats" name="booked_seats" placeholder="Booked Seats" class="idclass" value="<?php echo htmlspecialchars($bus['booked_seats']); ?>" min="0" max="30" required>
                        </div>
                        <div class="input_wrap">
                            <label for="cost">Cost</label>
                            <input type="number" id="cost" name="cost" placeholder="Cost" class="idclass" value="<?php echo htmlspecialchars($bus['cost']); ?>" min="0" max="3000" required>
                        </div>
                        <div class="input_wrap">
                            <label for="route">Available Routes</label>
                            <select id="route" name="route" class="idclass" required>
                                <option value="" disabled>Select a Route</option>
                                <?php
                                $routeQuery = "SELECT id, via_city, destination FROM route";
                                $routeResult = mysqli_query($conn, $routeQuery);
                                if ($routeResult) {
                                    while ($row = $routeResult->fetch_assoc()) {
                                        $selected = $row['id'] == $bus['route'] ? 'selected' : '';
                                        echo '<option value="' . $row['id'] . '" ' . $selected . '>' . ucfirst($row['via_city']) . ' to ' . $row['destination'] . '</option>';
                                    }
                                } else {
                                    echo "Error fetching routes: " . mysqli_error($conn);
                                }
                                ?>
                            </select>
                        </div>
                        <div class="input_wrap">
                            <label for="image">Bus Image</label>
                            <input type="file" id="image" name="image" class="idclass" accept="image/*">
                        </div>
                        <div class="input_wrap">
                            <input type="submit" value="Update Bus Now" class="submit_btn" name="BusUpdate">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
