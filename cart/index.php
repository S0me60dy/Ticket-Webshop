<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle adding tickets to the cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_tickets'])) {
    foreach ($_POST['selected_tickets'] as $ticket_json) {
        $ticket = json_decode($ticket_json, true);

        // Check if the ticket is already in the cart
        $found = false;
        foreach ($_SESSION['cart'] as &$cart_item) {
            if ($cart_item['ticket_id'] === $ticket['ticket_id']) {
                $cart_item['quantity']++;
                $found = true;
                break;
            }
        }

        if (!$found) {
            // Add new ticket to the cart
            $_SESSION['cart'][] = [
                'ticket_id' => $ticket['ticket_id'],
                'ticket_name' => $ticket['ticket_name'],
                'price' => $ticket['price'],
                'quantity' => 1
            ];
        }
    }

    header('Location: index.php');
    exit();
}

// Handle updating quantities or removing items
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantities'] as $ticket_id => $quantity) {
        foreach ($_SESSION['cart'] as $key => &$item) {
            if ($item['ticket_id'] == $ticket_id) {
                if ($quantity == 0) {
                    // Remove the item if quantity is 0
                    unset($_SESSION['cart'][$key]);
                } else {
                    // Update the quantity
                    $item['quantity'] = (int)$quantity;
                }
                break;
            }
        }
    }

    // Re-index the cart array
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <?php
        if(isset($_SESSION['permissions'])){
            if($_SESSION['permissions'] === 'admin'){
                header("Location: admin/index.php");
            } else if($_SESSION['permissions'] === 'normal'){
                include '../assets/header_registered.html';
            } else {
                include '../assets/header.html'; 
            }
        } else {
            include '../assets/header.html';
        }
    ?>
    <h1>Your Shopping Cart</h1>
    <?php if (empty($_SESSION['cart'])): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <form action="index.php" method="POST">
            <table border="1">
                <thead>
                    <tr>
                        <th>Ticket Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['cart'] as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['ticket_name']); ?></td>
                            <td><?php echo htmlspecialchars($item['price']); ?> USD</td>
                            <td>
                                <input type="number" name="quantities[<?php echo htmlspecialchars($item['ticket_id']); ?>]" value="<?php echo htmlspecialchars($item['quantity']); ?>" min="0">
                            </td>
                            <td><?php echo htmlspecialchars($item['price'] * $item['quantity']); ?> USD</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p><strong>Total: <?php echo array_sum(array_map(function ($item) {
                return $item['price'] * $item['quantity'];
            }, $_SESSION['cart'])); ?> USD</strong></p>
            <button type="submit" name="update_cart" value="Update">Update Cart</button>
        </form>
        <a href="checkout.php">Proceed to Checkout</a>
    <?php endif; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>