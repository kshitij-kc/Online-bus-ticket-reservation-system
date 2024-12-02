<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
	<meta charset="utf-8">
	<title>Online</title>
	<link rel="stylesheet" href="cssfile/nav.css">
	<link rel="stylesheet" href="cssfile/footer_l.css">
	<link rel="stylesheet" href="cssfile/login.css">
	<link rel="stylesheet" a href="css\font-awesome.min.css">
	<link href="http://fonts.googleapis.com/css?family=Cookie" rel="stylesheet" type="text/css">
	<script src="https://kit.fontawesome.com/a076d05399.js"></script>
	<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

	<style type="text/css">
		body {
			margin: 0;
			padding: 0;
			width: 100%;
			height: 100%;
			background-color: #644c4c;
		}

		nav {
			background-color: #222;
			padding: 15px 20px;
			color: white;
			position: fixed;
			width: 100%;
			top: 0;
			left: 0;
			z-index: 1000;
		}

		nav a {
			color: white;
			text-decoration: none;
			margin: 0 15px;
			font-size: 18px;
		}

		nav a:hover {
			color: #f9522e;
		}

		.about-sec {
			display: flex;
			padding: 4rem 0;
			width: 100%;
			justify-content: center;
			background: rgba(0, 0, 0, 0.6);
			margin-top: 120px;
		}

		.card-container {
			display: grid;
			grid-template-columns: repeat(3, 1fr);
			gap: 20px;
			padding: 20px;
			margin-top: 30px;
		}

		.card {
			background-color: white;
			color: black;
			padding: 25px;
			border-radius: 12px;
			text-align: center;
			box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.2);
			position: relative;
			transition: all 0.3s ease;
		}

		.card:hover {
			transform: translateY(-5px);
			box-shadow: 0px 0px 25px rgba(0, 0, 0, 0.3);
		}

		.card h3 {
			font-size: 22px;
			color: #00b894;
			margin-bottom: 15px;
		}

		.card p {
			font-size: 16px;
			color: #555;
			margin-bottom: 20px;
		}

		.card .price {
			font-size: 20px;
			font-weight: bold;
			color: #f9522e;
			margin-bottom: 20px;
		}

		.card .btn {
			background-color: #f9522e;
			color: white;
			padding: 10px 20px;
			border-radius: 5px;
			text-decoration: none;
			margin-top: 10px;
			display: inline-block;
			transition: background-color 0.3s ease;
		}

		.card .btn:hover {
			background-color: #e83e1d;
		}

		.about-img img {
			width: 100%;
			height: auto;
			border-radius: 12px;
			object-fit: cover;
		}

		@media (max-width: 900px) {
			.about-sec {
				flex-direction: column;
				align-items: center;
			}

			.card-container {
				grid-template-columns: 1fr 1fr;
			}

			.card {
				padding: 15px;
			}

			.about-intro {
				width: 100%;
				height: 100%;
				border-top: 3px solid #00b894;
				border-left: none;
				padding: 1rem;
				margin-top: 2rem;
			}
		}
	</style>
</head>

<body>

	<?php include("nav.php"); ?>
	<h1 class="topic_bus"> ...Our Buses...</h1>

	<div class="card-container">
		<?php
		include "connection.php";
		include "function.php";
		$sqlget = "SELECT * FROM bus";
		$sqldata = mysqli_query($conn, $sqlget) or die('Error getting buses');

		while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
			$base_price = $row['cost'];
			$booked_seats = $row['booked_seats'];
			$available_seats = $row['seat_available'];
			$dynamic_price = calculateDynamicPrice($base_price, $booked_seats, $available_seats);
		?>
			<div class="card">
				<h3><?php echo $row['Bus_Name']; ?></h3>
				<p>Tel: <?php echo $row['Tel']; ?></p>
				<p>Seats Available: <?php echo $row['seat_available']; ?></p>
				<p class="price">Price: Rs.<?php echo $dynamic_price; ?></p>
				<a href="AddBooking.php?id=<?php echo $row['id']; ?>" class="btn">Book Now</a>
			</div>
		<?php } ?>
	</div>

	<h1 class="topic_bus"> ...Our Route Services...</h1>

	<div class="card-container">
		<?php
		$sqlget = "SELECT * FROM route";
		$sqldata = mysqli_query($conn, $sqlget) or die('Error getting routes');
		while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
		?>
			<div class="card">
				<h3>Route: <?php echo $row['via_city']; ?> - <?php echo $row['destination']; ?></h3>
				<p>Bus Name: <?php echo $row['bus_name']; ?></p>
				<p>Departure: <?php echo $row['departure_date'] . ' ' . $row['departure_time']; ?></p>
			</div>
		<?php } ?>
	</div>

</body>

</html>
