<?php

require_once(__DIR__ . "/layout/connection.php");

$stmt1 = $pdo->prepare("SELECT * FROM rooms");

$stmt1->execute();

$roomlist = $stmt1->fetchAll(PDO::FETCH_ASSOC);

// Count
$stmt2 = $pdo->query("SELECT COUNT(*) FROM rooms");

$roomCount = $stmt2->fetchColumn();


if (isset($_POST['room_id'])) {
    $room_id = $_POST['room_id'];

    $stmt = $pdo->prepare("UPDATE rooms SET status = 'Available' WHERE room_id = :room_id");
    $stmt->execute([
        ':room_id' => $room_id
    ]);

    echo "success"; // keep it simple
    exit;
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage</title>
    <style>
        body {
            background: radial-gradient(circle at top left, #eef4ff 0, #f8f9fa 35%, #f8f9fa 100%);

        }
    </style>
</head>

<body>
    <?php require_once(__DIR__ . "/layout/navbarpage.php"); ?>

    <div class="container mt-5">
        <h2 class="mb-4">Room Management</h2>

        <!-- Alert Section -->
        <div id="alertPlaceholder"></div>

        <!-- Bookings Table -->
        <div class="card">
            <div class="card-header">Customer Booking Information</div>
            <div class="card-body">
                <table class="table table-striped table-bordered" id="bookingsTable">
                    <thead class="table-dark">
                        <tr>
                            <th>Room</th>
                            <th>Room Number</th>
                            <th>Type</th>
                            <th>Capacity</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th colspan="2">Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($roomlist as $key => $doc) { ?>
                            <tr>
                                <td><?php echo $doc["room_id"] ?></td>
                                <td><?php echo $doc["room_number"] ?></td>
                                <td><?php echo $doc["room_type"] ?></td>
                                <td><?php echo $doc["capacity"] ?></td>
                                <td><?php echo $doc["price"] ?></td>
                                <!-- Status -->
                                <?php if ($doc["status"] == "Booked") { ?>
                                    <td>
                                        <span class="badge bg-primary"><?= $doc["status"] ?></span>
                                    </td>
                                <?php } ?>
                                <?php if ($doc["status"] == "Available") { ?>
                                    <td>
                                        <span class="badge bg-success"><?= $doc["status"] ?></span>
                                    </td>
                                <?php } ?>
                                <?php if ($doc["status"] == "Maintenance") { ?>
                                    <td>
                                        <span class="badge bg-danger"><?= $doc["status"] ?></span>
                                    </td>
                                <?php } ?>
                                <!--  -->
                                <td>
                                    <a href="editroom.php?room_id=<?= $doc["room_id"] ?>" target="_self" class="btn btn-success btn-sm m-1"><i class="bi bi-pencil"></i></a>
                                    <?php if ($doc["status"] == "Booked" || $doc["status"] == "Available") { ?>
                                        <a href="maintenance.php?room_id=<?= $doc["room_id"] ?>" target="_self" class="btn btn-warning btn-sm m-1"><i class="bi bi-tools"></i></a>
                                    <?php } ?>

                                    <!--  -->
                                    <?php if ($doc["status"] == "Maintenance") { ?>

                                        <button class="btn btn-success btn-sm m-1 update-status" data-room="<?= $doc["room_id"] ?>"><i class="bi bi-check2-circle"></i></button>

                                    <?php } ?>
                                    <!--  -->

                                    <button class="btn btn-danger btn-sm m-1" onclick="deleteBooking(this)"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">



    <script>
        document.querySelectorAll('.update-status').forEach(button => {
            button.addEventListener('click', function() {
                const roomId = this.dataset.room;

                fetch('manageroom.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'room_id=' + roomId
                    })
                    .then(response => response.text())

                    .then(data => {
                        if (data.trim() === "success") {
                            const statusCell = this.closest('tr').querySelector('td:nth-child(6)');
                            statusCell.innerHTML = '<span class="badge bg-success">Available</span>';
                        }
                    })

                    .catch(err => console.error(err));
            });
        });
    </script>

</body>

</html>