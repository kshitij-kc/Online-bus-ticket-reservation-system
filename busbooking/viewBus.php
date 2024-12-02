<?php
session_start();

include("connection.php");
include("function.php");

$user_data = check_login($conn);
?>

<?php include("connection.php") ?>
<!--
<!DOCTYPE html>
<html>
<head>
  <title>admin Panel suraksha</title>
</head>
<body>

   <? php // echo "welcome:".  $_SESSION['id'];
	?>
   <a href="adminLogout.php"><button class="btnHome">logout</button></a>

</body>
</html>

-->


<!DOCTYPE html>
<html>

<head>
	<title>View Bus details</title>
	<!--cdn icon library -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="cssfile/sidebar.css">
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
			//table-layout:fixed;//
			text-align: center;
			margin-top: 50px;
			background-color: rgb(255, 255, 255);
			border-radius: 10px 10px 0px 0px;

		}

		table th {
			border-bottom: 2px solid rgb(187, 187, 187);
			padding: 10px 0px 10px 0px;
			font-family: "balsamiq_sansitalic" !important;
		}

		table tr td {
			border-right: 2px solid rgb(187, 187, 187);
			height: 58px;
			padding: 22px 0px 0px 0px;
			font-family: "monospace;" !important;
			border-bottom: 2px solid rgb(187, 187, 187);
			font-size: 20px;
		}

		table tr td a {
			/background-color: rgb(255, 196, 0);/
			color: white;
			border-radius: 5px;
			padding: 6px;
			text-decoration: none;
			margin: 10px;
			font-weight: 700;
		}

		table tr td button:hover {

			/*
    background: rgb(255, 255, 255);
    text-decoration:underline;
    color:tomato;
    padding: 4px;
    border:2px solid tomato;
    transition:background-color 0.2s;*/

			padding: 5px 5px 5px 5px;
			border: 2px solid yellow;
			border-radius: 7px;
			background-color: red;
			color: white;
			cursor: pointer;
		}

		button {
			padding: 5px 5px 5px 5x;
		}

		.btnPolicy {

			padding: 5px 5px 5px 5px;
			border: 2px solid yellow;
			border-radius: 7px;
			background-color: red;
			color: white;
			margin-left: 20px;
		}

		.btnPolicy:hover {

			background: red;
			padding: 7px 7px 7px 7px;
			cursor: pointer;

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
			<li><a href="logout.php">logout</a></li>
		</ul>
	</div>



	<div class="sidebar2">
		<h1 class="adminTopic">View Bus Details</h1>
		<?php

		/*
    $sqlget="SELECT * FROM bus";
    $sqldata=mysqli_query($conn,$sqlget) or die('error getting');


    echo "<table>";
    echo "<tr>
      <th>ID</th>
    <th>Bus Name</th>
    <th>Tel</th>
    <th>Book Now</th>


       </tr>";

       while ($row=mysqli_fetch_array($sqldata,MYSQLI_ASSOC))
       {
        echo "<tr><td>";
        echo $row['id'];
        echo "</td><td>";
        echo $row['Bus_Name'];
        echo "</td><td>";
        echo $row['Tel'];
        echo "</td>";


        ?>

        <td>

        <button style="border:2px solid yellow; border-radius:7px; background-color:red;color:white;">
          <a href="Viewbooking.php">




          Book Now

          </a>

        </button>

        </td></tr>

<?php
       }

       echo "</table>";

*/
		?>
		<?php


		$sqlget = "SELECT route.*, bus.cost from route join bus on route.id=bus.route;";
		$sqldata = mysqli_query($conn, $sqlget) or die('error getting');


		echo "<table>";
		echo "<tr>
      <th>ID</th>
    <th>Via City</th>
    <th>Destination</th>
    <th>Bus Name</th>
    <th>Departure Date</th>
    <th>Departure Time</th>
    <th>Cost</th>
    <th>Booking</th>


       </tr>";

		while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
			echo "<tr><td>";
			echo $row['id'];
			echo "</td><td>";
			echo $row['via_city'];
			echo "</td><td>";
			echo $row['destination'];
			echo "</td><td>";
			echo $row['bus_name'];
			echo "</td><td>";
			echo $row['departure_date'];
			echo "</td><td>";
			echo $row['departure_time'];
			echo "</td><td>";
			echo $row['cost'];
			echo "</td>";


		?>

			<td>

				<button style="border:2px solid yellow; border-radius:7px; background-color:red;color:white;">
					<a href="AddBooking.php?bus_id=<?php echo $row['id']; ?>">
						Book Now
					</a>

				</button>

			</td>
			</tr>

		<?php
		}

		echo "</table>";


		?><br>

		<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3593955.6139239655!2d84.13015055000001!3d28.397454999999997!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3995e8c77d2e68cf%3A0x34a29abcd0cc86de!2sNepal!5e0!3m2!1sen!2snp!4v1727141894388!5m2!1sen!2snp" width="1400" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>






	</div>

</body>

</html>