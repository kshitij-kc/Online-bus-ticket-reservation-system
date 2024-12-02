<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
	<title>Online Bus and Ticket Booking</title>
	<link rel="stylesheet" href="cssfile/nav.css">
	<link rel="stylesheet" href="cssfile/footer.css">
	<link rel="stylesheet" type="text/css" href="cssfile/videoedit.css">
	<link href="http://fonts.googleapis.com/css?family=Cookie" rel="stylesheet" type="text/css">
	<script src="https://kit.fontawesome.com/a076d05399.js"></script>
	<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/fontawesome.min.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />

	<style type="text/css">
		body {
			background-image: url(10.jpg);
			background-size: cover;
			background-repeat: no-repeat;
			background-attachment: fixed;
		}

		.home_details {
			color: #fff;
			font-family: inherit;
			font-size: 74px;
			padding: 162px 5px 5px 185px;
		}

		.font {
			color: #F9522E;
		}

		.btnHome {
			font-family: inherit;
			background-color: #F9522E;
			padding: 13px 44px;
			font-size: 18px;
			border-style: none;
		}

		.btnHome:hover {
			background-color: orange;
			cursor: pointer;
		}

		.card-container {
			display: grid;
			grid-template-columns: repeat(3, 1fr);
			gap: 20px;
			padding: 20px;
		}

		.card {
			border: 1px solid #ccc;
			border-radius: 8px;
			padding: 20px;
			text-align: center;
			background-color: #f9f9f9;
		}

		.card img {
			width: 100%;
			height: auto;
			max-height: 200px;
			object-fit: cover;
			border-radius: 8px;
		}

		.card h2 {
			font-size: 20px;
			color: #F9522E;
			margin-bottom: 14px;
		}

		.card p {
			font-size: 16px;
			color: #333;
			margin-bottom: 14px;
		}

		.view-all-btn {
			display: block;
			margin: 20px auto;
			background-color: #F9522E;
			color: white;
			padding: 10px 20px;
			border: none;
			border-radius: 5px;
			cursor: pointer;
			text-align: center;
			width: 14%;
		}

		.view-all-btn:hover {
			background-color: orange;
		}

		a.btn-book {
			color: #ffffff;
			background: #f9522e;
			padding: 5px 20px;
		}
	</style>
</head>

<body>

	<div id="container">
		<?php
			include("nav.php");
			include("function.php");
		?>

		<h1 class="home_details">Your Bus Pass.Anytime. <br>
			<font class="font">Anywhere..</font>
			<br>
			<a href="signUp.php"><button class="btnHome">SIGN UP NOW</button></a>
		</h1>
	</div>

	<?php
	include("connection.php");
	$bus_sql = "SELECT * FROM bus ORDER BY id DESC LIMIT 6";
	$bus_result = mysqli_query($conn, $bus_sql);
	?>

	<div class="card-container">
		<?php while ($bus_detail = mysqli_fetch_assoc($bus_result)) :
			$base_price = is_numeric($bus_detail['cost']) ? (float) $bus_detail['cost'] : 0.0;
			$booked_seats = is_numeric($bus_detail['booked_seats']) ? (int) $bus_detail['booked_seats'] : 0;
			$available_seats = is_numeric($bus_detail['seat_available']) ? (int) $bus_detail['seat_available'] : 0;

			$new_price = calculateDynamicPrice($base_price, $booked_seats, $available_seats);
			?>

			<div class="card">
				<img src="<?php echo !empty($bus_detail['bus_picture']) ? htmlspecialchars($bus_detail['bus_picture']) : 'image/3.jpg'; ?>" alt="Bus Image">
				<h2><?php echo isset($bus_detail['Bus_Name']) ? $bus_detail['Bus_Name'] : ''; ?></h2>
				<p>Route: <?php echo isset($bus_detail['route']) ? $bus_detail['route'] : ''; ?></p>
				<p>Booked Seats: <?php echo isset($bus_detail['booked_seats']) ? $bus_detail['booked_seats'] : ''; ?></p>
				<p>Seats Available: <?php echo isset($bus_detail['seat_available']) ? $bus_detail['seat_available'] : ''; ?></p>
				<p>Price: <?php echo isset($bus_detail['cost']) ? $new_price : ''; ?></p>

				<a href="AddBooking.php?bus_id=<?php echo $bus_detail['id']; ?>" class="btn-book">Book Now</a>
			</div>
		<?php endwhile; ?>
	</div>

	<a href="services.php" class="view-all-btn">View All Buses</a>
</body>

</html>
