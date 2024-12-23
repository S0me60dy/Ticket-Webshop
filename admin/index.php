<?php
session_start();
if (!isset($_SESSION['permissions']) || $_SESSION['permissions'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <header class="p-3 text-bg-dark">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <a href="../index.php" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                    <h1>Admin Panel</h1>
                </a>
                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="manage_users.php" class="nav-link px-2 text-white">Manage Users</a></li>
                    <li><a href="manage_tickets.php" class="nav-link px-2 text-white">Manage Tickets</a></li>
                    <li><a href="manage_events.php" class="nav-link px-2 text-white">Manage Events</a></li>
                </ul>
                <div class="text-end">
                    <a href="../logout.php" class="btn btn-danger">Logout</a>
                </div>
            </div>
        </div>
    </header>
    <div class="container mt-4">
        <h2>Welcome, Admin</h2>
        <p>Here you can manage users, tickets, and events for the platform.</p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
