<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Bus and Ticket Booking</title>
    <link rel="stylesheet" href="cssfile/nav.css">
    <link rel="stylesheet" href="cssfile/footer.css">
    <link rel="stylesheet" type="text/css" href="cssfile/videoedit.css">
    <link href="http://fonts.googleapis.com/css?family=Cookie" rel="stylesheet" type="text/css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/hmac-sha256.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/enc-base64.min.js"></script>

    <style>
        * {
            color: #000000;
        }

        #container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 10px;
            background-color: #f9f9f9;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        form label {
            display: flex;
            flex-direction: column;
            color: #000000;
        }

        form input[type="text"], form input[type="hidden"] {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            width: 100%;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        form input[type="text"]:focus {
            border-color: #007BFF;
            outline: none;
        }

        form input[type="submit"] {
            background-color: #007BFF;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            margin-top: 10px;
        }

        form input[type="submit"]:hover {
            background-color: #0056b3;
        }

        form label {
            font-weight: bold;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>

    <?php include("nav.php"); ?>
    <div id="container">
        <form id="payment-form" action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST" onsubmit="generateSignature()" target="_blank">
            <input type="hidden" id="secret" name="secret" value="8gBm/:&EnhH.1/q" required>

            <label for="amount">Amount (in paisa)</label>
            <input type="text" id="amount" name="amount" value="10000" required>

            <label for="tax_amount">Tax Amount</label>
            <input type="text" id="tax_amount" name="tax_amount" value="0" required>

            <label for="total_amount">Total Amount</label>
            <input type="text" id="total_amount" name="total_amount" value="10000" required>

            <label for="transaction_uuid">Transaction UUID</label>
            <input type="text" id="transaction_uuid" name="transaction_uuid" required readonly>

            <label for="product_code">Product Code</label>
            <input type="text" id="product_code" name="product_code" value="EPAYTEST" required>

            <label for="product_service_charge">Product Service Charge</label>
            <input type="text" id="product_service_charge" name="product_service_charge" value="0" required>

            <label for="product_delivery_charge">Product Delivery Charge</label>
            <input type="text" id="product_delivery_charge" name="product_delivery_charge" value="0" required>

            <label for="success_url">Success URL</label>
            <input type="text" id="success_url" name="success_url" value="http://localhost/busbooking.php/booking.php" required>

            <label for="failure_url">Failure URL</label>
            <input type="text" id="failure_url" name="failure_url" value="http://localhost/busbooking.php/booking.php" required>

            <label for="signed_field_names">Signed Field Names</label>
            <input type="text" id="signed_field_names" name="signed_field_names" value="total_amount,transaction_uuid,product_code" required>

            <label for="signature">Signature</label>
            <input type="text" id="signature" name="signature" required readonly>

            <input value="Pay with eSewa" type="submit" class="button">
        </form>
    </div>

    <script>
        // Function to generate a UUID
        function generateUUID() {
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
                var r = Math.random() * 16 | 0,
                    v = c === 'x' ? r : (r & 0x3 | 0x8);
                return v.toString(16);
            });
        }

        // Function to auto-generate signature
        function generateSignature() {
            // Set the transaction UUID when the form is submitted
            var formattedTime = new Date().toISOString().slice(2, 10).replace(/-/g, '') + '-' + new Date().getHours() +
                new Date().getMinutes() + new Date().getSeconds();
            document.getElementById("transaction_uuid").value = formattedTime;

            var total_amount = document.getElementById("total_amount").value;
            var transaction_uuid = document.getElementById("transaction_uuid").value;
            var product_code = document.getElementById("product_code").value;
            var secret = document.getElementById("secret").value;

            var hash = CryptoJS.HmacSHA256(
                `total_amount=${total_amount},transaction_uuid=${transaction_uuid},product_code=${product_code}`,
                secret
            );
            var hashInBase64 = CryptoJS.enc.Base64.stringify(hash);
            document.getElementById("signature").value = hashInBase64;
        }

        // Set the transaction UUID when the page loads
        document.getElementById("transaction_uuid").value = generateUUID();
    </script>
</body>
</html>
