<?php
session_start();
include 'config.php';

if (isset($_POST["report_post"])) {
    // Sanitize and validate user inputs
    $reported_by = isset($_POST['reported_by']) ? htmlspecialchars(trim($_POST['reported_by'])) : '';
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0; 
    $string_id = isset($_POST['string_id']) ? htmlspecialchars(trim($_POST['string_id'])) : '';
    $issue_details = isset($_POST['issue_details']) ? htmlspecialchars(trim($_POST['issue_details'])) : '';
    $track_item = isset($_POST['track_item']) ? htmlspecialchars(trim($_POST['track_item'])) : '';

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO `reported_posts`(`reported_by`, `date_reported`, `issue_details`, `post_id`, `string_id`, track_item) 
    VALUES (?, NOW(), ?, ?, ?, ?)");

    // Bind parameters
    $stmt->bind_param("sssss", $reported_by, $issue_details, $post_id, $string_id, $track_item);

    if ($stmt->execute()) {
        // Report submitted successfully
        $_SESSION['successs'] = "Report submitted successfully. An email will be sent with action taken.";
        header("Location: " . $_SERVER['HTTP_REFERER']); // Redirect using HTTP header
        exit;
    } else {
        $_SESSION['error'] = "Error: System error";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
  
}else{
    if (isset($_POST["report_post_gear"])) {
        // Sanitize and validate user inputs
        $reported_by = isset($_POST['reported_by']) ? htmlspecialchars(trim($_POST['reported_by'])) : '';
        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        $string_id = isset($_POST['string_id']) ? htmlspecialchars(trim($_POST['string_id'])) : '';
        $issue_details = isset($_POST['issue_details']) ? htmlspecialchars(trim($_POST['issue_details'])) : '';
        $track_item = isset($_POST['track_item']) ? htmlspecialchars(trim($_POST['track_item'])) : '';

        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO `reported_posts`(`reported_by`, `date_reported`, `issue_details`, `post_id`, `string_id`, track_item) 
    VALUES (?, NOW(), ?, ?, ?, ?)");

        // Bind parameters
        $stmt->bind_param("sssss", $reported_by, $issue_details, $post_id, $string_id, $track_item);

        if ($stmt->execute()) {
            // Report submitted successfully
            $_SESSION['successs'] = "Report submitted successfully. An email will be sent with action taken.";
            header("Location: " . $_SERVER['HTTP_REFERER']); // Redirect using HTTP header
            exit;
        } else {
            $_SESSION['error'] = "Error: System error";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }else{
        if (isset($_POST["Bike_parts_tracking"])) {
            // Sanitize and validate user inputs
            $reported_by = isset($_POST['reported_by']) ? htmlspecialchars(trim($_POST['reported_by'])) : '';
            $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
            $string_id = isset($_POST['string_id']) ? htmlspecialchars(trim($_POST['string_id'])) : '';
            $issue_details = isset($_POST['issue_details']) ? htmlspecialchars(trim($_POST['issue_details'])) : '';
            $track_item = isset($_POST['track_item']) ? htmlspecialchars(trim($_POST['track_item'])) : '';

            // Prepare the SQL statement
            $stmt = $conn->prepare("INSERT INTO `reported_posts`(`reported_by`, `date_reported`, `issue_details`, `post_id`, `string_id`, track_item) 
    VALUES (?, NOW(), ?, ?, ?, ?)");

            // Bind parameters
            $stmt->bind_param("sssss", $reported_by, $issue_details, $post_id, $string_id, $track_item);

            if ($stmt->execute()) {
                // Report submitted successfully
                $_SESSION['successs'] = "Report submitted successfully. An email will be sent with action taken.";
                header("Location: " . $_SERVER['HTTP_REFERER']); // Redirect using HTTP header
                exit;
            } else {
                $_SESSION['error'] = "Error: System error";
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit;
            }
        }
    }
}
