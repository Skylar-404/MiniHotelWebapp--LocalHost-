<?php

require_once(__DIR__ . "/layout/connection.php");

$stmt = $pdo->prepare("
    SELECT b.booking_id, c.full_name, r.room_number, b.check_in, b.check_out, b.status
    FROM bookings b
    JOIN customers c ON b.customer_id = c.customer_id
    JOIN rooms r ON b.room_id = r.room_id
");

$stmt->execute();

$recent = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Booking List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<style>
    body {
        background: radial-gradient(circle at top left, #eef4ff 0, #f8f9fa 35%, #f8f9fa 100%);
    }

    .page-header {
        margin-top: 2rem;
        margin-bottom: 1.5rem;
    }

    .page-header h2 {
        font-weight: 700;
    }

    .page-header p {
        color: #6c757d;
    }

    .card {
        border-radius: 12px;
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
    }

    .table thead th {
        white-space: nowrap;
    }

    .badge-status {
        font-size: 0.8rem;
        padding: 0.4rem 0.6rem;
        border-radius: 999px;
    }

    .amount-cell {
        font-weight: 600;
    }
</style>

<body>

    <?php require_once(__DIR__ . "/layout/navbarpage.php"); ?>

    <div class="container">
        <div class="page-header d-flex flex-wrap justify-content-between align-items-end">
            <div>
                <h2 class="mb-1">Booking Details</h2>
                <p class="mb-0">Overview of all bookings, and billing information.</p>
            </div>
            <a href="addbooking.php" class="btn btn-primary mt-3 mt-sm-0">
                <i class="bi bi-plus-circle me-1"></i> Add New Booking
            </a>
        </div>

        <!-- Bookings Table -->
        <div class="card mb-5">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0" id="bookingsTable">
                        <thead class="table-light">
                            <tr>
                                <th>Guest Name</th>
                                <th>Room</th>
                                <th>Check-In</th>
                                <th>Check-Out</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent as $key => $doc) { ?>
                                <tr>
                                    <td><?php echo $doc["full_name"] ?></td>
                                    <td><?php echo $doc["room_number"] ?></td>
                                    <td><?php echo $doc["check_in"] ?></td>
                                    <td><?php echo $doc["check_out"] ?></td>
                                    <?php if ($doc["status"] == "Booked") { ?>
                                        <td>
                                            <span class="badge bg-primary"><?= $doc["status"] ?></span>
                                        </td>
                                    <?php } ?>
                                    <?php if ($doc["status"] == "Checked In") { ?>
                                        <td>
                                            <span class="badge bg-success"><?= $doc["status"] ?></span>
                                        </td>
                                    <?php } ?>
                                    <?php if ($doc["status"] == "Checked Out") { ?>
                                        <td>
                                            <span class="badge bg-danger"><?= $doc["status"] ?></span>
                                        </td>
                                    <?php } ?>
                                    <?php if ($doc["status"] == "Cancelled") { ?>
                                        <td>
                                            <span class="badge bg-danger"><?= $doc["status"] ?></span>
                                        </td>
                                    <?php } ?>
                                    <td>
                                        <a href="payment.php?booking_id=<?= $doc["booking_id"] ?>" target="_self" class="btn btn-success btn-sm">
                                            <i class="bi bi-receipt"></i>
                                        </a>
                                        <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
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
</body>

</html>