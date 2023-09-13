<?php
session_start();
include 'config.php';

if (isset($_POST["submit_post"])) {
    // Collect and sanitize input data
    $system_user = isset($_POST['system_user']) ? $_POST['system_user'] : '';
    $bike_name = isset($_POST['bike_name']) ? $_POST['bike_name'] : '';
    $bike_year = isset($_POST['bike_year']) ? $_POST['bike_year'] : '';
    $bike_cc = isset($_POST['bike_cc']) ? $_POST['bike_cc'] : '';
    $bike_mileage = isset($_POST['bike_mileage']) ? $_POST['bike_mileage'] : '';
    $bike_location = isset($_POST['bike_location']) ? $_POST['bike_location'] : '';
    $bike_price = isset($_POST['bike_price']) ? $_POST['bike_price'] : '';
    $bike_color = isset($_POST['bike_color']) ? $_POST['bike_color'] : '';
    $logbook_availability = isset($_POST['logbook_availability']) ? $_POST['logbook_availability'] : '';
    $bike_condition = isset($_POST['bike_condition']) ? $_POST['bike_condition'] : '';
    $bike_details = isset($_POST['bike_details']) ? $_POST['bike_details'] : '';
    $terms_agreement = isset($_POST['terms_agreement']) ? $_POST['terms_agreement'] : '';

    // Generate a unique random string
    $randomString = bin2hex(random_bytes(9));

    // Prepare the INSERT statement
    $stmt = $conn->prepare("INSERT INTO `bike_details_table`(`bike_name`, `bike_year`, 
    `bike_cc`, `bike_mileage`, `bike_location`, `bike_price`, `bike_color`, `logbook_availability`, 
    `bike_condition`, `bike_details`, `terms_agreement`, `random_string_id`, `date_posted`, posted_by) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)");

    // Bind parameters and execute the statement
    $stmt->bind_param(
        "sssssssssssss",
        $bike_name,
        $bike_year,
        $bike_cc,
        $bike_mileage,
        $bike_location,
        $bike_price,
        $bike_color,
        $logbook_availability,
        $bike_condition,
        $bike_details,
        $terms_agreement,
        $randomString,
        $system_user
    );

    // Execute the statement
    if ($stmt->execute()) {
        $_SESSION['done'] = "Bike details saved successfully.";
    } else {
        $_SESSION['error'] = "Error saving bike details: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();

    // Redirect back to the form page
    header('Location: sell_bike.php');
    exit;
} else {
    if (isset($_POST["submit_image"])) {
        function generateUniqueFileName($directory, $fileExtension)
        {
            $length = 9;
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $randomString = '';

            do {
                for ($i = 0; $i < $length; $i++) {
                    $randomString .= $characters[rand(0, strlen($characters) - 1)];
                }
                $newFileName = $randomString . '.' . $fileExtension;
            } while (file_exists($directory . $newFileName));

            return $newFileName;
        }

        // Sanitize input data
        $post_id = isset($_POST['post_id']) ? $_POST['post_id'] : '';
        // Generate a unique random string
        $string_id = bin2hex(random_bytes(9));

        // Validate and process file uploads
        $allowedExtensions = array('png', 'jpeg', 'jpg');
        $uploadedFiles = array_filter($_FILES['bike_images']['name']);
        $imageNames = array();

        if (!empty($uploadedFiles)) {
            $uploadDir = 'seller_images/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            foreach ($_FILES['bike_images']['tmp_name'] as $key => $tmpName) {
                $fileInfo = pathinfo($_FILES['bike_images']['name'][$key]);
                $fileExtension = strtolower($fileInfo['extension']);

                if (in_array($fileExtension, $allowedExtensions)) {
                    $newFileName = generateUniqueFileName($uploadDir, $fileExtension);
                    $targetFilePath = $uploadDir . $newFileName;

                    // Check if the image is landscape
                    $imgSize = getimagesize($tmpName);
                    $imgWidth = $imgSize[0];
                    $imgHeight = $imgSize[1];

                    if ($imgWidth > $imgHeight) {
                        if (move_uploaded_file($tmpName, $targetFilePath)) {
                            $imageNames[] = $newFileName;
                        }
                    }
                }
            }
        }

        if (empty($imageNames)) {
            // No landscape images were uploaded, display an error message
            $_SESSION['error'] = "Please upload landscape images only.";
            header('Location: sell_bike.php');
            exit;
        }

        // Prepare the INSERT statement for image names
        $stmt = $conn->prepare("INSERT INTO `images_table` (`post_id`, `image_name`, `date_uploaded`, string_id) VALUES (?, ?, NOW(), ?)");

        // Bind parameters and execute the statement
        $stmt->bind_param("sss", $post_id, $imageName, $string_id);

        // Execute the statement
        foreach ($imageNames as $imageName) {
            $stmt->execute();
        }

        // Check if any rows were inserted successfully
        if ($stmt->affected_rows > 0) {
            $_SESSION['done'] = "Bike images saved successfully. Only landscape images were saved!";
        } else {
            $_SESSION['error'] = "Error saving bike images.";
        }

        // Close the statement
        $stmt->close();

        // Redirect back to the form page with success or error message
        header('Location: sell_bike.php');
        exit;
    } else {
        if (isset($_POST["edit_post"])) {
            // Collect and sanitize input data
            $bike_id = isset($_POST['bike_id']) ? $_POST['bike_id'] : '';
            $string_id = isset($_POST['string_id']) ? $_POST['string_id'] : '';
            $bike_name = isset($_POST['bike_name']) ? $_POST['bike_name'] : '';
            $bike_year = isset($_POST['bike_year']) ? $_POST['bike_year'] : '';
            $bike_cc = isset($_POST['bike_cc']) ? $_POST['bike_cc'] : '';
            $bike_mileage = isset($_POST['bike_mileage']) ? $_POST['bike_mileage'] : '';
            $bike_location = isset($_POST['bike_location']) ? $_POST['bike_location'] : '';
            $bike_price = isset($_POST['bike_price']) ? $_POST['bike_price'] : '';
            $bike_color = isset($_POST['bike_color']) ? $_POST['bike_color'] : '';
            $logbook_availability = isset($_POST['logbook_availability']) ? $_POST['logbook_availability'] : '';
            $bike_condition = isset($_POST['bike_condition']) ? $_POST['bike_condition'] : '';
            $bike_details = isset($_POST['bike_details']) ? $_POST['bike_details'] : '';

            // Generate a unique random string
            $randomString = bin2hex(random_bytes(9));

            // Start the transaction
            $conn->begin_transaction();

            try {
                // Prepare the UPDATE statement for `bike_details_table`
                $updateStmt = $conn->prepare("UPDATE `bike_details_table` SET `bike_name` = ?, `bike_year` = ?, `bike_cc` = ?, 
                `bike_mileage` = ?, `bike_location` = ?, `bike_price` = ?, `bike_color` = ?, `logbook_availability` = ?, `bike_condition` = ?, 
                `bike_details` = ? WHERE id = ? AND random_string_id = ?");

                // Bind parameters and execute the UPDATE statement
                $updateStmt->bind_param(
                    "ssssssssssss",
                    $bike_name,
                    $bike_year,
                    $bike_cc,
                    $bike_mileage,
                    $bike_location,
                    $bike_price,
                    $bike_color,
                    $logbook_availability,
                    $bike_condition,
                    $bike_details,
                    $bike_id,
                    $string_id,
                );

                // Execute the UPDATE statement
                $updateStmt->execute();

                // Prepare the INSERT statement for `bike_details_tracking_table`
                $insertStmt = $conn->prepare("INSERT INTO `bike_details_tracking_table` (`bike_id`, `bike_name`, `bike_year`, `bike_cc`, `bike_mileage`, 
                `bike_location`, `bike_price`, `bike_color`, `logbook_availability`, `bike_condition`, `bike_details`, date_edited, random_string_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)");

                // Bind parameters and execute the INSERT statement
                $insertStmt->bind_param(
                    "ssssssssssss",
                    $bike_id,
                    $bike_name,
                    $bike_year,
                    $bike_cc,
                    $bike_mileage,
                    $bike_location,
                    $bike_price,
                    $bike_color,
                    $logbook_availability,
                    $bike_condition,
                    $bike_details,
                    $randomString
                );

                // Execute the INSERT statement
                $insertStmt->execute();

                // Commit the transaction
                $conn->commit();

                $_SESSION['done'] = "Bike details edited successfully.";

                // Close the prepared statements
                $updateStmt->close();
                $insertStmt->close();
            } catch (Exception $e) {
                // Roll back the transaction if an error occurs
                $conn->rollback();

                $_SESSION['error'] = "Error saving bike details: " . $e->getMessage();
            }

            // Redirect back to the form page
            header('Location: sell_bike.php');
            exit;
        } else {
            if (isset($_POST["delete_post"])) {
                $bike_id = isset($_POST['bike_id']) ? $_POST['bike_id'] : '';
                $string_id = isset($_POST['string_id']) ? $_POST['string_id'] : '';
                $deleted = isset($_POST['deleted']) ? $_POST['deleted'] : '';

                // Generate a unique random string
                $randomString = bin2hex(random_bytes(9));

                // Start the transaction
                $conn->begin_transaction();

                try {

                    $updateStmt = $conn->prepare("UPDATE `bike_details_table` SET `post_delete` = ?, `date_deleted` = NOW()  WHERE id = ? AND random_string_id = ?");
                    $updateStmt->bind_param(
                        "sss",
                        $deleted,
                        $bike_id,
                        $string_id,
                    );
                    $updateStmt->execute();

                    // Table 2
                    $insertStmt = $conn->prepare("INSERT INTO `deleted_bike_tracker` (`bike_id`, date_delete, random_string) VALUES (?, NOW(), ?)");
                    $insertStmt->bind_param(
                        "ss",
                        $bike_id,
                        $randomString,
                    );

                    $insertStmt->execute();
                    $conn->commit();

                    $_SESSION['done'] = "Post deleted successfully.";

                    // Close the prepared statements
                    $updateStmt->close();
                    $insertStmt->close();
                } catch (Exception $e) {
                    // Roll back the transaction if an error occurs
                    $conn->rollback();

                    $_SESSION['error'] = "Error saving bike details: " . $e->getMessage();
                }

                // Redirect back to the form page
                header('Location: sell_bike.php');
                exit;
            } else {
                if (isset($_POST["sold_bike"])) {
                    $bike_id = isset($_POST['bike_id']) ? $_POST['bike_id'] : '';
                    $string_id = isset($_POST['string_id']) ? $_POST['string_id'] : '';
                    $sold = isset($_POST['sold']) ? $_POST['sold'] : '';

                    // Check if the post has images, if not, reject the request
                    $stmt = $conn->prepare("SELECT post_id FROM `images_table` WHERE post_id = ? AND delete_image = ''");
                    $stmt->bind_param("s", $bike_id);

                    if ($stmt->execute()) {
                        $result = $stmt->get_result();

                        if ($result->num_rows === 0) {
                            // Disallow marking as sale if the post has no images available
                            $_SESSION['error'] = "Item cannot be marked as sold due to missing images. Data not saved!";
                            header('Location: sell_bike.php');
                            exit;
                        }
                    }

                    // Generate a unique random string
                    $randomString = bin2hex(random_bytes(9));

                    // Start the transaction
                    $conn->begin_transaction();

                    try {

                        $updateStmt = $conn->prepare("UPDATE `bike_details_table` SET `bike_availability` = ?, `date_deleted` = NOW()  WHERE id = ? AND random_string_id = ?");
                        $updateStmt->bind_param(
                            "sss",
                            $sold,
                            $bike_id,
                            $string_id,
                        );
                        $updateStmt->execute();

                        // Table 2
                        $insertStmt = $conn->prepare("INSERT INTO `sold_bike_tracker` (`bike_id`, date_sold, random_string) VALUES (?, NOW(), ?)");
                        $insertStmt->bind_param(
                            "ss",
                            $bike_id,
                            $randomString
                        );

                        $insertStmt->execute();
                        $conn->commit();

                        $_SESSION['done'] = "Bike marked as sold successfully";

                        // Close the prepared statements
                        $updateStmt->close();
                        $insertStmt->close();
                    } catch (Exception $e) {
                        // Roll back the transaction if an error occurs
                        $conn->rollback();

                        $_SESSION['error'] = "Error saving bike details: " . $e->getMessage();
                    }



                    // Redirect back to the form page
                    header('Location: sell_bike.php');
                    exit;
                } else {

                    if (isset($_POST["delete_image"])) {
                        $image_id = isset($_POST['image_id']) ? $_POST['image_id'] : '';
                        $string_id = isset($_POST['string_id']) ? $_POST['string_id'] : '';
                        $deleted_image = htmlspecialchars("deleted");

                        // Perform the image deletion code here
                        $stmt = $conn->prepare("UPDATE `images_table` SET date_deleted = NOW(), delete_image = ? WHERE id = ? AND string_id = ? ");
                        $stmt->bind_param("sss", $deleted_image, $image_id, $string_id);

                        if ($stmt->execute()) {
                            // Image deleted successfully
                            $_SESSION['img_success'] = "Image deleted successfully. Data saved!";
                            header('Location: sell_bike.php');
                            exit;
                        } else {
                            $_SESSION['error'] = "An error occurred while deleting the image. Please try again later.";
                            header('Location: sell_bike.php');
                            exit;
                        }
                    }
                }
            }
        }
    }
}
