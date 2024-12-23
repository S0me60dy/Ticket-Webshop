<?php
    include("./db.php");
    session_start();
    echo "<h2>Register Page</h2>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
</head>
<body>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']?>">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br>
        
        <label for="birthdate">Birth Date:</label><br>
        <input type="date" id="birthdate" name="birthdate" required><br>
        
        <label for="pass">Password:</label><br>
        <input type="password" name="pass" id="pass" required><br>
        
        <input type="submit" name="submit" value="Register">
    </form>



<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {  
    $name = trim($_POST['name']);
    $birthdate = trim($_POST['birthdate']);
    $password = trim($_POST['pass']);

    if (empty($name) || empty($birthdate) || empty($password)) {
        echo "<p style='color: red;'>All fields are required.</p>";
    } else {
        $name = htmlspecialchars($name);
        $birthdate = htmlspecialchars($birthdate);
        $hashed_pwd = password_hash($password, PASSWORD_DEFAULT);
        $_SESSION['name'] = $name;
        $_SESSION['permissions'] = 'normal';

        $query_customers = "INSERT INTO Customers (Name, Date_of_Birth, Password, Permissions, Active)    
                    VALUES (?, ?, ?, 'normal', '1')";
        $stmt = mysqli_prepare($link, $query_customers);
        mysqli_stmt_bind_param($stmt, "sss", $name, $birthdate, $hashed_pwd);
        if (mysqli_stmt_execute($stmt)) {
            echo "<p style='color: green;'>Registration successful! Your data has been processed.</p>";
            mysqli_stmt_close($stmt);
            mysqli_close($link);
            header("Location: index.php");
            exit();
        } else {
            echo "<p style='color: red;'>Error during registration: " . mysqli_error($link) . "</p>";
        }
        mysqli_stmt_close($stmt);
        mysqli_close($link);
    }
}
?>
</body>
</html>
