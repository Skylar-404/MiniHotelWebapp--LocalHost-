<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . "/layout/connection.php");

$days = null;
$totalCost = null;
$bookingdetail = null;

if ($_SERVER["REQUEST_METHOD"] == "GET") {

    $booking_id = $_REQUEST["booking_id"];
    $stmt0 = $pdo->prepare("SELECT b.booking_id, b.check_in, b.check_out, b.status,
                            c.full_name, c.email, c.phone,
                            r.room_id, r.room_number, r.room_type, r.price
                            FROM bookings b
                            JOIN customers c ON b.customer_id = c.customer_id
                            JOIN rooms r ON b.room_id = r.room_id
                            WHERE b.booking_id = :booking_id");

    $stmt0->bindParam(":booking_id", $booking_id, PDO::PARAM_INT);
    $stmt0->execute();
    $bookingdetail = $stmt0->fetch(PDO::FETCH_ASSOC);

    // Number of days that guests have stayed in
    // strtotime >> convert to second
    $checkIn  = strtotime($bookingdetail['check_in']);
    $checkOut = strtotime($bookingdetail['check_out']);
    // There are 86400 second in a day
    $days = ($checkOut - $checkIn) / (86400);
    // Price per night
    $pricePerNight = $bookingdetail['price'];
    $totalCost = $days * $pricePerNight;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $stmt1 = $pdo->prepare("UPDATE rooms SET status = 'Available' WHERE room_id = :room_id");

    $room_id = $_REQUEST["room_id"];

    $stmt1->execute([':room_id' => $room_id]);

    $stmt2 = $pdo->prepare("UPDATE bookings SET status = 'Checked Out' WHERE booking_id = :booking_id");

    $booking_id = $_REQUEST["booking_id"];

    $stmt2->execute([':booking_id' => $booking_id]);

    // For Payment table
    $amount = $_REQUEST["amount"];
    $payment_date = $_REQUEST["payment_date"];

    $stmt3 = $pdo->prepare("INSERT INTO payments(booking_id, amount, payment_date, method) VALUES (:booking_id, :amount, :payment_date, :method)");
    $stmt3->execute([
        ':booking_id' => $booking_id,
        ':amount' => $_REQUEST['amount'],
        ':payment_date' => $_REQUEST['payment_date'],
        ':method' => $_REQUEST['method']
    ]);

    if ($stmt3->errorCode() !== '00000') {
        var_dump($stmt3->errorInfo());
    }

    header("Location: hotel.php");
}



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>



    <div class="container mt-5">
        <h2 class="mb-4">Payment Method</h2>

        <form action="" id="" method="post" enctype="multipart/form-data">

            <input type="hidden" name="room_id" value="<?= $bookingdetail['room_id'] ?>">

            <div class="mb-3">
                <label for="booking_id" class="form-label">Booking ID</label>
                <input type="text" class="form-control" id="booking_id" name="booking_id" value="<?= $bookingdetail['booking_id'] ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="room_type" class="form-label">Room Type</label>
                <input type="text" class="form-control" id="room_type" name="room_type" value="<?= $bookingdetail['room_type'] ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="days" class="form-label">Number of Night(s)</label>
                <input type="text" class="form-control" id="days" name="days" value="<?= $days ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="payment_date" class="form-label">Payment Date</label>
                <input type="text" class="form-control" id="payment_date" name="payment_date" value="<?= $bookingdetail['check_out'] ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="amount" class="form-label">Amount ($)</label>
                <input type="text" class="form-control" id="amount" name="amount" value="<?= $totalCost . ".00" ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="method" class="form-label">Method</label>
                <select class="form-select" id="method" name="method" required>
                    <option value="">-- Method --</option>
                    <option value="Cash">Cash</option>
                    <option value="Credit Card">Credit Card</option>
                    <option value="KHQR">KHQR (Barkong)</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Proceed</button>
        </form>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</body>

</html>