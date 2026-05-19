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

// Counting room + available room

$stmt2 = $pdo->query("SELECT COUNT(*) FROM rooms");

$roomCount = $stmt2->fetchColumn();

$stmt22 = $pdo->query("SELECT COUNT(*) FROM rooms WHERE status = 'Available'");
$stmt22->execute();
$availableCount = $stmt22->fetchColumn();

// Counting customer

$stmt3 = $pdo->query("SELECT COUNT(*) FROM customers");

$customerCount = $stmt3->fetchColumn();

// Booking data

$stmt4 = $pdo->query("SELECT COUNT(*) FROM rooms WHERE status = 'Booked'");

$bookingCount = $stmt4->fetchColumn();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Hotel Booking Manager</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: radial-gradient(circle at top left, #eef4ff 0, #f8f9fa 35%, #f8f9fa 100%);

        }

        .summary-card {
            display: block;
            text-align: center;
            padding: 20px;
            border-radius: 12px;
            color: #fff;
            text-decoration: none;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.12);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            position: relative;
            overflow: hidden;
        }

        .summary-card i {
            font-size: 2.2rem;
            margin-bottom: 8px;
        }

        .summary-card h5 {
            margin: 5px 0;
            font-weight: 600;
        }

        .summary-card p {
            margin: 0;
            font-size: 1.4rem;
            font-weight: 700;
        }

        .summary-card::after {
            content: "";
            position: absolute;
            top: -40%;
            right: -40%;
            width: 140%;
            height: 140%;
            background: rgba(255, 255, 255, 0.12);
            transform: rotate(15deg);
            pointer-events: none;
        }

        .summary-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.18);
        }

        .summary-card.btn-primary {
            background: linear-gradient(135deg, #0d6efd, #4dabff);
        }

        .summary-card.btn-danger {
            background: linear-gradient(135deg, #dc3545, #ff6b81);
        }

        .summary-card.btn-success {
            background: linear-gradient(135deg, #198754, #34d399);
        }

        .summary-card.btn-warning {
            background: linear-gradient(135deg, #ffc107, #ffb347);
            color: #212529;
        }
    </style>

</head>

<body>

    <div class="container-fluid">

        <?php require_once(__DIR__ . "/layout/navbarpage.php"); ?>

        <div class="row">

            <!-- Main -->
            <div class="col-12 col-xl-10 p-4">
                <h3 class="fw-bold mb-1">Welcome, Admin!</h3>
                <p class="text-muted mb-3">Here is an overview of your hotel today.</p>
                <div id="alertPlaceholder" class="mt-2"></div>

                <!-- Dashboard -->
                <div class="row mt-4">
                    <div class="col-md-3 mb-3">
                        <a href="roomdetail.php" target="_self" class="summary-card btn-primary">
                            <i class="bi bi-house-door"></i>
                            <h5>Total Rooms</h5>
                            <p><?= $roomCount ?></p>
                        </a>
                    </div>

                    <div class="col-md-3 mb-3">
                        <a href="cusdetail.php" target="_self" class="summary-card btn-danger">
                            <i class="bi bi-people"></i>
                            <h5>Total Customers</h5>
                            <p><?= $customerCount ?></p>
                        </a>
                    </div>

                    <div class="col-md-3 mb-3">
                        <a href="bookinglist.php" target="_self" class="summary-card btn-success">
                            <i class="bi bi-calendar-check"></i>
                            <h5>Current Bookings</h5>
                            <p><?= $bookingCount ?></p>
                        </a>
                    </div>

                    <div class="col-md-3 mb-3">
                        <a href="roomdetail.php" target="_self" class="summary-card btn-warning">
                            <i class="bi bi-door-open"></i>
                            <h5>Available Rooms</h5>
                            <p><?= $availableCount ?></p>
                        </a>
                    </div>
                </div>
                <!--  -->

                <!-- Recent Bookings Table -->
                <div class="card mt-4 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span class="fw-semibold">Recent Bookings</span>
                        <a href="bookinglist.php" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0 align-middle" id="bookingsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Guest Name</th>
                                        <th>Room</th>
                                        <th>Check-In</th>
                                        <th>Check-Out</th>
                                        <th>Status</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent as $key => $doc) { ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($doc["full_name"]); ?></td>
                                            <td><?php echo htmlspecialchars($doc["room_number"]); ?></td>
                                            <td><?php echo htmlspecialchars($doc["check_in"]); ?></td>
                                            <td><?php echo htmlspecialchars($doc["check_out"]); ?></td>
                                            <td>
                                                <?php if ($doc["status"] == "Booked") { ?>
                                                    <span class="badge bg-success">Booked</span>
                                                <?php } ?>
                                                <?php if ($doc["status"] == "Checked In") { ?>
                                                    <span class="badge bg-primary">Checked In</span>
                                                <?php } ?>
                                                <?php if ($doc["status"] == "Checked Out") { ?>
                                                    <span class="badge bg-danger">Checked Out</span>
                                                    <span class="badge bg-success">Paid</span>
                                                <?php } ?>
                                            </td>
                                            <td class="text-end">
                                                <?php if ($doc["status"] == "Booked" || $doc["status"] == "Checked In") { ?>
                                                    <a href="payment.php?booking_id=<?= $doc["booking_id"] ?>" target="_self" class="btn btn-outline-success btn-sm">
                                                        <i class="bi bi-receipt"></i>
                                                    </a>
                                                <?php } ?>
                                                <?php if ($doc["status"] == "Checked Out") { ?>
                                                    <button class="btn btn-outline-success btn-sm" disabled><i class="bi bi-receipt"></i></button>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="mt-4 d-flex flex-wrap gap-3">
                    <a href="addbooking.php" target="_self" class="btn btn-primary px-4 py-3">
                        <i class="bi bi-plus-circle me-1"></i> Add New Booking
                    </a>
                    <a href="manageroom.php" target="_self" class="btn btn-outline-secondary px-4 py-3">
                        <i class="bi bi-door-open me-1"></i> Manage Rooms
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS + Icons -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <script>
        function showAlert(message, type) {
            const alertPlaceholder = document.getElementById('alertPlaceholder');
            const wrapper = document.createElement('div');
            wrapper.innerHTML = `
      <div class="alert alert-${type} alert-dismissible fade show mt-3" role="alert">
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    `;
            alertPlaceholder.append(wrapper);
        }

        function deleteBooking(button) {
            const row = button.closest('tr');
            row.remove();
            showAlert('Booking deleted successfully!', 'danger');
            // Connect with PHP backend here
        }
    </script>

</body>

</html>