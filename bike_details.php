<?php
session_start();
include 'config.php';
include 'header.php';
?>

<!-- Start Main Content -->
<main class="main-content">
    <!-- Start Single Product Detail -->
    <?php

    if (isset($_GET['post_id']) && isset($_GET['position'])) {

        $post_id = isset($_GET['post_id']) ? $_GET['post_id'] : '';
        $position = isset($_GET['position']) ? $_GET['position'] : '';

        try {
            $selectStmt = $conn->prepare("SELECT * FROM bike_details_table WHERE id = ? AND random_string_id = ?");
            $selectStmt->bind_param("ss", $position, $post_id);
            $selectStmt->execute();

            $result = $selectStmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $bike_availability_details = $row["bike_availability"];

                    //Get seller information
                    $businesss_name = $businesss_email = $businesss_contacts = ''; // Declare the global variable
                    $bike_posted = htmlspecialchars($row['posted_by'], ENT_QUOTES, 'UTF-8');

                    try {
                        $querr = "SELECT business_name, email, user_contacts FROM `user_profile` WHERE user_id = ?";
                        $stmt = $conn->prepare($querr);
                        $stmt->bind_param("s", $bike_posted);

                        if ($stmt->execute()) {
                            $get_posted = $stmt->get_result();

                            if ($get_posted->num_rows > 0) {
                                while ($person = $get_posted->fetch_assoc()) {
                                    $businesss_name = htmlspecialchars($person['business_name']);
                                    $businesss_email = htmlspecialchars($person['email']);
                                    $businesss_contacts = htmlspecialchars($person['user_contacts']);
                                }
                            } else {
                                // echo "No user found!";
                            }
                        } else {
                            throw new Exception("Error executing the database query!");
                        }

                        $stmt->close();
                    } catch (Exception $e) {
                        // Handle the exception, set an error message, or redirect to an error page.
                        $_SESSION['error'] = "Error fetching user details: ";
                        header('Location: index.php');
                        exit;
                    }

    ?>
                    <div class="product-single product-details-group">
                        <div class="container">
                            <div class="row">
                                <!-- Start Product Store Features -->
                                <div class="col-12 col-sm-12 col-md-6 product-store-features">
                                    <!-- Start Product Thumb Slider -->
                                    <div class="product-img-thumb">
                                        <div class="product-single-img-slider position-relative">
                                            <div class="product-group-photo">

                                                <!-- Start Product Single Images -->
                                                <div class="row no-gutters">
                                                    <?php
                                                    $bike_identity = htmlspecialchars($row['id']);
                                                    $stmt = $conn->prepare("SELECT `image_name` FROM `images_table` WHERE post_id = ? AND delete_image = '' ORDER BY id DESC LIMIT 6");
                                                    $stmt->bind_param("s", $bike_identity);

                                                    if ($stmt->execute()) {
                                                        $bike_img_results = $stmt->get_result();

                                                        if ($bike_img_results->num_rows > 0) {
                                                            $imageNames = array();

                                                            while ($bike_img_rows = $bike_img_results->fetch_assoc()) {
                                                                $imageNames[] = $bike_img_rows['image_name'];
                                                            }

                                                            // Count the images
                                                            $imageCount = count($imageNames);
                                                    ?>

                                                            <?php foreach ($imageNames as $imageName) { ?>
                                                                <div class="single-item col-6 product-gallery-image">
                                                                    <div class="zoom item">
                                                                        <img class="img-fluid blur-up lazyload" src="seller_images/<?php echo htmlspecialchars($imageName); ?>" alt="image" title="<?php echo htmlspecialchars($imageName); ?>" title="Bike Image" />
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            No images found!
                                                    <?php
                                                        }
                                                    } else {
                                                        echo "An error occurred while fetching the image. Please try again later!";
                                                    }

                                                    // Close the statement
                                                    $stmt->close();
                                                    ?>
                                                </div>
                                            </div>
                                            <!-- End Product Single Images -->

                                            <!-- Start Product Action -->
                                            <div class="product-gallery-actions">
                                                <a id="lightgallery-btn" class="action-btn gallery-popup lightgallery-btn"><i class="ti-zoom-in"></i></a>
                                            </div>
                                            <!-- End Product Action -->
                                        </div>

                                    </div>
                                    <!-- End Product Thumb Slider -->
                                </div>
                                <!-- End Product Store Features -->

                                <!-- Start Product Info Details -->
                                <div class="col-12 col-sm-12 col-md-6 product-info-details">
                                    <!-- Start Product Info -->
                                    <div class="profuct-info" style="color: green; text-align: center;">
                                        <h1 class="product-title">
                                            <?php echo htmlspecialchars($row['bike_name'], ENT_QUOTES, 'UTF-8'); ?>
                                        </h1>
                                        <small>Date posted: <?php echo htmlspecialchars($row['date_posted'], ENT_QUOTES, 'UTF-8'); ?></small>
                                    </div>
                                    <?php include 'session_messages.php'; ?>
                                    <br>
                                    <div class="product-label">
                                        <?php if ('' == $bike_availability_details) { ?>
                                            <span class="label new">Available</span>
                                        <?php } else { ?>
                                            <span class="label sale">Sold</span>
                                        <?php } ?>
                                    </div>
                                    <br>
                                    <br>
                                    <!-- End Product Info -->

                                    <!-- Start Product Sold In Last -->
                                    <div class="counter-real-time">
                                        <i class="fa fa-camera mr-2" aria-hidden="true"></i>Images found:
                                        <?php
                                        $bike_identity = htmlspecialchars($row['id']);
                                        $stmt = $conn->prepare("SELECT `image_name` FROM `images_table` WHERE post_id = ? AND delete_image = '' ORDER BY id DESC LIMIT 6");
                                        $stmt->bind_param("s", $bike_identity);

                                        if ($stmt->execute()) {
                                            $bike_img_results = $stmt->get_result();

                                            if ($bike_img_results->num_rows > 0) {
                                                $imageNames = array();

                                                while ($bike_img_rows = $bike_img_results->fetch_assoc()) {
                                                    $imageNames[] = $bike_img_rows['image_name'];
                                                }

                                                $imageCount = count($imageNames);

                                                echo $imageCount;
                                            } else {
                                                echo "0";
                                            }
                                        } else {
                                            echo "An error occurred while fetching the image. Please try again later!";
                                        }

                                        // Close the statement
                                        $stmt->close();
                                        ?>
                                        </a>
                                    </div>
                                    <!-- End Product Sold In Last -->

                                    <!-- Start Product Group Table -->
                                    <div class="group-product-tbl table-responsive">
                                        <table class="table table-bordered mb-4">
                                            <thead>
                                                <tr>
                                                    <th>Bike name</th>
                                                    <th><?php echo htmlspecialchars($row['bike_name'], ENT_QUOTES, 'UTF-8'); ?></th>
                                                </tr>
                                                <tr>
                                                    <th>Year</th>
                                                    <th><?php echo htmlspecialchars($row['bike_year'], ENT_QUOTES, 'UTF-8'); ?></th>
                                                </tr>
                                                <tr>
                                                    <th>Engine size</th>
                                                    <th><?php echo htmlspecialchars($row['bike_cc'], ENT_QUOTES, 'UTF-8'); ?>cc</th>
                                                </tr>
                                                <tr>
                                                    <th>Mileage (KMs)</th>
                                                    <th><?php echo htmlspecialchars($row['bike_mileage'], ENT_QUOTES, 'UTF-8'); ?></th>
                                                </tr>
                                                <tr>
                                                    <th>Bike location</th>
                                                    <th><?php echo htmlspecialchars($row['bike_location'], ENT_QUOTES, 'UTF-8'); ?></th>
                                                </tr>
                                                <tr>
                                                    <th>Bike color</th>
                                                    <th><?php echo htmlspecialchars($row['bike_color'], ENT_QUOTES, 'UTF-8'); ?></th>
                                                </tr>
                                                <tr>
                                                    <th>Logbook availablity</th>
                                                    <th><?php echo htmlspecialchars($row['logbook_availability'], ENT_QUOTES, 'UTF-8'); ?></th>
                                                </tr>
                                                <tr>
                                                    <th>Bike condition</th>
                                                    <th><?php echo htmlspecialchars($row['bike_condition'], ENT_QUOTES, 'UTF-8'); ?></th>
                                                </tr>
                                                <tr>
                                                    <th>Bike details</th>
                                                    <th> <?php echo nl2br(htmlspecialchars($row['bike_details'], ENT_QUOTES, 'UTF-8')); ?>
                                                    </th>
                                                </tr>
                                                <tr style="color:green">
                                                    <th>Bike price</th>
                                                    <th><?php echo number_format((float) $row['bike_price'], 2); ?>
                                                    </th>
                                                </tr>

                                                <tr style="color:orange">
                                                    <th>Business name</th>
                                                    <th>
                                                        <?php
                                                        if (!isset($_SESSION["random_access_key"])) { ?>
                                                            Log in to see
                                                        <?php } else {
                                                        ?>
                                                            <?php echo $businesss_name ?>
                                                        <?php } ?>
                                                    </th>
                                                </tr>
                                                <tr style="color:orange">
                                                    <th>Business contacts</th>
                                                    <th>
                                                        <?php
                                                        if (!isset($_SESSION["random_access_key"])) { ?>
                                                            Log in to see
                                                        <?php } else {
                                                        ?>
                                                            <?php echo $businesss_contacts ?>
                                                        <?php } ?>
                                                    </th>
                                                </tr>
                                                <tr style="color:orange">
                                                    <th>Business email</th>
                                                    <th>
                                                        <?php
                                                        if (!isset($_SESSION["random_access_key"])) { ?>
                                                            Log in to see
                                                        <?php } else {
                                                        ?>
                                                            <?php echo $businesss_email ?>
                                                        <?php } ?>
                                                    </th>
                                                </tr>
                                            </thead>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- End Product Group Table -->
                                    <hr>

                                    <div class="wish-size-ship clearfix">
                                        <!-- Start Product Wish List -->
                                        <div class="wishlistOuter pull-left">
                                            <a href=""><i class="ti-heart align-middle mr-2"></i> <span class="align-middle">Add To
                                                    Wishlist</span></a>
                                        </div>
                                        <!-- End Product Wish List -->
                                        <!-- Start Product Size Shipping Info -->
                                        <div class="size-ship-info pull-right">
                                            <!-- End Product Size -->
                                            <!-- Start Product Shipping Info -->
                                            <div class="shipping-info pull-left">
                                                <a href="" class="ship-info-btn text-danger" data-toggle="modal" data-target="#reportPost"><i class="ti-alert align-middle mr-2"></i> <span class="align-middle">Report this
                                                        post</span></a>
                                                <!-- Start Shipping Info Modal -->
                                                <div class="reportPost modal fade" id="reportPost" tabindex="-1" role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-body">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="ti-close"></i></button>
                                                                <div class="shipping-returns-content">
                                                                    <h3 class="text-center">Report post</h3>
                                                                    <hr>
                                                                    <?php
                                                                    if (!isset($_SESSION["random_access_key"])) { ?>
                                                                        You must log in to report post
                                                                    <?php } else {
                                                                        // Display the post form
                                                                    ?>
                                                                        <!-- Start Change Password -->
                                                                        <div class="change-password">
                                                                            <div class="container">
                                                                                <div class="row row-sp">
                                                                                    <div class="col-sp col-12 col-sm-12 col-md-12 col-lg-6 offset-lg-3">
                                                                                        <div class="page-title text-center">
                                                                                            <p class="subtitle">Fill the field below to report post.</p>
                                                                                        </div>
                                                                                        <form action="form_handler.php" method="POST">
                                                                                            <input type="hidden" class="form-control" name="track_item" value="Bike_tracking" />
                                                                                            <input type="hidden" class="form-control" name="reported_by" value="<?php echo htmlspecialchars($_SESSION['user_identity']) ?>" />
                                                                                            <input type="hidden" class="form-control" name="post_id" value="<?php echo htmlspecialchars($row['id']) ?>" required />
                                                                                            <input type="hidden" class="form-control" name="string_id" value="<?php echo htmlspecialchars($row['random_string_id']) ?>" required />
                                                                                            <div class="form-group">
                                                                                                <label>Report details *</label>
                                                                                                <textarea minlength="20" rows="15" type="password" name="issue_details" class="form-control" required></textarea>
                                                                                                <small class="form-text text-muted">Please describe the issue with the post in few words. An action will be taken and an email sent to you.</small>
                                                                                            </div>
                                                                                            <div class="cart-checkout">
                                                                                                <button type="submit" name="report_post" class="btn btn-primary btn-block">Submit report</button>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <!-- End Change Password -->
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End Shipping Info Modal -->
                                            </div>
                                            <!-- End Product Shipping Info -->
                                        </div>
                                        <!-- Start Product Size Shipping Info -->
                                    </div>
                                    <br>
                                    <!-- Start Product Social Media -->
                                    <div class="social-media">
                                        <ul class="d-flex flex-row">
                                            <li><span>Share:</span></li>
                                            <li>
                                                <div class="fb-share-button" data-href="<?php echo $url_to_share; ?>" data-layout="button_count"></div>
                                            </li>
                                            <li style="color: green;">
                                                <a href="whatsapp://send?text=<?php echo urlencode($og_title . ' - ' . $url_to_share); ?>" class="ti-share"> whatsapp</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- Start Product Social Media -->
                                </div>
                                <!-- End Product Info Details -->
                            </div>
                        </div>
                    </div>
                    <!-- End Single Product Detail -->
    <?php }
            } else {
                echo "No data found!";
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Error retrieving bike details!";
        }
    }
    ?>

    <hr>

    <!-- Start Main Content -->
    <main class="main-content" style="margin-bottom: 280px; margin-top: 100px">

        <div class="tabs-header clearfix">
            <div class="section-header">
                <h2>Bikes on sale</h2>
                <p>Because you clicked on bikes on sale</p>
            </div>
        </div>
        <!-- Start Cart Details -->
        <div class="cart-view-table m-0 col-12 col-sm-12 col-lg-12">
            <!-- Start cart Content Inner -->
            <div class="product-filter-sidebar">
                <div class="container">
                    <div class="row">
                        <?php include 'side_bar_nav_sponsored.php'; ?>
                        <div class="product-view-items product-grid col-12 col-sm-12 col-lg-9 sidebar-right">
                            <!-- Start Products Grid -->
                            <div class="products products-grid">
                                <div class="row row-sp row-eq-height">
                                    <?php
                                    $post_delete_status = 'deleted';
                                    $bike_availability = '';

                                    $query = "SELECT * FROM `bike_details_table` WHERE post_delete != ? ORDER BY id DESC LIMIT 24";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("s", $post_delete_status);

                                    if ($stmt->execute()) {
                                        $result = $stmt->get_result();
                                    } else {
                                        $_SESSION['error'] = "Error fetching bike details!";
                                        header('Location: bike_details.php');
                                        exit;
                                    }

                                    $stmt->close();
                                    ?>

                                    <?php if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $bike_availability = htmlspecialchars($row['bike_availability'], ENT_QUOTES, 'UTF-8');
                                    ?>
                                            <div class="col-sp col-md-3 col-sm-6 col-6">
                                                <div class="product-item">
                                                    <div class="product-image-action">
                                                        <div class="product-image">

                                                            <?php
                                                            $post_identy = htmlspecialchars($row['id']);
                                                            $stmt = $conn->prepare("SELECT `image_name` FROM `images_table` WHERE post_id = ? AND delete_image = '' ORDER BY id DESC LIMIT 1");
                                                            $stmt->bind_param("s", $post_identy);
                                                            if ($stmt->execute()) {
                                                                $results = $stmt->get_result();
                                                                if ($results->num_rows > 0) {
                                                                    $rows = $results->fetch_assoc();
                                                                    $imageNames = $rows['image_name']; ?>
                                                                    <div class="product-label">
                                                                        <?php if ('' == $bike_availability) { ?>

                                                                        <?php } else { ?>
                                                                            <span class="label sale">Sold</span>
                                                                        <?php } ?>
                                                                    </div>
                                                                    <a href="bike_details.php?post_id=<?php echo htmlspecialchars($row['random_string_id'], ENT_QUOTES, 'UTF-8') ?>&position=<?php echo htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?>&img=<?php echo htmlspecialchars($imageNames); ?>&name=<?php echo htmlspecialchars($row['bike_name'], ENT_QUOTES, 'UTF-8') ?>&price=<?php echo htmlspecialchars($row['bike_price'], ENT_QUOTES, 'UTF-8') ?>">
                                                                        <img class="img-fluid blur-up lazyload" src="seller_images/<?php echo htmlspecialchars($imageNames); ?>" style="max-height: 180px; min-height: 180px;" />
                                                                    </a>
                                                                    <?php } else { ?>
                                                                        <img class="img-fluid blur-up lazyload" src="assets/images/usable/not_found.png" alt="" style="max-height: 180px; min-height: 180px;">

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
                                                    </div>
                                                    <div class="product-details">
                                                        <h3 class="product-title">
                                                            <?php echo htmlspecialchars($row['bike_name'], ENT_QUOTES, 'UTF-8'); ?>
                                                        </h3>
                                                        <div class="product-price">
                                                            <span class="sale-price">
                                                                <?php echo number_format((float) $row['bike_price'], 2); ?>
                                                            </span>
                                                        </div>
                                                        <br>
                                                        <h5 class="product-title">
                                                            <?php

                                                            $business_name = ''; // Declare the global variable
                                                            $bike_posted = htmlspecialchars($row['posted_by'], ENT_QUOTES, 'UTF-8');

                                                            try {
                                                                $querr = "SELECT business_name FROM `user_profile` WHERE user_id = ?";
                                                                $stmt = $conn->prepare($querr);
                                                                $stmt->bind_param("s", $bike_posted);

                                                                if ($stmt->execute()) {
                                                                    $get_datas = $stmt->get_result();

                                                                    if ($get_datas->num_rows > 0) {
                                                                        while ($persons = $get_datas->fetch_assoc()) {
                                                                            $business_name = htmlspecialchars($persons['business_name']); ?>

                                                                            <?php echo '<i class="fa fa-user mr-1"></i> Seller: ' . $business_name ?>

                                                            <?php }
                                                                    } else {
                                                                        echo "No user found!";
                                                                    }
                                                                } else {
                                                                    throw new Exception("Error executing the database query!");
                                                                }

                                                                $stmt->close();
                                                            } catch (Exception $e) {
                                                                // Handle the exception, set an error message, or redirect to an error page.
                                                                $_SESSION['error'] = "Error fetching user details: ";
                                                                header('Location: bike_details.php');
                                                                exit;
                                                            }

                                                            ?>
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End of grid-->
                                        <?php }
                                    } else { ?>
                                        <p style="text-align: left;  display: flex;
                                        justify-content: center;
                                        align-items: center;
                                        height: 20vh; ">
                                            No data!
                                        </p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Products -->
                </div>
            </div>
        </div>
        </div>
        </div>
    </main>
    <!-- End Main Content -->
    <?php include 'footer.php'; ?>