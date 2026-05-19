<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . "/layout/connection.php");

$stmt0 = $pdo->prepare("SELECT * FROM rooms");
$stmt0->execute();

$roomlist = $stmt0->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $pdo->beginTransaction();

    // Insert
    $stmt1 = $pdo->prepare(
        "INSERT INTO customers(full_name, email, phone)
             VALUES (:full_name, :email, :phone)"
    );

    $room_id   = $_POST['room_id'];
    $full_name = $_POST['full_name'];
    $email = !empty($_POST['email']) ? $_POST['email'] : null;
    $phone     = $_POST['phone'];

    $stmt1->execute([
        ':full_name' => $full_name,
        ':email'     => $email,
        ':phone'     => $phone
    ]);


    $customer_id = $pdo->lastInsertId();


    $stmt3 = $pdo->prepare(
        "INSERT INTO bookings(check_in, check_out, status, room_id, customer_id)
             VALUES (:check_in, :check_out, :status, :room_id, :customer_id)"
    );

    $check_in  = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $status    = $_POST['status'];

    $stmt3->execute([
        ':check_in'    => $check_in,
        ':check_out'   => $check_out,
        ':status'      => $status,
        ':room_id'     => $room_id,
        ':customer_id' => $customer_id
    ]);

    // Update
    $stmt2 = $pdo->prepare("UPDATE rooms SET status = 'Booked' WHERE room_id = :room_id");
    $stmt2->execute([':room_id' => $room_id]);


    $pdo->commit();

    header("Location: hotel.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add New Booking</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <?php require_once(__DIR__ . "/layout/navbarpage.php"); ?>

    <div class="container mt-5">
        <h2 class="mb-4">Add New Booking</h2>

        <form action="addbooking.php" id="addBookingForm" method="post" enctype="multipart/form-data">

            <div class="mb-3">
                <label for="full_name" class="form-label">Guest Name</label>
                <input type="text" class="form-control" id="full_name" name="full_name" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address (Optional)</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Telephone Number</label>
                <input type="number" class="form-control" id="phone" name="phone" required>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="">-- Status --</option>
                    <option value="Booked">Booking</option>
                    <option value="Checked In">Check In</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="check_in" class="form-label">Check-In Date</label>
                <input type="date" class="form-control" id="check_in" name="check_in" required>
            </div>

            <div class="mb-3">
                <label for="check_out" class="form-label">Check-Out Date</label>
                <input type="date" class="form-control" id="check_out" name="check_out" required>
            </div>

            <div class="mb-3">
                <label for="room_id" class="form-label">Select Available Room</label>
                <select class="form-select" id="room_id" name="room_id" required>
                    <option value="">-- Choose Room --</option>

                    <!-- <option value="101">Room 101</option> -->

                    <?php foreach ($roomlist as $key => $doc) { ?>
                        <option value="<?php echo $doc['room_id'] ?>"><?php echo $doc['room_number'] . ' ' . $doc['room_type'] . ' - ' . $doc['status'] ?></option>
                    <?php } ?>

                </select>
            </div>

            <button type="submit" class="btn btn-primary">Submit Booking</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

</body>

</html>