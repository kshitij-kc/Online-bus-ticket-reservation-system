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

	<style>
		.container {
			max-width: 1200px;
			margin: 0 auto;
			padding: 20px;
		}

		.recommendation-cards {
			display: flex;
			flex-wrap: wrap;
			gap: 20px;
			justify-content: space-between;
		}

		.one-column {
			justify-content: center;
		}

		.two-column {
			justify-content: space-between;
		}

		.three-column {
			justify-content: space-between;
		}

		.card {
			position: relative;
			width: calc(33% - 20px);
			height: auto;
			background-color: #f4f4f4;
			border-radius: 8px;
			overflow: hidden;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
			display: flex;
			flex-direction: column;
		}

		.one-column .card {
			width: 100%;
		}

		.two-column .card {
			width: 48%;
		}

		.card-img {
			width: 100%;
			height: 200px;
			object-fit: cover;
			margin: 0;
			padding: 0;
		}

		.card-content {
			padding: 20px;
			background-color: rgba(0, 0, 0, 0.7);
			color: white;
			text-align: center;
			border-top: 2px solid #fff;
			margin: 0;
		}

		.card h3 {
			font-size: 22px;
			margin-bottom: 10px;
		}

		.card p {
			font-size: 16px;
		}

		@media (max-width: 768px) {
			.card {
				width: calc(50% - 20px);
			}

			.one-column .card {
				width: 100%;
			}

			.two-column .card {
				width: 48%;
			}

			.three-column .card {
				width: 32%;
			}
		}

		@media (max-width: 480px) {
			.card {
				width: 100%;
			}
		}
	</style>
</head>

<?php
session_start();

if (!isset($_SESSION['user_id'])) {
	header("Location: loginMenu.php");
	exit();
}

include("connection.php");
include "nav.php";
include "function.php";

$current_user_id = $_SESSION['user_id'];

$sql_get_user = "SELECT email FROM users WHERE id = '$current_user_id'";
$user_result = mysqli_query($conn, $sql_get_user);

if (!$user_result) {
	die("Error fetching user data: " . mysqli_error($conn));
}

$user_data = mysqli_fetch_assoc($user_result);
$current_user_email = $user_data['email'];

$sql_get_user_bookings = "SELECT route_id, bus_id FROM booking WHERE email = '$current_user_email'";

$user_bookings_result = mysqli_query($conn, $sql_get_user_bookings);

if (!$user_bookings_result) {
	die("Error fetching user bookings: " . mysqli_error($conn));
}

$current_user_bookings = [];
while ($row = mysqli_fetch_assoc($user_bookings_result)) {
	$current_user_bookings[] = [
		'route_id' => $row['route_id'],
		'bus_id' => $row['bus_id']
	];
}

function cosineSimilarity($user1, $user2)
{
	$dot_product = 0;
	$magnitude_user1 = 0;
	$magnitude_user2 = 0;

	$user1_data = [];
	foreach ($user1 as $booking) {
		if (isset($booking['route_id']) && isset($booking['bus_id'])) {
			$user1_data[$booking['route_id']] = $booking['bus_id'];
		}
	}

	$user2_data = [];
	foreach ($user2 as $route_id => $bus_id) {
		$booking[] = [
			'route_id' => $route_id,
			'bus_id' => $bus_id
		];
		if (isset($booking['route_id']) && isset($booking['bus_id'])) {
			$user2_data[$booking['route_id']] = $booking['bus_id'];
		}
	}

	foreach ($user1_data as $route_id => $bus_id) {
		if (isset($user2_data[$route_id])) {
			if ($user1_data[$route_id] == $user2_data[$route_id]) {
				$dot_product += 1;
			}
		}
		$magnitude_user1 += 1;
	}

	foreach ($user2_data as $route_id => $bus_id) {
		$magnitude_user2 += 1;
	}

	if ($magnitude_user1 == 0 || $magnitude_user2 == 0) {
		return 0;
	}

	$similarity = $dot_product / (sqrt($magnitude_user1) * sqrt($magnitude_user2));

	return $similarity;
}

