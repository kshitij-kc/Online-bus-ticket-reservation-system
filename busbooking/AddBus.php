<?php
session_start();
include("connection.php");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add new bus</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="cssfile/sidebar.css">
    <link rel="stylesheet" href="cssfile/signUp.css">
    <style type="text/css">
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
            <p><?php echo $_SESSION['username']; ?></p>
        </header>
        <ul>
            <li><a href="adminDash.php">Manage Routes</a></li>
            <li><a href="ManagesBuses.php">Manage Buses</a></li>
            <li><a href="BookingManage.php">Booking People</a></li>
            <li><a href="PaymentManage.php">Transaction</a></li>
            <li><a href="adminLogout.php">logout</a></li>
        </ul>
    </div>

    <div class="sidebar2">
        <?php
        if (isset($_POST['AddBus'])) {
			$nameOFbus = $_POST['bus_name'];
			$tel = $_POST['tel'];
			$seat_capacity = $_POST['seat_available'];
			$cost = $_POST['cost'];
			$route_id = $_POST['route'];
			$targetDir = "image/";
			$targetFile = $targetDir . basename($_FILES["image"]["name"]);
			$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
			$uploadOk = 1;
			$bus_picture = null;

			$route_sql = mysqli_query($conn, "SELECT * FROM route WHERE id = $route_id");
			$route_detail = $route_sql->fetch_assoc();
			$route_name = ucfirst($route_detail['via_city']) . ' To ' . $route_detail['destination'];

			if (!empty($_FILES["image"]["name"])) {
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
						$bus_picture = $targetFile;
					} else {
						echo "Sorry, there was an error uploading your file.";
					}
				} else {
					echo "Sorry, your file was not uploaded.";
				}
			}

			if ($conn->connect_error) {
				die('Connection Failed: ' . $conn->connect_error);
			} else {
				$stmt = $conn->prepare("INSERT INTO bus (Bus_Name, Tel, bus_picture, seat_available, route, route_name, cost) VALUES (?, ?, ?, ?, ?, ?, ?)");
				if ($stmt) {
					$stmt->bind_param("sssisds", $nameOFbus, $tel, $bus_picture, $seat_capacity, $route_id, $route_name, $cost);

					if ($stmt->execute()) {
						echo ("<script LANGUAGE='JavaScript'>
							window.alert('Bus Added Successfully!');
							window.location.href='ManagesBuses.php';
						</script>");
					} else {
						echo "Error: " . $stmt->error;
					}

					$stmt->close();
				} else {
					echo "Prepare failed: " . $conn->error;
				}
			}
		}

        $routes = [];
        $routeQuery = "SELECT id, via_city, destination FROM route";
        $routeResult = mysqli_query($conn, $routeQuery);

        if ($routeResult) {
            while ($row = $routeResult->fetch_assoc()) {
                $routes[] = $row;
            }
        } else {
            echo "Error fetching routes: " . mysqli_error($conn);
        }
        ?>
        <div class="wrapper">
            <div class="registration_form">
                <div class="title">
                    Add New Bus
                </div>

                <form action="AddBus.php" method="POST" enctype="multipart/form-data">
                    <div class="form_wrap">
                        <div class="input_wrap">
                            <label for="title">Bus Name</label>
                            <input type="text" id="title" name="bus_name" placeholder="Bus Name" required>
                        </div>
                        <div class="input_wrap">
                            <label for="tel">Telephone</label>
                            <input type="text" id="tel" name="tel" placeholder="Tel" class="idclass" maxlength="13" required>
                        </div>
                        <div class="input_wrap">
                            <label for="image">Bus Image</label>
                            <input type="file" id="image" name="image" class="idclass" accept="image/*" required>
                        </div>
                        <div class="input_wrap">
                            <label for="seat_capacity">Seat Capacity</label>
                            <input type="number" id="seat_capacity" name="seat_available" placeholder="Seat Capacity" class="idclass" min="1" max="30" required>
                        </div>
                        <div class="input_wrap">
                            <label for="cost">Cost</label>
                            <input type="number" id="cost" name="cost" placeholder="Cost" class="idclass" required min="0">
                        </div>
                        <div class="input_wrap">
                            <label for="route">Available Routes</label>
                            <select id="route" name="route" class="idclass" required>
                                <option value="" disabled selected>Select a Route</option>
                                <?php foreach ($routes as $route) : ?>
                                    <option value="<?php echo $route['id']; ?>">
                                        <?php echo $route['via_city'] . ' to ' . $route['destination']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="input_wrap">
                            <input type="submit" value="Add Bus Now" class="submit_btn" name="AddBus">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
