<?php

function check_login($conn)
{

	if(isset($_SESSION['user_id']))
	{

		$id = $_SESSION['user_id'];
		$query = "select * from users where id = '$id' limit 1";

		$result = mysqli_query($conn,$query);
		if($result && mysqli_num_rows($result) > 0)
		{

			$user_data = mysqli_fetch_assoc($result);
			return $user_data;
		}
	}

	//redirect to login
	header("Location: Login.php");
	die;

}


function random_num($length)
{

	$text = "";
	if($length < 5)
	{
		$length = 5;
	}

	$len = rand(4,$length);

	for ($i=0; $i < $len; $i++) {
		

		$text .= rand(0,9);
	}

	return $text;
}

function calculateDynamicPrice($base_price, $booked_seats, $available_seats, $demand_factor = 1.0, $time_factor = 1.0) {
    $base_price = is_numeric($base_price) ? $base_price : 0.0;
    $booked_seats = is_numeric($booked_seats) ? $booked_seats : 0;
    $available_seats = is_numeric($available_seats) ? $available_seats : 0;

    $total_seats = $booked_seats + $available_seats;
    $occupancy_rate = ($total_seats > 0) ? ($booked_seats / $total_seats) : 0;

    $adjusted_price = $base_price * (1 + $occupancy_rate * $demand_factor * $time_factor);

    return round($adjusted_price, 2);
}


?>
