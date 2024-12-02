<?php
session_start();

include("connection.php");
include("function.php");

$user_data = check_login($conn);
?>

<?php include("connection.php") ?>
<!DOCTYPE html>
<html>

<head>
    <title>Booking Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="cssfile/sidebar.css">
    <link rel="stylesheet" href="cssfile/signUp.css">
    <style type="text/css">
        body {
            background-image: url("image/1.jpg");
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

        table {
            width: 99%;
            border-collapse: separate !important;
            margin: auto;
            text-align: center;
            margin-top: 50px;
            background-color: rgb(255, 255, 255);
            border-radius: 10px 10px 0px 0px;
        }

        table th {
            border-bottom: 2px solid rgb(187, 187, 187);
            padding: 10px 0px;
            font-family: "balsamiq_sansitalic" !important;
        }

        table tr td {
            border-right: 2px solid rgb(187, 187, 187);
            height: 58px;
            padding: 22px 0px 0px;
            font-family: "monospace" !important;
            border-bottom: 2px solid rgb(187, 187, 187);
            font-size: 20px;
        }

        table tr td a {
            color: white;
            border-radius: 5px;
            padding: 6px;
            text-decoration: none;
            margin: 10px;
            font-weight: 700;
        }

        button {
            padding: 5px;
        }

        .btnPolicy {
            padding: 5px;
            border: 2px solid yellow;
            border-radius: 7px;
            background-color: red;
            color: white;
            margin-left: 20px;
        }

        .btnPolicy:hover {
            background: red;
            padding: 7px;
            cursor: pointer;
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
            <p><?php echo $user_data['username']; ?></p>
        </header>
        <ul>
            <li><a href="viewBus.php">Ticket Booking</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="sidebar2">
        <h1 class="adminTopic">Get Your Ticket...</h1>
        <?php
        if (isset($_POST['AddBooking'])) {
			$passenger = $_POST['passenger_name'];
			$tel = $_POST['tel'];
			$email = $_POST['email'];
			$board_place = $_POST['board_place'];
			$desti = $_POST['Your_destination'];
			$status = 'Pending';
			$route_id = $_GET['bus_id'];

			$routesql = "SELECT * FROM bus WHERE `route` = $route_id";
			$routequery = mysqli_query($conn, $routesql);
			$routedetails = mysqli_fetch_assoc($routequery);
			$bus_cost = isset($routedetails['cost']) ? $routedetails['cost'] : '';
			$seat_available = isset($routedetails['seat_available'])?$routedetails['seat_available']:'';
			$booked_seats = isset($routedetails['booked_seats']) ? $routedetails['booked_seats'] : '';

			if ($seat_available > 0) {
				$transaction_id = 'txn_' . random_num(10);

				if ($conn->connect_error) {
					die('Connection Failed: ' . $conn->connect_error);
				} else {
					$stmt = $conn->prepare("INSERT INTO booking(passenger_name, telephone, email, boarding_place, Your_destination, booking_status, transaction_id, cost) VALUES(?, ?, ?, ?, ?, ?, ?,?)");
					$stmt->bind_param("ssssssss", $passenger, $tel, $email, $board_place, $desti, $status, $transaction_id, $bus_cost);

					$stmt->execute();

					$new_seat_available = $seat_available - 1;
					$new_booked_seats = $booked_seats + 1;

					$updateSeatsSql = "UPDATE bus SET seat_available = ?, booked_seats = ? WHERE route = ?";
					$updateStmt = $conn->prepare($updateSeatsSql);
					$updateStmt->bind_param("iii", $new_seat_available, $new_booked_seats, $route_id);
					$updateStmt->execute();

					echo ("<script LANGUAGE='JavaScript'>
					window.alert('Successfully added!');
					</script>");

					header('Location: AddPay.php?txn=' . $transaction_id);

					$stmt->close();
					$updateStmt->close();
				}
			} else {
				echo ("<script LANGUAGE='JavaScript'>
				window.alert('No seats available.');
				</script>");
			}
		}
        ?>

        <div class="wrapper">
            <div class="registration_form">
                <div class="title">Getting A Ticket...</div>

                <form action="#" method="POST">
                    <div class="form_wrap">
                        <div class="input_wrap">
                            <label for="title">Passenger Name</label>
                            <input type="text" id="title" name="passenger_name" placeholder="Passenger Name" value="<?php echo $user_data['First_Name'] . ' ' . $user_data['Last_Name']; ?>" required>
                        </div>

                        <div class="input_wrap">
                            <label for="title">Telephone</label>
                            <input type="number" id="title" name="tel" placeholder="Tel" class="idclass">
                        </div>

                        <div class="input_wrap">
                            <label for="title">E-mail</label>
                            <input type="email" id="title" name="email" placeholder="E-mail" class="idclass" value="<?php echo $user_data['email']; ?>" required>
                        </div>

                        <div class="input_wrap">
                            <label for="title">Board Place</label>
                            <input type="text" id="title" name="board_place" placeholder="Board place" required>
                        </div>

                        <div class="input_wrap">
                            <label for="title">Your Destination</label>
                            <input type="text" id="title" name="Your_destination" placeholder="Your destination" required>
                        </div>

                        <div class="input_wrap">
                            <input type="submit" value="Booking Now" class="submit_btn" name="AddBooking">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>
