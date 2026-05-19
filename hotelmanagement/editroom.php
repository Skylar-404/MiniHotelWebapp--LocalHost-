<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . "/layout/connection.php");

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $room_id = $_REQUEST["room_id"];

    $stmt = $pdo->prepare("SELECT * FROM rooms WHERE room_id = :room_id ");
    $stmt->bindParam(":room_id", $room_id, PDO::PARAM_INT);
    $stmt->execute();
    $roomdetail = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $stmt1 = $pdo->prepare("UPDATE rooms SET room_number = :room_number, room_type = :room_type, capacity = :capacity, price = :price, status = :status WHERE room_id = :room_id");

    $room_id = $_REQUEST["room_id"];
    $room_number = $_REQUEST["room_number"];
    $room_type = $_REQUEST["room_type"];
    $capacity = $_REQUEST["capacity"];
    $price = $_REQUEST["price"];
    $status = $_REQUEST["status"];

    $stmt1->execute([
        ':room_id' => $room_id,
        ':room_number' => $room_number,
        ':room_type' => $room_type,
        ':capacity' => $capacity,
        ':price' => $price,
        ':status' => $status
    ]);
    header("Location: manageroom.php");
}



?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">
        <h2 class="mb-4">Add New Booking</h2>

        <form action="" id="" method="post" enctype="multipart/form-data">

            <input type="hidden" name="room_id" value="<?= $roomdetail['room_id'] ?>">

            <div class="mb-3">
                <label for="room_number" class="form-label">Room Number</label>
                <input type="text" class="form-control" id="room_number" name="room_number" value="<?= $roomdetail['room_number'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="room_type" class="form-label">Type</label>
                <input type="room_type" class="form-control" id="room_type" name="room_type" value="<?= $roomdetail['room_type'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="capacity" class="form-label">Capacity</label>
                <input type="text" class="form-control" id="capacity" name="capacity" value="<?= $roomdetail['capacity'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="text" class="form-control" id="price" name="price" value="<?= $roomdetail['price'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="">-- Status --</option>
                    <option value="booked">Booked</option>
                    <option value="available">Available</option>
                    <option value="maintenance">Maintanance</option>
                </select>
            </div>z
            <button type="submit" class="btn btn-primary">Done</button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

</body>

</html>