<?php
    require_once 'db.php';

    if (!isset($_GET['event_name']) || empty($_GET['event_name'])) {
        die("No event selected.");
    }

    $event_name = $_GET['event_name'];

    // Use a prepared statement to fetch tickets for the specific event
    $query_tickets = "
        SELECT t.Ticket_ID, t.Name AS Ticket_Name, t.Price, t.Expiration_Date, t.Type, t.Active
        FROM tickets t
        JOIN events e ON t.Event_ID = e.Event_ID
        WHERE t.Active = 1 AND e.Name = ?
    ";

    $stmt = mysqli_prepare($link, $query_tickets);
    if (!$stmt) {
        die("Error preparing statement: " . mysqli_error($conn));
    }

    // Bind the input parameter to the query
    mysqli_stmt_bind_param($stmt, 's', $event_name);

    // Execute the statement
    if (!mysqli_stmt_execute($stmt)) {
        die("Error executing statement: " . mysqli_stmt_error($stmt));
    }

    // Bind the result variables
    mysqli_stmt_bind_result($stmt, $ticket_id, $ticket_name, $price, $expiration_date, $type, $active);

    // Fetch tickets data
    $tickets = [];
    while (mysqli_stmt_fetch($stmt)) {
        $tickets[] = [
            'ticket_id' => $ticket_id,
            'ticket_name' => $ticket_name,
            'price' => $price,
            'expiration_date' => $expiration_date,
            'type' => $type,
            'active' => $active
        ];
    }

    // Close the statement
    mysqli_stmt_close($stmt);

    // Close the database connection
    mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets for <?php echo htmlspecialchars($event_name); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <?php
        session_start();
        if(isset($_SESSION['permissions'])){
            if($_SESSION['permissions'] === 'admin'){
                header("Location: admin/index.php");
            } else if($_SESSION['permissions'] === 'normal'){
                include './assets/header_registered.html';
            } else {
                include './assets/header.html'; 
            }
        } else {
            include './assets/header.html';
        }
    ?>
    <h1>Tickets for <?php echo htmlspecialchars($event_name); ?></h1>

    <?php if (count($tickets) > 0): ?>
        <form action="cart/index.php" method="POST">
            <table border="1">
                <thead>
                    <tr>
                        <th>Ticket Name</th>
                        <th>Price</th>
                        <th>Expiration Date</th>
                        <th>Type</th>
                        <th>Add to cart</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tickets as $ticket): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($ticket['ticket_name']); ?></td>
                            <td><?php echo htmlspecialchars($ticket['price']); ?> USD</td>
                            <td><?php echo htmlspecialchars($ticket['expiration_date']); ?></td>
                            <td><?php echo htmlspecialchars($ticket['type']); ?></td>
                            <td>
                                <input type="checkbox" name="selected_tickets[]" value="<?php echo htmlspecialchars(json_encode($ticket)); ?>">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit">Add to Cart</button>
        </form>
    <?php else: ?>
        <p>No tickets available for this event.</p>
    <?php endif; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
