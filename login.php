<?php
    include("./db.php");
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
</head>
<body>
    <h2>Log in page</h2>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']?>">
        <label for="name">Name:</label><br>
        <input type="text" name="name" id="name" required><br>
        <label for="pass">Password:</label><br>
        <input type="password" name="pass" id="pass" required><br>
        <input type="submit" value="Log in" name="submit">
    </form>
<?php
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $name = trim($_POST['name']);
        $pass = trim($_POST['pass']);
        if(empty($pass) || empty($name)){
            echo("<p style='color: red;'>Both fieds are required!(Passive aggressive)</p>");
        } else {
            $query_check_login = "SELECT Customer_ID, Password, Permissions FROM customers WHERE Name LIKE ?";
            $stmt = mysqli_prepare($link, $query_check_login);
            mysqli_stmt_bind_param($stmt, "s", $name);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $customer_ID, $hashed_pwd, $permissions);
            mysqli_stmt_fetch($stmt);
            if($customer_ID && password_verify($pass, $hashed_pwd)){
                $_SESSION['customer_ID'] = $customer_ID;
                $_SESSION['permissions'] = $permissions;
                $_SESSION['name'] = $name;
                header("Location: index.php");
                exit();
            } else {
                echo("<p style='color:red;'>The username or password are incorrect!(Not so aggressive, but still passive)");
            }
            mysqli_stmt_close($stmt);
        }
    }

    mysqli_close($link);
?>
</body>
</html>