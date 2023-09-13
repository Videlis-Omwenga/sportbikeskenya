<?php
$selectStmt = $conn->prepare("SELECT * FROM bike_details_table WHERE bike_availability = '' ORDER BY RAND() LIMIT 1");
$selectStmt->execute();
$resulted = $selectStmt->get_result();

if ($resulted->num_rows > 0) {
    while ($rowed = $resulted->fetch_assoc()) {
?>

        <div style="margin-top: 30px; border: 2px solid #ccc; padding: 20px;">
            <p style="font-weight: bold; font-size: 18px; margin-bottom: 10px;">Sponsored Ad</p>
            <?php
            //Get the image
            $post_identy = htmlspecialchars($rowed['id']);
            $stmt = $conn->prepare("SELECT `image_name` FROM `images_table` WHERE post_id = ? AND delete_image = '' ORDER BY RAND() DESC LIMIT 1");
            $stmt->bind_param("s", $post_identy);
            if ($stmt->execute()) {
                $resultss = $stmt->get_result();
                if ($resultss->num_rows > 0) {
                    $rowss = $resultss->fetch_assoc();
                    $imageNames = $rowss['image_name']; ?>
                    <a href="bike_details.php?post_id=<?php echo htmlspecialchars($row['random_string_id'], ENT_QUOTES, 'UTF-8') ?>&position=<?php echo htmlspecialchars($row_id['id'], ENT_QUOTES, 'UTF-8') ?>&img=<?php echo htmlspecialchars($imageNames); ?>&name=<?php echo htmlspecialchars($row_id['bike_name'], ENT_QUOTES, 'UTF-8') ?>&price=<?php echo htmlspecialchars($row_id['bike_price'], ENT_QUOTES, 'UTF-8') ?>">
                        <img class="img-fluid blur-up lazyload" src="seller_images/<?php echo htmlspecialchars($imageNames); ?>" />
                    </a>
                <?php } else { ?>
                    <img class="img-fluid blur-up lazyload" src="assets/images/usable/not_found.png" alt="">

            <?php }
            } else {
                // Log the error in a secure log file
                error_log("Error fetching image!");

                echo "An error occurred while fetching the image. Please try again later!";
            }

            // Close the statement
            $stmt->close();
            ?>
            <p style="margin-top: 10px;">
                <br>
                <strong>Bike name:</strong> <?php echo strtoupper(htmlspecialchars($rowed['bike_name'], ENT_QUOTES, 'UTF-8')); ?>
                <br>
                <strong>Year:</strong> <?php echo strtoupper(htmlspecialchars($rowed['bike_year'], ENT_QUOTES, 'UTF-8')); ?>
                <br>
                <strong>Engine Capacity:</strong> <?php echo strtoupper(htmlspecialchars($rowed['bike_cc'], ENT_QUOTES, 'UTF-8')); ?>cc
                <br>
                <strong>Mileage:</strong> <?php echo strtoupper(htmlspecialchars($rowed['bike_mileage'], ENT_QUOTES, 'UTF-8')); ?> KMs
                <br>
                <strong>Condition:</strong> <?php echo strtoupper(htmlspecialchars($rowed['bike_condition'], ENT_QUOTES, 'UTF-8')); ?>
                <br>
                <strong>Price:</strong> <?php echo number_format((float) $rowed['bike_price']); ?>
            </p>
        </div>
<?php }
} ?>