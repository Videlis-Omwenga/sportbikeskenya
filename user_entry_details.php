<?php
session_set_cookie_params(0, '/', '', true, true);
session_start();
include 'config.php';

// Sanitize and validate the input data
function sanitizeInput($data)
{
    return isset($data) ? filter_var(trim($data), FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
}


if (isset($_POST["user_registration"])) {


    $first_name = sanitizeInput($_POST['first_name']);
    $second_name = sanitizeInput($_POST['second_name']);
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    $repeat_password = $_POST['repeat_password'];

    // Passwords match
    if ($password !== $repeat_password) {
        $_SESSION['error'] = "Passwords do not match. Registration failed!";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Check if the user already exists in the database
    $query = "SELECT email FROM users_table WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['registration_error'] = "User with this email already exists. Proceed to login. Registration failed!";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Create a hash of the password using password_hash() for better security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL statement using a prepared statement
    $insert_user_query = "INSERT INTO users_table (first_name, second_name, email, user_password, random_string, date_registered) VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($insert_user_query);
    $randomString = bin2hex(random_bytes(40));
    $stmt->bind_param("sssss", $first_name, $second_name, $email, $hashed_password, $randomString);

    if ($stmt->execute()) {
        // Get the newly inserted user's ID
        $user_id = $stmt->insert_id;

        // Insert user profile data into user_profile table
        $insert_profile_query = "INSERT INTO user_profile (user_id, first_name, second_name, email, random_string, date_registered) VALUES (?, ?, ?, ?, ?, NOW())";
        $profile_stmt = $conn->prepare($insert_profile_query);
        $randomStrings = bin2hex(random_bytes(40));
        $profile_stmt->bind_param("issss", $user_id, $first_name, $second_name, $email, $randomStrings);

        if ($profile_stmt->execute()) {
            $_SESSION['success'] = "Registration successful. Data saved successfully. Proceed to login..... !";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        } else {
            $_SESSION['error'] = "Error: System Error";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        }
    } else {
        $_SESSION['error'] = "Error! System Error";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
} else {
    // User login
    if (isset($_POST['user_login'])) {
        $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
        $password = isset($_POST['password']) ? filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';

        $query = "SELECT * FROM users_table WHERE email = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $hashed_password = $row["user_password"];
            if (password_verify($password, $hashed_password)) {
                $_SESSION["email"] = ucwords($email);

                // Combine random string with session
                $random_access_key = bin2hex(random_bytes(40));
                $_SESSION["random_access_key"] = $random_access_key;

                // Get user id and give session based on the id
                $user_id = $row['id'];
                // Validate and sanitize the user_id
                if (filter_var($user_id, FILTER_VALIDATE_INT) !== false) {
                    $_SESSION["user_identity"] = (int) $user_id;
                } else {
                    // If $user_id is not a valid integer, handle the error (e.g., redirect or show an error message)
                    // For example, redirect to an error page or log the error
                    $_SESSION['error'] = "Incorrect userID details. Session rejected!";
                    $urls = "{$_SERVER['HTTP_REFERER']}";
                    echo '<script type="text/javascript"> window.location = "' . $urls . '";</script>';
                    exit();
                }

                // Set user privileges based on database flags
                $verified = $row['account_verification'];

                if ($verified == 1) {
                    $_SESSION['verified'] = true;
                } else {
                    $_SESSION['verified'] = false;
                }

                $_SESSION['login_success'] = "Log in success. You can now access all system features!";
                $urls = "{$_SERVER['HTTP_REFERER']}";
                echo '<script type="text/javascript"> window.location = "' . $urls . '";</script>';
                exit();
            } else {
                $_SESSION['error'] = "Incorrect password. Login failed!";
                $urls = "{$_SERVER['HTTP_REFERER']}";
                echo '<script type="text/javascript"> window.location = "' . $urls . '";</script>';
                exit();
            }
        }

        $_SESSION['error'] = "User does not exist. Login failed!";
        $urls = "{$_SERVER['HTTP_REFERER']}";
        echo '<script type="text/javascript"> window.location = "' . $urls . '";</script>';
        exit();
    } else {
        if (isset($_POST["edit_profile"])) {

            // Sanitize and validate the input data
            $first_name = sanitizeInput($_POST['first_name']);
            $second_name = sanitizeInput($_POST['second_name']);
            $email = sanitizeInput($_POST['email']);
            $user_contacts = sanitizeInput($_POST['user_contacts']);
            $business_name = sanitizeInput($_POST['business_name']);
            $user_location = sanitizeInput($_POST['user_location']);
            $user_description = sanitizeInput($_POST['user_description']);
            $agreement = sanitizeInput($_POST['agreement']);
            $user_id = sanitizeInput($_POST['user_id']);
            $randomString = bin2hex(random_bytes(40));

            // Prepare the SQL statement for updating user_profile table
            $update_profile_query = "UPDATE user_profile SET first_name = ?, second_name = ?, email = ? , user_contacts =?, user_location= ?, user_description= ?, business_name = ?, date_edited = NOW() WHERE user_id = ?";
            $profile_stmt = $conn->prepare($update_profile_query);
            $profile_stmt->bind_param("ssssssss", $first_name, $second_name, $email, $user_contacts, $user_location, $user_description, $business_name, $user_id);

            // Execute the profile update
            if ($profile_stmt->execute()) {
                // Prepare the SQL statement for updating users_table table
                $update_user_query = "UPDATE users_table SET first_name = ?, second_name = ?, email = ? WHERE id = ?";
                $user_stmt = $conn->prepare($update_user_query);
                $user_stmt->bind_param("ssss", $first_name, $second_name, $email, $user_id);

                // Execute the user update
                if ($user_stmt->execute()) {
                    // Insert data into profile_edit_tracker table
                    $tracker_query = "INSERT INTO `user_profile_edit_tracker`(`first_name`, `second_name`, `email`, `user_contacts`,
                     `user_location`, `user_description`, `random_string`, `business_name`, `date_edited`)
                    VALUES (?, ? , ?, ?, ?, ?, ?, ?, NOW())";
                    $tracker_stmt = $conn->prepare($tracker_query);
                    $tracker_stmt->bind_param("ssssssss", $first_name, $second_name, $email, $user_contacts, $user_location, $user_description, $randomString, $business_name);

                    // Execute the tracker insert
                    if ($tracker_stmt->execute()) {
                        $_SESSION['successs'] = "Profile updated successfully!";
                        header("Location: " . $_SERVER['HTTP_REFERER']);
                        exit;
                    } else {
                        $_SESSION['error'] = "Error: System Error";
                        header("Location: " . $_SERVER['HTTP_REFERER']);
                        exit;
                    }
                } else {
                    $_SESSION['error'] = "Error: System Error";
                    header("Location: " . $_SERVER['HTTP_REFERER']);
                    exit;
                }
            } else {
                $_SESSION['error'] = "Error: System Error";
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit;
            }
        }
    }
}
