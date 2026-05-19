<?php
require_once(__DIR__ . "/layout/connection.php");

$days = null;
$totalCost = null;

$stmt0 = $pdo->prepare("SELECT b.booking_id, b.check_in, b.check_out, b.status,
                            c.full_name, c.email, c.phone,
                            r.room_id, r.room_number, r.room_type, r.price
                            FROM bookings b
                            JOIN customers c ON b.customer_id = c.customer_id
                            JOIN rooms r ON b.room_id = r.room_id
                            ");
$stmt0->execute();
$cusdetail = $stmt0->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Customer Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
</head>

<body>

    <?php require_once(__DIR__ . "/layout/navbarpage.php"); ?>

    <div class="container">
        <div class="page-header d-flex flex-wrap justify-content-between align-items-end">
            <div>
                <h2 class="mb-1">Customer Details</h2>
                <p class="mb-0">Overview of all guests, their stays, and billing information.</p>
            </div>
            <a href="addbooking.php" class="btn btn-primary mt-3 mt-sm-0">
                <i class="bi bi-plus-circle me-1"></i> Add New Booking
            </a>
        </div>

        <div class="card mb-5">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0" id="bookingsTable">
                        <thead class="table-light">
                            <tr>
                                <th>Guest Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Room</th>
                                <th class="text-center">Night(s)</th>
                                <th class="text-end">Amount</th>
                                <th>Check-In</th>
                                <th>Check-Out</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cusdetail as $doc): ?>
                                <?php
                                $checkIn  = strtotime($doc['check_in']);
                                $checkOut = strtotime($doc['check_out']);
                                $days = max(1, ($checkOut - $checkIn) / 86400); // at least 1 night

                                $pricePerNight = $doc['price'];
                                $totalCost = $days * $pricePerNight;

                                $status = $doc["status"];
                                $statusClass = "bg-secondary";
                                if ($status === "Booked") {
                                    $statusClass = "bg-warning text-dark";
                                } elseif ($status === "Checked In") {
                                    $statusClass = "bg-success";
                                } elseif ($status === "Checked Out") {
                                    $statusClass = "bg-danger";
                                } elseif ($status === "Cancelled") {
                                    $statusClass = "bg-danger";
                                }
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($doc["full_name"]) ?></td>
                                    <td><?= htmlspecialchars($doc["email"]) ?></td>
                                    <td><?= htmlspecialchars($doc["phone"]) ?></td>
                                    <td><?= htmlspecialchars($doc["room_number"]) ?> (<?= htmlspecialchars($doc["room_type"]) ?>)</td>
                                    <td class="text-center"><?= $days ?></td>
                                    <td class="text-end amount-cell">$<?= number_format($totalCost, 2) ?></td>
                                    <td><?= htmlspecialchars($doc["check_in"]) ?></td>
                                    <td><?= htmlspecialchars($doc["check_out"]) ?></td>
                                    <td>
                                        <span class="badge badge-status <?= $statusClass ?>">
                                            <?= htmlspecialchars($status) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($doc["status"] == "Booked" || $doc["status"] == "Checked In") { ?>
                                            <a href="payment.php?booking_id=<?= $doc["booking_id"] ?>" target="_self" class="btn btn-outline-success btn-sm" title="View Payment">
                                                <i class="bi bi-receipt"></i>
                                            </a>
                                        <?php } ?>
                                        <?php if ($doc["status"] == "Checked Out") { ?>
                                            <button class="btn btn-outline-success btn-sm" disabled><i class="bi bi-receipt"></i></button>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($cusdetail)): ?>
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-4">
                                        No customer records found.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

</body>

</html>