$sql_get_all_users_bookings = "SELECT email, route_id, bus_id FROM booking";
$all_users_result = mysqli_query($conn, $sql_get_all_users_bookings);

if (!$all_users_result) {
	die("Error fetching all users' bookings: " . mysqli_error($conn));
}

$all_users_bookings = [];
while ($row = mysqli_fetch_assoc($all_users_result)) {
	$all_users_bookings[$row['email']][$row['route_id']] = $row['bus_id'];
}

$user_similarities = [];

foreach ($all_users_bookings as $other_user_email => $other_user_bookings) {
	if ($other_user_email != $current_user_email) {
		$similarity_score = cosineSimilarity($current_user_bookings, $other_user_bookings);
		if ($similarity_score > 0) {
			$user_similarities[$other_user_email] = $similarity_score;
		}
	}
}

arsort($user_similarities);

$recommended_buses = [];
foreach ($user_similarities as $similar_user_email => $similarity_score) {
	foreach ($all_users_bookings[$similar_user_email] as $route_id => $bus_id) {
		if (!in_array($bus_id, array_column($current_user_bookings, 'bus_id'))) {
			$recommended_buses[] = $bus_id;
		}
	}
}

$recommended_buses = array_unique($recommended_buses);

if (count($recommended_buses) > 0) {
	$sql_get_buses = "SELECT * FROM bus WHERE id IN (" . implode(',', $recommended_buses) . ")";
	$buses_result = mysqli_query($conn, $sql_get_buses);

	if (!$buses_result) {
		die("Error fetching bus details: " . mysqli_error($conn));
	}
} else {
	$buses_result = null;
}
?>

<body>
	<div class="container">
		<h2 style="color:black; margin-bottom:50px;">Recommended Buses Based on User Similarity</h2>

		<?php if ($buses_result && mysqli_num_rows($buses_result) > 0): ?>
			<div class="recommendation-cards <?php echo (mysqli_num_rows($buses_result) === 1) ? 'one-column' : (mysqli_num_rows($buses_result) === 2 ? 'two-column' : 'three-column'); ?>">
				<?php while ($bus = mysqli_fetch_assoc($buses_result)):
					$base_price = is_numeric($bus['cost']) ? (float) $bus['cost'] : 0.0;
					$booked_seats = is_numeric($bus['booked_seats']) ? (int) $bus['booked_seats'] : 0;
					$available_seats = is_numeric($bus['seat_available']) ? (int) $bus['seat_available'] : 0;
					$new_price = calculateDynamicPrice($base_price, $booked_seats, $available_seats);
				?>
					<div class="card">
						<img src="<?php echo !empty($bus['bus_picture']) ? htmlspecialchars($bus['bus_picture']) : 'image/3.jpg'; ?>" alt="Bus Image">
						<div class="card-content">
							<h2><?php echo isset($bus['Bus_Name']) ? $bus['Bus_Name'] : ''; ?></h2>
							<p>Route: <?php echo isset($bus['route']) ? $bus['route'] : ''; ?></p>
							<p>Booked Seats: <?php echo isset($bus['booked_seats']) ? $bus['booked_seats'] : ''; ?></p>
							<p>Seats Available: <?php echo isset($bus['seat_available']) ? $bus['seat_available'] : ''; ?></p>
							<p>Price: <?php echo isset($bus['cost']) ? $new_price : ''; ?></p>

							<a href="AddBooking.php?bus_id=<?php echo $bus['id']; ?>" class="btn-book" style="color: blue;">Book Now</a>
						</div>
					</div>
				<?php endwhile; ?>
			</div>
		<?php else: ?>
			<p>No bus recommendations found based on similar users' bookings.</p>
		<?php endif; ?>
	</div>

	<script src="script.js"></script>
</body>

</html>
