<?php
session_start();
include 'config.php';

if (isset($_POST["submit_post"])) {
    // Collect and sanitize input data
    $system_user = isset($_POST['system_user']) ? $_POST['system_user'] : '';
    $part_name = isset($_POST['part_name']) ? $_POST['part_name'] : '';
    $part_price = isset($_POST['part_price']) ? $_POST['part_price'] : '';
    $part_condition = isset($_POST['part_condition']) ? $_POST['part_condition'] : '';
    $part_location = isset($_POST['part_location']) ? $_POST['part_location'] : '';
    $part_details = isset($_POST['part_details']) ? $_POST['part_details'] : '';
    $terms_agreement = isset($_POST['terms_agreement']) ? $_POST['terms_agreement'] : '';

    // Generate a unique random string
    $randomString = bin2hex(random_bytes(40));

    // Prepare the INSERT statement
    $stmt = $conn->prepare("INSERT INTO `bike_parts_details_table`
    (`part_name`, `part_price`, `part_condition`, `part_location`, 
    `part_details`, `terms_agreement`,`date_posted`, posted_by, random_string_id) 
    VALUES (?, ?, ?, ?, ?, ?, NOW(), ? , ?)");

    // Bind parameters and execute the statement
    $stmt->bind_param(
        "ssssssss",
        $part_name,
        $part_price,
        $part_condition,
        $part_location,
        $part_details,
        $terms_agreement,
        $system_user,
        $randomString
    );

    // Execute the statement
    if ($stmt->execute()) {
        $_SESSION['done'] = "Motorbike part details saved successfully.";
    } else {
        $_SESSION['error'] = "Error saving motorbike part details: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();

    // Redirect back to the form page
    header('Location: sell_bike_parts.php');
    exit;
} else {
    if (isset($_POST["edit_post"])) {
        // Collect and sanitize input data
        $parts_id = isset($_POST['parts_id']) ? $_POST['parts_id'] : '';
        $string_id = isset($_POST['string_id']) ? $_POST['string_id'] : '';
        $part_name = isset($_POST['part_name']) ? $_POST['part_name'] : '';
        $part_price = isset($_POST['part_price']) ? $_POST['part_price'] : '';
        $part_condition = isset($_POST['part_condition']) ? $_POST['part_condition'] : '';
        $part_location = isset($_POST['part_location']) ? $_POST['part_location'] : '';
        $part_details = isset($_POST['part_details']) ? $_POST['part_details'] : '';

        // Generate a unique random string
        $randomString = bin2hex(random_bytes(9));

        // Start the transaction
        $conn->begin_transaction();

        try {
            // Prepare the UPDATE statement for `bike_details_table`
            $updateStmt = $conn->prepare("UPDATE `bike_parts_details_table` SET `part_name` = ?, `part_price` = ?, `part_condition` = ?, 
                `part_location` = ?, `part_details` = ? WHERE id = ? AND random_string_id = ?");

            // Bind parameters and execute the UPDATE statement
            $updateStmt->bind_param(
                "sssssss",
                $part_name,
                $part_price,
                $part_condition,
                $part_location,
                $part_details,
                $parts_id,
                $string_id,
            );

            // Execute the UPDATE statement
            $updateStmt->execute();

            // Prepare the INSERT statement for `bike_details_tracking_table`
            $insertStmt = $conn->prepare("INSERT INTO `bike_parts_details_tracking_table`(`part_name`, `part_price`, `part_condition`, 
                `part_location`, `part_details`, `random_string_id`, parts_id, date_edited) 
                VALUES (? , ? , ? , ? , ? , ? , ?, NOW())");

            // Bind parameters and execute the INSERT statement
            $insertStmt->bind_param(
                "sssssss",
                $part_name,
                $part_price,
                $part_condition,
                $part_location,
                $part_details,
                $randomString,
                $parts_id,
            );

            // Execute the INSERT statement
            $insertStmt->execute();

            // Commit the transaction
            $conn->commit();

            $_SESSION['done'] = "Bike part details edited successfully.";

            // Close the prepared statements
            $updateStmt->close();
            $insertStmt->close();
        } catch (Exception $e) {
            // Roll back the transaction if an error occurs
            $conn->rollback();

            $_SESSION['error'] = "Error saving bike details: " . $e->getMessage();
        }

        // Redirect back to the form page
        header('Location: sell_bike_parts.php');
        exit;
    } else {
        if (isset($_POST["delete_post"])) {
            $part_id = isset($_POST['part_id']) ? $_POST['part_id'] : '';
            $string_id = isset($_POST['string_id']) ? $_POST['string_id'] : '';
            $deleted = isset($_POST['deleted']) ? $_POST['deleted'] : '';

            // Generate a unique random string
            $randomString = bin2hex(random_bytes(9));

            // Start the transaction
            $conn->begin_transaction();

            try {

                $updateStmt = $conn->prepare("UPDATE `bike_parts_details_table` SET `post_delete` = ?, `date_deleted` = NOW()  WHERE id = ? AND random_string_id = ?");
                $updateStmt->bind_param(
                    "sss",
                    $deleted,
                    $part_id,
                    $string_id,
                );
                $updateStmt->execute();

                // Table 2
                $insertStmt = $conn->prepare("INSERT INTO `deleted_part_tracker` (`gear_id`, random_string_id, date_delete) VALUES (?, ?, NOW())");
                $insertStmt->bind_param(
                    "ss",
                    $part_id,
                    $randomString
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
            header('Location: sell_bike_parts.php');
            exit;
        } else {
            if (isset($_POST["sold_part"])) {
                $part_id = isset($_POST['part_id']) ? $_POST['part_id'] : '';
                $string_id = isset($_POST['string_id']) ? $_POST['string_id'] : '';
                $sold = isset($_POST['sold']) ? $_POST['sold'] : '';

                // Check if the post has images, if not, reject the request
                $stmt = $conn->prepare("SELECT post_id FROM `parts_images` WHERE post_id = ? AND delete_image = ''");
                $stmt->bind_param("s", $part_id);

                if ($stmt->execute()) {
                    $result = $stmt->get_result();

                    if ($result->num_rows === 0) {
                        // Disallow marking as sale if the post has no images available
                        $_SESSION['error'] = "Item cannot be marked as sold due to missing images. Data not saved!";
                        header('Location: sell_bike_parts.php');
                        exit;
                    }
                }

                // Generate a unique random string
                $randomString = bin2hex(random_bytes(9));

                // Start the transaction
                $conn->begin_transaction();

                try {

                    $updateStmt = $conn->prepare("UPDATE `bike_parts_details_table` SET `part_availability` = ?, `date_deleted` = NOW()  WHERE id = ? AND random_string_id = ?");
                    $updateStmt->bind_param(
                        "sss",
                        $sold,
                        $part_id,
                        $string_id,
                    );
                    $updateStmt->execute();

                    // Table 2
                    $insertStmt = $conn->prepare("INSERT INTO `sold_part_tracker` (`part_id`, date_sold, random_string) VALUES (?, NOW(), ?)");
                    $insertStmt->bind_param(
                        "ss",
                        $part_id,
                        $randomString
                    );

                    $insertStmt->execute();
                    $conn->commit();

                    $_SESSION['done'] = "Item marked as sold successfully";

                    // Close the prepared statements
                    $updateStmt->close();
                    $insertStmt->close();
                } catch (Exception $e) {
                    // Roll back the transaction if an error occurs
                    $conn->rollback();

                    $_SESSION['error'] = "Error saving bike details: " . $e->getMessage();
                }

                // Redirect back to the form page
                header('Location: sell_bike_parts.php');
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

                    // Generate a unique random string
                    $randomString = bin2hex(random_bytes(9));

                    // Sanitize input data
                    $post_id = isset($_POST['post_id']) ? $_POST['post_id'] : '';

                    // Validate and process file uploads
                    $allowedExtensions = array('png', 'jpeg', 'jpg');
                    $uploadedFiles = array_filter($_FILES['parts_images']['name']);
                    $imageNames = array();

                    if (!empty($uploadedFiles)) {
                        $uploadDir = 'seller_images/';
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }

                        foreach ($_FILES['parts_images']['tmp_name'] as $key => $tmpName) {
                            $fileInfo = pathinfo($_FILES['parts_images']['name'][$key]);
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
                        header('Location: sell_bike_parts.php');
                        exit;
                    }

                    // Prepare the INSERT statement for image names
                    $stmt = $conn->prepare("INSERT INTO `parts_images` (`post_id`, `image_name`, `date_uploaded`, random_string) VALUES (?, ?, NOW(), ?)");

                    // Bind parameters and execute the statement
                    $stmt->bind_param("sss", $post_id, $imageName, $randomString);

                    // Execute the statement
                    foreach ($imageNames as $imageName) {
                        $stmt->execute();
                    }

                    // Check if any rows were inserted successfully
                    if ($stmt->affected_rows > 0) {
                        $_SESSION['done'] = "Bike parts images saved successfully. Only landscape images were saved!";
                    } else {
                        $_SESSION['error'] = "Error saving bike images.";
                    }

                    // Close the statement
                    $stmt->close();

                    // Redirect back to the form page with success or error message
                    header('Location: sell_bike_parts.php');
                    exit;
                }else{
                    if (isset($_POST["delete_image"])) {
                        $image_id = isset($_POST['image_id']) ? $_POST['image_id'] : '';
                        $string_id = isset($_POST['string_id']) ? $_POST['string_id'] : '';
                        $deleted_image = htmlspecialchars("deleted");

                        // Perform the image deletion code here
                        $stmt = $conn->prepare("UPDATE `parts_images` SET date_deleted = NOW(), delete_image = ? WHERE id = ? AND random_string = ? ");
                        $stmt->bind_param("sss", $deleted_image, $image_id, $string_id);

                        if ($stmt->execute()) {
                            // Image deleted successfully
                            $_SESSION['img_success'] = "Image deleted successfully. Data saved!";
                            header('Location: sell_bike_parts.php');
                            exit;
                        } else {
                            $_SESSION['error'] = "An error occurred while deleting the image. Please try again later.";
                            header('Location: sell_bike_parts.php');
                            exit;
                        }
                    }
                }
            }
        }
    }
}
