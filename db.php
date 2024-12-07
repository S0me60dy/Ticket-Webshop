<?php 
    $config = include "./config.php";

    // Validate config keys
    $db_host = $config['DATABASE_HOSTNAME'] ?? die("DATABASE_HOST not defined in config.php");
    $db_user = $config['DATABASE_USERNAME'] ?? die("DATABASE_USERNAME not defined in config.php");
    $db_pass = $config['DATABASE_PASSWORD'] ?? die("DATABASE_PASSWORD not defined in config.php");
    $db_name = $config['DATABASE_NAME'] ?? die("DATABASE_NAME not defined in config.php");

    // Establish connection
    $link = mysqli_connect($db_host, $db_user, $db_pass) or die("Error establishing the connection with a DB: " . mysqli_connect_error());
    mysqli_select_db($link, $db_name) or die("Error connecting to the DB: " . mysqli_error($link));

    // Execute query
    $query = "SELECT * FROM customers";
    $result = mysqli_query($link, $query) or die("Error executing query: " . mysqli_error($link));

    // Display results
    echo("<h2>Records</h2>");
    echo("<table><tr><th>Customer_ID</th><th>Name</th><th>Date_of_Birth</th><th>Password</th><th>Permissions</th><th>Active</th></tr>");
    while ($row = mysqli_fetch_array($result)) {
        echo(
            "<tr><td>" . htmlspecialchars($row['Customer_ID']) . "</td>
            <td>" . htmlspecialchars($row['Name']) . "</td>
            <td>" . htmlspecialchars($row['Date_of_Birth']) . "</td>
            <td>" . htmlspecialchars($row['Password']) . "</td>
            <td>" . htmlspecialchars($row['Permissions']) . "</td>
            <td>" . htmlspecialchars($row['Active']) . "</td></tr>"
        );
    }
    echo("</table>");
    echo("<div><a href='./register.php'>Register here</a></div>");

