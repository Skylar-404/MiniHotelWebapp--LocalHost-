<?php
require_once(__DIR__ . "/layout/connection.php");

$stmt1 = $pdo->prepare("SELECT * FROM rooms");

$stmt1->execute();

$roomlist = $stmt1->fetchAll(PDO::FETCH_ASSOC);

$stmt2 = $pdo->query("SELECT COUNT(*) FROM rooms");

$roomCount = $stmt2->fetchColumn();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Rooms</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
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

        .room-card {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.12);
            border: none;
            background-color: #ffffff;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .room-image-wrapper {
            position: relative;
            height: 180px;
            background: #e5e7eb;
            overflow: hidden;
        }

        .room-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .room-status-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            border-radius: 999px;
            padding: 0.35rem 0.7rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .room-card-body {
            padding: 1rem 1.25rem 1.25rem;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .room-title {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
        }

        .room-number {
            font-size: 1.2rem;
            font-weight: 700;
        }

        .room-type {
            font-size: 0.9rem;
            color: #6b7280;
        }

        .room-meta {
            font-size: 0.9rem;
            color: #6b7280;
        }

        .room-price {
            font-weight: 700;
            font-size: 1.1rem;
        }

        .badge-capacity {
            background-color: #eff6ff;
            color: #1d4ed8;
            border-radius: 999px;
            font-size: 0.78rem;
            padding: 0.25rem 0.6rem;
        }

        .room-footer {
            display: flex;
            justify-content: flex-end;
            margin-top: auto;
        }
    </style>
</head>

<body>

    <?php require_once(__DIR__ . "/layout/navbarpage.php"); ?>

    <div class="container">
        <div class="page-header d-flex flex-wrap justify-content-between align-items-end">
            <div>
                <h2 class="mb-1">Rooms</h2>
                <p class="mb-0">Total rooms: <?= $roomCount ?> available in your property.</p>
            </div>
            <a href="manageroom.php" target="_self" class="btn btn-outline-secondary px-4 py-3">
                <i class="bi bi-door-open me-1"></i> Manage Rooms
            </a>
        </div>

        <div class="row g-4 mb-5">
            <?php foreach ($roomlist as $key => $doc): ?>
                <?php
                $status = $doc["status"];
                $statusClass = "bg-secondary";
                if (strcasecmp($status, "available") === 0) {
                    $statusClass = "bg-success";
                } elseif (strcasecmp($status, "booked") === 0) {
                    $statusClass = "bg-primary";
                } elseif (strcasecmp($status, "maintenance") === 0) {
                    $statusClass = "bg-warning text-dark";
                }
                ?>
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="card room-card">
                        <div class="room-image-wrapper">
                            <?php if (!empty($doc["room_img"])): ?>
                                <img src="<?= htmlspecialchars($doc["room_img"]) ?>" alt="Room image">
                            <?php else: ?>
                                <!-- simple placeholder background if no image -->
                            <?php endif; ?>
                            <span class="badge room-status-badge <?= $statusClass ?>">
                                <?= htmlspecialchars($status) ?>
                            </span>
                        </div>
                        <div class="room-card-body">
                            <div class="room-title mb-1">
                                <span class="room-number">Room <?= htmlspecialchars($doc["room_number"]) ?></span>
                                <span class="room-type">
                                    <i class="bi bi-door-open me-1"></i>
                                    <?= htmlspecialchars($doc["room_type"]) ?>
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge-capacity">
                                    <i class="bi bi-people-fill me-1"></i>
                                    <?= htmlspecialchars($doc["capacity"]) ?> guest(s)
                                </span>
                                <span class="room-price">
                                    $<?= number_format($doc["price"], 2) ?>/night
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (empty($roomlist)): ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        No rooms found. Click “Add New Room” to create one.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>