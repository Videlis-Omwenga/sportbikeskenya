<?php
session_start();
include 'config.php';


$user_cookie_id = $_COOKIE['user_cookie'] ?? '';

// Check if user's cookie exists in the database
$user_cookie = $_COOKIE['user_cookie'] ?? '';

if (!empty($user_cookie)) {
    $query = "SELECT user_id FROM user_cookies WHERE cookie_value = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $user_cookie);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Cookie exists, do not count view
        $cookie_exists = true;
    } else {
        // Cookie doesn't exist, store it in the database
        $cookie_exists = false;
        $query = "INSERT INTO user_cookies (user_id, cookie_value) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $user_cookie_id, $user_cookie);
        $stmt->execute();
    }
    $stmt->close();
}

if (empty($user_cookie)) {
    $user_cookie = uniqid(); 
    setcookie('user_cookie', $user_cookie, time() + 86400, '/');
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html>

<head>
    <title>View Count Example</title>
</head>

<body>
    <h1>Welcome to the Website</h1>
    <?php
    if (!$cookie_exists) {
        echo "<p>View counted!</p>";
    } else {
        echo "<p>View not counted.</p>";
    }
    ?>
</body>

</html>