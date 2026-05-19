<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . "/layout/connection.php");

if ($_SERVER["REQUEST_METHOD"] == "GET") {

    $room_id = $_REQUEST["room_id"];

    $stmt = $pdo->prepare("SELECT * FROM rooms WHERE room_id = :room_id");
    $stmt->bindParam(":room_id", $room_id, PDO::PARAM_INT);
    $stmt->execute();
    $roomdetail = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // maintenance_id
    // :maintenance_id

    $stmt1 = $pdo->prepare("INSERT INTO maintenance(room_id, cause, cost, maintenance_date, notes) VALUES (:room_id, :cause, :cost, :maintenance_date,:notes)");

    $room_id = $_REQUEST["room_id"];
    $cause = $_REQUEST["cause"];
    $cost = $_REQUEST["cost"];
    $maintenance_date = $_REQUEST["maintenance_date"];
    $notes = $_REQUEST["notes"];

    $stmt1->execute([
        ':room_id' => $room_id,
        ':cause' => $cause,
        ':cost' => $cost,
        ':maintenance_date' => $maintenance_date,
        ':notes' => $notes
    ]);

    $stmt2 = $pdo->prepare("UPDATE rooms SET status = 'Maintenance' WHERE room_id = :room_id");

    $room_id = $_REQUEST["room_id"];

    $stmt2->execute([':room_id' => $room_id]);

    header("Location: manageroom.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">
        <h2 class="mb-4">Add New Booking</h2>

        <form action="" id="" method="post" enctype="multipart/form-data">

            <!-- Update room status -->
            <input type="hidden" name="room_id" value="<?= $roomdetail['room_id'] ?>">
            <!--  -->

            <!-- Maintenance Detail -->
            <div class="mb-3">
                <label for="room_id" class="form-label">Room ID</label>
                <input type="text" class="form-control" name="room_id" value="<?= $roomdetail['room_id'] ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="cause" class="form-label">Cause</label>
                <input type="text" class="form-control" id="cause" name="cause">
            </div>

            <div class="mb-3">
                <label for="cost" class="form-label">Cost</label>
                <input type="cost" class="form-control" id="cost" name="cost">
            </div>

            <div class="mb-3">
                <label for="maintenance_date" class="form-label">Maintenance Date</label>
                <input type="date" class="form-control" id="maintenance_date" name="maintenance_date" required>
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">Notes</label>
                <input type="text" class="form-control" id="notes" name="notes">
            </div>
            <!--  -->

            <button type="submit" class="btn btn-primary">Done</button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</body>

</html>