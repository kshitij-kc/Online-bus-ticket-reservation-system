<?php
session_start();
include("connection.php");

if (isset($_GET['txn'])) {
    $transaction_uuid = $_GET['txn'];

    $sql = "SELECT cost FROM booking WHERE transaction_id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("SQL error: " . $conn->error);
    }

    $stmt->bind_param("s", $transaction_uuid);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();

    if ($booking) {
        $amount = $booking['cost'];
    } else {
        echo "Invalid transaction.";
        exit();
    }
    $stmt->close();
} else {
    echo "Transaction not specified.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>eSewa Payment</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMZc0B1xQxr4l2EVE5Hq0L1hXrA0k6fQURoL2Cd" crossorigin="anonymous">
  <style>
      body {
          font-family: Arial, sans-serif;
          margin: 0;
          padding: 0;
          background-color: #f5f5f5;
      }
      /* Navbar styling */
      .navbar {
          background-color: #333;
          padding: 1em;
          display: flex;
          justify-content: space-between;
          align-items: center;
          color: #fff;
      }
      .navbar h1 {
          margin: 0;
          font-size: 1.5em;
      }
      .navbar ul {
          list-style: none;
          display: flex;
          gap: 15px;
          margin: 0;
      }
      .navbar ul li {
          display: inline;
      }
      .navbar ul li a {
          color: white;
          text-decoration: none;
          padding: 8px 16px;
          border-radius: 5px;
          transition: background 0.3s;
      }
      .navbar ul li a:hover {
          background-color: #575757;
      }
      /* Form styling */
      .payment-container {
          width: 100%;
          max-width: 600px;
          margin: 30px auto;
          padding: 20px;
          background: #fff;
          border-radius: 8px;
          box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      }
      table {
          width: 100%;
          margin: 20px 0;
      }
      table td {
          padding: 8px;
          font-size: 1em;
      }
      input[type="text"], input[type="submit"] {
          width: 100%;
          padding: 8px;
          margin: 5px 0;
          font-size: 1em;
          border: 1px solid #ccc;
          border-radius: 5px;
      }
      input[type="submit"] {
          background-color: #60bb46;
          color: #fff;
          cursor: pointer;
          border: none;
          transition: background 0.3s;
      }
      input[type="submit"]:hover {
          background-color: #4aa233;
      }
  </style>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.min.js"></script>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <h1>Online</h1>
    <ul>
        <li><a href="home.php"><i class="fas fa-home"></i> Home</a></li>
    </ul>
</div>

<!-- Payment Form -->
<div class="payment-container">
    <h2>eSewa Payment</h2>
    <form action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST" onsubmit="return generateSignature()" target="_blank">
        <table>
            <tbody>
                <tr>
                    <td>Amount:</td>
                    <td><input type="text" id="amount" name="amount" value="<?php echo $amount; ?>" required></td>
                </tr>
                <tr>
                    <td>Tax Amount:</td>
                    <td><input type="text" id="tax_amount" name="tax_amount" value="0" required></td>
                </tr>
                <tr>
                    <td>Total Amount:</td>
                    <td><input type="text" id="total_amount" name="total_amount" value="<?php echo $amount; ?>" required></td>
                </tr>
                <tr>
                    <td>Transaction UUID:</td>
                    <td><input type="text" id="transaction_uuid" name="transaction_uuid" value="<?php echo $transaction_uuid; ?>" required></td>
                </tr>
                <tr>
                    <td>Product Code:</td>
                    <td><input type="text" id="product_code" name="product_code" value="EPAYTEST" required></td>
                </tr>
                <tr>
                    <td>Product Service Charge:</td>
                    <td><input type="text" id="product_service_charge" name="product_service_charge" value="0" required></td>
                </tr>
                <tr>
                    <td>Product Delivery Charge:</td>
                    <td><input type="text" id="product_delivery_charge" name="product_delivery_charge" value="0" required></td>
                </tr>
                <tr>
                    <td>Success URL:</td>
                    <td><input type="text" id="success_url" name="success_url" value="http://localhost/busbooking/paySucess.php" required></td>
                </tr>
                <tr>
                    <td>Failure URL:</td>
                    <td><input type="text" id="failure_url" name="failure_url" value="http://localhost/busbooking/payFailure.php" required></td>
                </tr>
                <tr>
                    <td>Signed Field Names:</td>
                    <td><input type="text" id="signed_field_names" name="signed_field_names" value="total_amount,transaction_uuid,product_code" required></td>
                </tr>
                <tr>
                    <td>Signature:</td>
                    <td><input type="text" id="signature" name="signature" readonly required></td>
                </tr>
                <tr>
                    <td>Secret Key:</td>
                    <td><input type="text" id="secret" name="secret" value="8gBm/:&EnhH.1/q" required></td>
                </tr>
            </tbody>
        </table>

        <input type="submit" value="Pay with eSewa">
    </form>
</div>

<script>
  function generateSignature() {
      var total_amount = document.getElementById("total_amount").value;
      var transaction_uuid = document.getElementById("transaction_uuid").value;
      var product_code = document.getElementById("product_code").value;
      var secret = document.getElementById("secret").value;

      var hash = CryptoJS.HmacSHA256(
          `total_amount=${total_amount},transaction_uuid=${transaction_uuid},product_code=${product_code}`,
          secret);
      var hashInBase64 = CryptoJS.enc.Base64.stringify(hash);

      document.getElementById("signature").value = hashInBase64;

      return true; // Allow form submission to proceed
  }
</script>
</body>
</html>
