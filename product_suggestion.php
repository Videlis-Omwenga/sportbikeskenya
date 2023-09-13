<!-- Start Product Suggestion -->
<div class="product-suggestion-content clearfix border-0 rounded-0 alert fade show" role="alert" style="margin-bottom: 125px;">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <i class="ti-close" aria-hidden="true"></i>
    </button>



    <?php
    $selectStmt = $conn->prepare("SELECT * FROM bike_details_table WHERE bike_availability = '' ORDER BY RAND() LIMIT 1");
    $selectStmt->execute();
    $result = $selectStmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
    ?>
            <div class="product-suggestion">
                <div class="suggestion-item">
                    <div class="d-table suggestion-product">
                        <div class="d-table-cell align-middle suggestion-img">
                            <?php
                            //Get the image
                            $post_identy = htmlspecialchars($row['id']);
                            $stmt = $conn->prepare("SELECT `image_name` FROM `images_table` WHERE post_id = ? AND delete_image = '' ORDER BY id DESC LIMIT 1");
                            $stmt->bind_param("s", $post_identy);
                            if ($stmt->execute()) {
                                $results = $stmt->get_result();
                                if ($results->num_rows > 0) {
                                    $rows = $results->fetch_assoc();
                                    $imageNames = $rows['image_name']; ?>
                                    <a href="bike_details.php?post_id=<?php echo htmlspecialchars($row['random_string_id'], ENT_QUOTES, 'UTF-8') ?>&position=<?php echo htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?>&img=<?php echo htmlspecialchars($imageNames); ?>&name=<?php echo htmlspecialchars($row['bike_name'], ENT_QUOTES, 'UTF-8') ?>&price=<?php echo htmlspecialchars($row['bike_price'], ENT_QUOTES, 'UTF-8') ?>">
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

                        </div>
                        <div class="d-table-cell align-middle pl-3 suggestion-detail">
                            <span>
                                <strong>
                                    <u>
                                        <ins>
                                            <?php echo strtoupper(htmlspecialchars($row['bike_name'], ENT_QUOTES, 'UTF-8')); ?>
                                        </ins>
                                    </u>
                                </strong>
                            </span>
                            <br>
                            <span>Bike size: <?php echo htmlspecialchars($row['bike_cc'], ENT_QUOTES, 'UTF-8') ?>cc</span>
                            <br>
                            <span>Price: <?php echo number_format((float) $row['bike_price']); ?></span>
                        </div>
                    </div>
                </div>
            </div>
    <?php }
    } ?>

</div>