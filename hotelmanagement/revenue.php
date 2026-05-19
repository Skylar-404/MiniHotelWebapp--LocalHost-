<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . "/layout/connection.php");

// stmt1. Transaction
$stmt = $pdo->query("
    SELECT b.booking_id, c.full_name, r.room_number, r.room_type, b.check_in, b.check_out, b.status, p.amount
    FROM bookings b
    JOIN payments p ON b.booking_id = p.booking_id
    JOIN customers c ON b.customer_id = c.customer_id
    JOIN rooms r ON b.room_id = r.room_id
    
");
$recent = $stmt->fetchAll(PDO::FETCH_ASSOC);
$booking = $pdo->query("SELECT COUNT(*) FROM bookings");
$totalbooking = $booking->fetchColumn();
// --------------------------------------------------

// stmt2. Admin
$stmt2 = $pdo->query("SELECT * FROM admins");
$employee = $stmt2->fetchAll(PDO::FETCH_ASSOC);
$employeesalary = $pdo->query("SELECT SUM(salary) as employee_salary FROM admins");
$row0 = $employeesalary->fetch(PDO::FETCH_ASSOC);
// -------------------------------------

// stmt3. Maintenance
$stmt3 = $pdo->prepare("
    SELECT m.maintenance_id, m.room_id, m.cause, m.cost, m.maintenance_date, m.notes, r.room_id, r.room_number, r.status
    FROM maintenance m
    JOIN rooms r ON m.room_id = r.room_id
    ");
$stmt3->execute();
$maintenancelist = $stmt3->fetchAll(PDO::FETCH_ASSOC);
$cost = $pdo->query("SELECT SUM(cost) as totalcost FROM maintenance");
$row1 = $cost->fetch(PDO::FETCH_ASSOC);
//-------------------------------------

// revenue
$totalrevenue = $pdo->query("SELECT SUM(amount) as totalrevenue FROM payments");
$row2 = $totalrevenue->fetch(PDO::FETCH_ASSOC);
// ------------------------------------

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Revenue Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .btn-animate {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-animate:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .card:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
        }
    </style>
</head>

<body class="bg-light">

    <?php require_once(__DIR__ . "/layout/navbarpage.php") ?>

    <div class="container py-4">

        <h2 class="mb-4 text-center">Hotel Revenue Dashboard</h2>


        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h6>Total Revenue</h6>
                        <h4 class="text-success"><?= "$" . $row2['totalrevenue'] ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h6>Bookings</h6>
                        <h4 class="text-primary"><?= $totalbooking ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h6>Employee Salary</h6>
                        <h4 class="text-info"><?= "$" . $row0['employee_salary'] ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h6>Maintenance Cost</h6>
                        <h4 class="text-danger"><?= "$" . $row1['totalcost'] ?></h4>
                    </div>
                </div>
            </div>
        </div>


        <!-- Transactions -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title">Recent Transactions</h5>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Customer</th>
                            <th>Room Type</th>
                            <th>Total Cost</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent as $doc): ?>
                            <tr>
                                <td><?= $doc['booking_id'] ?></td>
                                <td><?= $doc['full_name'] ?></td>
                                <td><?= $doc['room_type'] ?></td>
                                <td><?= $doc['amount'] ?></td>
                                <td><span class="badge bg-success">Paid</span></td>
                                <td><button class="btn btn-sm btn-danger btn-animate" disabled><i class="bi bi-trash"></i></button></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Employee -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title">Employee Salary</h5>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Salary</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($employee as $doc): ?>
                            <tr>
                                <td><?= $doc['admin_id'] ?></td>
                                <td><?= $doc['username'] ?></td>
                                <td><?= $doc['role'] ?></td>
                                <td><?= $doc['salary'] ?></td>
                                <td><button class="btn btn-sm btn-primary btn-animate" disabled><i class="bi bi-pencil"></i></button></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Maintenance Cost -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title">Maintenance Cost</h5>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Maintenance ID</th>
                            <th>Room</th>
                            <th>Cause</th>
                            <th>Cost</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($maintenancelist as $doc): ?>
                            <tr>
                                <td><?= $doc["maintenance_id"] ?></td>
                                <td><?= $doc["room_number"] ?></td>
                                <td><?= $doc["cause"] ?></td>
                                <td><?= "$" . $doc["cost"] ?></td>
                                <td><?= $doc["maintenance_date"] ?></td>
                                <td>
                                    <?php if ($doc["status"] == "Available"): ?>
                                        <span class="badge bg-secondary">
                                            Fixed
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td><button class="btn btn-sm btn-success btn-animate" disabled><i class="bi bi-receipt"></i></button></td>
                            </tr>
                        <?php endforeach; ?>
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