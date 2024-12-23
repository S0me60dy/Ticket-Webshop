<?php
    include("./db.php");

    class Products {
        // Attributes
        protected $name;
        protected $place;
        protected $start_date;
        protected $end_date;

        // Constructor
        public function __construct($name, $place, $start_date, $end_date) {
            $this->name = $name;
            $this->place = $place;
            $this->start_date = $start_date;
            $this->end_date = $end_date;
        }

        // Getter methods
        public function getName() {
            return $this->name;
        }

        public function getPlace() {
            return $this->place;
        }

        public function getStartDate() {
            return $this->start_date;
        }

        public function getEndDate() {
            return $this->end_date;
        }

        // Static method to fetch products data
        public static function getProductsData($link) {
            // Use a prepared statement to prevent SQL injection
            $query_products = "SELECT Name, Venue, Start_Date, End_Date FROM Events WHERE Active = ?";

            $stmt = mysqli_prepare($link, $query_products);
            if (!$stmt) {
                die("Error preparing statement: " . mysqli_error($link));
            }

            // Bind parameters to the query
            $active = 1; // Only fetch active events
            mysqli_stmt_bind_param($stmt, 'i', $active);

            // Execute the prepared statement
            if (!mysqli_stmt_execute($stmt)) {
                die("Error executing statement: " . mysqli_stmt_error($stmt));
            }

            // Bind the result set to variables
            mysqli_stmt_bind_result($stmt, $name, $venue, $startDate, $endDate);

            // Fetch data and store it in the products array
            $products = [];
            while (mysqli_stmt_fetch($stmt)) {
                $products[] = new Products($name, $venue, $startDate, $endDate);
            }

            mysqli_stmt_close($stmt);

            return $products;
        }

        public function display() {
            return "
                <tr>
                    <td><a href='tickets.php?event_name=" . urlencode($this->name) . "'>{$this->name}</a></td>
                    <td>{$this->place}</td>
                    <td>{$this->start_date}</td>
                    <td>{$this->end_date}</td>
                </tr>
            ";
        }
    }

    $products = Products::getProductsData($link);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="./assets/products_styling.css">
</head>
<body>
    
    <h1>Available Products</h1>
    <?php if (empty($products)): ?>
    <p>No products available at the moment.</p>
<?php else: ?>
    <ul class="products">
        <?php foreach ($products as $product): ?>
            <li class="products-elements">
                <div class="event <?php echo htmlspecialchars($product->getName()); ?>">
                    <strong>
                        <a href="tickets.php?event_name=<?php echo urlencode($product->getName()); ?>">
                            <?php echo htmlspecialchars($product->getName()); ?>
                        </a>
                    </strong><br>
                    Place: <?php echo htmlspecialchars($product->getPlace()); ?><br>
                    Start Date: <?php echo htmlspecialchars($product->getStartDate()); ?><br>
                    End Date: <?php echo htmlspecialchars($product->getEndDate()); ?><br>
                </div>
            </li>
        <?php endforeach; ?> 
    </ul>
<?php endif; ?>

</body>
</html>

    