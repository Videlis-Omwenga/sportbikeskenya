<?php
session_set_cookie_params(0, '/', '', true, true);
session_start();

include 'config.php';

if (!isset($_SESSION["random_access_key"])) {
    $_SESSION['error'] = "Error: You must login to continue!";
    header('Location: index.php');
    exit;
}

session_regenerate_id(true);
?>

<?php include 'header.php' ?>

<!-- Start Main Content -->
<main class="main-content" style="margin-bottom: 280px;">
    <!-- Start Cart Details -->
    <div class="cart-view-table m-0 col-12 col-sm-12 col-lg-12">
        <!-- Start cart Content Inner -->
        <div class="product-filter-sidebar">
            <div class="container">
                <div class="row">
                    <?php include 'side_bar_nav.php'; ?>
                    <div class="product-view-items product-grid col-12 col-sm-12 col-lg-9 sidebar-right">

                        <!-- Session messages  -->
                        <?php include 'session_messages.php'; ?>

                        <div class="products products-lists">
                            <div class="row row-sp row-eq-height">
                                <?php

                                $user_id = $_SESSION['user_identity'];

                                $check_query = "SELECT first_name, second_name, email, user_contacts, user_location, user_description, business_name FROM user_profile WHERE user_id = ?";
                                $stmt = $conn->prepare($check_query);
                                $stmt->bind_param("i", $user_id);

                                if ($stmt->execute()) {
                                    $result = $stmt->get_result();

                                    if ($result->num_rows > 0) {
                                        $profile_data = $result->fetch_assoc();

                                        $required_columns = ['first_name', 'second_name', 'email', 'user_contacts', 'user_location', 'user_description', 'business_name'];
                                        $incomplete_columns = [];

                                        foreach ($required_columns as $column) {
                                            if (empty($profile_data[$column])) {
                                                $incomplete_columns[] = $column;
                                            }
                                        }

                                        if (!empty($incomplete_columns)) { ?>
                                            <div class="col-12 col-sm-12 col-lg-9 sidebar-left">
                                                <div class="align-items-center alert alert-danger alert-dismissible">
                                                    Your profile details are incomplete.<a href="my_profile.php"> Click here </a> to complete your profile and start selling!
                                                    <a class="close" data-dismiss="alert"><i class="ti-close"></i></a>
                                                </div>
                                            </div>
                                        <?php } else { ?>

                                            <div class="account-inner-primary" style="margin-bottom: 20px; margin-left:15px">
                                                <div class="account-nav">
                                                    <div class="nav-pills" id="v-pills-tab" role="tablist">
                                                        <a class="btn open-quickview-popup nav-link active btn-block" id="my-account-home-tab" href="#open-quickview-popups" title="Click to post" role="tab" aria-controls="my-account-home" aria-selected="true">Sale A Bike </a>
                                                    </div>
                                                </div>
                                            </div>
                                <?php  }
                                    }
                                }

                                ?>


                                <?php
                                $post_delete_status = 'deleted';
                                $system_user = htmlspecialchars($_SESSION['user_identity']);

                                $showRecordPerPage = 30;
                                if (isset($_GET['page']) && !empty($_GET['page'])) {
                                    $currentPage = $_GET['page'];
                                } else {
                                    $currentPage = 1;
                                }
                                $startFrom = ($currentPage * $showRecordPerPage) - $showRecordPerPage;

                                $totalEmpSQL = "SELECT COUNT(*) AS total FROM `bike_details_table` WHERE post_delete != ? AND posted_by = ?";
                                $totalStmt = $conn->prepare($totalEmpSQL);
                                $totalStmt->bind_param("ss", $post_delete_status, $system_user);

                                $totalStmt->execute();
                                $totalResult = $totalStmt->get_result();
                                $totalData = $totalResult->fetch_assoc()['total'];

                                $lastPage = ceil($totalData / $showRecordPerPage);
                                $firstPage = 1;
                                $nextPage = $currentPage + 1;
                                $previousPage = $currentPage - 1;

                                $query = "SELECT * FROM `bike_details_table` WHERE post_delete != ? AND posted_by = ? ORDER BY id DESC LIMIT ?, ?";
                                $stmt = $conn->prepare($query);
                                $stmt->bind_param("ssii", $post_delete_status,  $system_user, $startFrom, $showRecordPerPage);

                                if ($stmt->execute()) {
                                    $result = $stmt->get_result();
                                } else {
                                    $_SESSION['error'] = "Error fetching bike details: " . $stmt->error;
                                    header('Location: sell_bike.php');
                                    exit;
                                }

                                $stmt->close();
                                ?>

                                <?php if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $item_availability = htmlspecialchars($row['bike_availability']); ?>
                                        <div class="col-12 col-sm-12 col-md-12 col-sp">
                                            <div class="product-item row no-gutters">
                                                <div class="product-image-action col-md-4">
                                                    <div class="product-image">
                                                        <?php
                                                        $post_identy = htmlspecialchars($row['id']);
                                                        $stmt = $conn->prepare("SELECT `image_name` FROM `images_table` WHERE post_id = ? AND delete_image = '' ORDER BY id DESC LIMIT 1");
                                                        $stmt->bind_param("s", $post_identy);
                                                        if ($stmt->execute()) {
                                                            $results = $stmt->get_result();
                                                            if ($results->num_rows > 0) {
                                                                $rows = $results->fetch_assoc();
                                                                $imageName = $rows['image_name']; ?>
                                                                <div class="product-label">
                                                                    <?php if ('' == $item_availability) { ?>

                                                                    <?php } else { ?>
                                                                        <span class="label sale">Sold</span>
                                                                    <?php } ?>
                                                                </div>
                                                                <img class="img-fluid blur-up lazyload" src="seller_images/<?php echo htmlspecialchars($imageName); ?>" alt="<?php echo htmlspecialchars($imageName); ?>" width="400" height="400" />
                                                            <?php } else { ?>
                                                                <img class="img-fluid blur-up lazyload" src="assets/images/usable/not_found.png" alt="">

                                                        <?php }
                                                        } else {
                                                            // Log the error in a secure log file
                                                            error_log("Error fetching image: " . $stmt->error);

                                                            echo "An error occurred while fetching the image. Please try again later.";
                                                        }

                                                        // Close the statement
                                                        $stmt->close(); ?>
                                                    </div>
                                                    <div class="product-action">
                                                        <?php
                                                        if ($item_availability != '') { ?>
                                                            <button class="btn btn-primary btn-block">Bike sold. You cannot edit</button>
                                                        <?php } else {
                                                        ?>
                                                            <ul>
                                                                <!-- Add images start -->
                                                                <li class="actions-wishlist" data-toggle="tooltip" data-placement="top" title="Add images"><a href="#add_images<?php echo htmlspecialchars($row['id']); ?>" class="btn open-wishlist-popup"><i class="icon ti-camera"></i></a>
                                                                </li>

                                                                <div id="add_images<?php echo htmlspecialchars($row['id']); ?>" class="quickview-popup magnific-popup mfp-hide">
                                                                    <div class="quickview-content">
                                                                        <h3 class="text-center">Read carefully these instructions
                                                                            before uploading images</h3>
                                                                        <hr>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div style="margin-top: 30px; border: 2px solid #ccc; padding: 20px;">
                                                                                    <ul class="d-flex flex-column pro-lists">
                                                                                        <li><i class="fa fa-star mr-2" aria-hidden="true"></i> Include
                                                                                            clear images of the bike from
                                                                                            different angles.</li>
                                                                                        <li><i class="fa fa-star mr-2" aria-hidden="true"></i> Include
                                                                                            images from various angles, such
                                                                                            as front, back, side, and close-ups of
                                                                                            specific
                                                                                            parts.</li>
                                                                                        <li><i class="fa fa-star mr-2" aria-hidden="true"></i> Ensure the
                                                                                            images are in focus and
                                                                                            reflect the true condition of the bike.
                                                                                        </li>
                                                                                        <li><i class="fa fa-star mr-2" aria-hidden="true"></i> Avoid using
                                                                                            stock images or images
                                                                                            downloaded from other sources. Admin
                                                                                            has the right to delete downloaded
                                                                                            images </li>
                                                                                        <li><i class="fa fa-star mr-2" aria-hidden="true"></i> Preferably,
                                                                                            capture images in landscape
                                                                                            mode for better visibility.</li>
                                                                                        <li><i class="fa fa-star mr-2" aria-hidden="true"></i> If there are
                                                                                            any notable damages or wear,
                                                                                            include images that highlight these
                                                                                            areas.</li>
                                                                                    </ul>
                                                                                </div>
                                                                                <div style="margin-top: 30px; border: 2px solid #ccc; padding: 20px;">
                                                                                    <p>
                                                                                        <?php
                                                                                        $post_identy = htmlspecialchars($row['id']);
                                                                                        $stmt = $conn->prepare("SELECT `id`, `image_name`, string_id FROM `images_table` WHERE post_id = ? AND delete_image = '' ORDER BY id DESC");
                                                                                        $stmt->bind_param("s", $post_identy);

                                                                                        if ($stmt->execute()) {
                                                                                            $resultss = $stmt->get_result();

                                                                                            if ($resultss->num_rows > 0) {
                                                                                                // Fetch all images and store them in an array
                                                                                                $imageData = array();
                                                                                                while ($rowss = $resultss->fetch_assoc()) {
                                                                                                    $imageData[] = array(
                                                                                                        'id' => $rowss['id'],
                                                                                                        'image_name' => $rowss['image_name'],
                                                                                                        'string_id' => $rowss['string_id']
                                                                                                    );
                                                                                                }
                                                                                        ?>
                                                                                    <h6>Click on the image you want to set as the main image</h6>
                                                                                    <div class="cms-block-content">
                                                                                        <ul class="instagram-gallery row">
                                                                                            <?php foreach ($imageData as $image) { ?>
                                                                                                <li class="col-4 col-sm-3 col-md-2 col-lg-4 item" style="margin-bottom: 10px;">
                                                                                                    <a class="animate-scale" href="#">
                                                                                                        <img class="img-fluid blur-up lazyload" src="seller_images/<?php echo htmlspecialchars($image['image_name']); ?>" alt="<?php echo htmlspecialchars($image['image_name']); ?>" />
                                                                                                    </a>
                                                                                                    <hr />
                                                                                                    <form method="post" action="bike_sale_handler.php">
                                                                                                        <!-- Hidden input field to hold the image ID -->
                                                                                                        <input type="hidden" name="image_id" value="<?php echo $image['id']; ?>" />
                                                                                                        <input type="hidden" name="string_id" value="<?php echo $image['string_id']; ?>" />
                                                                                                        <button type="submit" name="delete_image" class="btn-danger" style="text-align: center;">Delete image</button>
                                                                                                    </form>
                                                                                                </li>
                                                                                            <?php } ?>
                                                                                        </ul>
                                                                                    </div>
                                                                                <?php } else { ?>
                                                                                    No images found for this post
                                                                            <?php
                                                                                            }
                                                                                        } else {
                                                                                            echo "An error occurred while fetching the image. Please try again later.";
                                                                                        }

                                                                                        // Close the statement
                                                                                        $stmt->close();
                                                                            ?>

                                                                            </p>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <div class="shadow-lg p-3 mb-5 bg-white rounded" style="margin-top: 30px;">
                                                                                    <form action="bike_sale_handler.php" method="POST" style="padding: 20px;" enctype="multipart/form-data">
                                                                                        <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                                                                        <div class="form-group">
                                                                                            <label>Attach images of the bike
                                                                                                (multiple files allowed)</label>
                                                                                            <br />
                                                                                            <small>Only landscape images will be uploaded</small>
                                                                                            <br />
                                                                                            <br />
                                                                                            <input type="file" class="form-control-file" name="bike_images[]" multiple required />
                                                                                            <div class="invalid-feedback">Please
                                                                                                attach images of the bike.</div>
                                                                                        </div>
                                                                                        <div class="cart-checkout">
                                                                                            <button type="submit" name="submit_image" class="btn btn-primary btn-block">Upload</button>
                                                                                        </div>
                                                                                    </form>

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- End Pof add images -->

                                                                <!-- Start edit post -->
                                                                <li class="actions-wishlist" data-toggle="tooltip" data-placement="top" title="Edit post"><a href="#edit_post<?php echo htmlspecialchars($row['id']); ?>" class="btn open-wishlist-popup"><i class="icon ti-pencil"></i></a>
                                                                </li>

                                                                <div id="edit_post<?php echo htmlspecialchars($row['id']); ?>" class="quickview-popup magnific-popup mfp-hide">
                                                                    <div class="quickview-content">
                                                                        <h3 class="text-center">Edit post</h3>
                                                                        <hr>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div style="margin-top: 30px; border: 2px solid #ccc; padding: 20px;">
                                                                                    <ul class="d-flex flex-column pro-lists">
                                                                                        <li><i class="fa fa-star mr-2" aria-hidden="true"></i>Only Kenyan registered bikes are
                                                                                            eligible
                                                                                            for
                                                                                            sale.</li>
                                                                                        <li><i class="fa fa-star mr-2" aria-hidden="true"></i>Provide accurate and detailed
                                                                                            information
                                                                                            about
                                                                                            the bike's condition.</li>
                                                                                        <li><i class="fa fa-star mr-2" aria-hidden="true"></i>Mention any modifications or
                                                                                            aftermarket
                                                                                            parts
                                                                                            installed on the bike.</li>
                                                                                        <li><i class="fa fa-star mr-2" aria-hidden="true"></i>Include clear images of the bike from
                                                                                            different
                                                                                            angles.</li>
                                                                                        <li><i class="fa fa-star mr-2" aria-hidden="true"></i>Specify the bike's make, model, year,
                                                                                            and
                                                                                            engine capacity (cc).</li>
                                                                                        <li><i class="fa fa-star mr-2" aria-hidden="true"></i>Provide the current mileage of the
                                                                                            bike in
                                                                                            kilometers.</li>
                                                                                        <li><i class="fa fa-star mr-2" aria-hidden="true"></i>Mention if the bike is still under
                                                                                            warranty,
                                                                                            and if so, provide warranty details.</li>
                                                                                        <li><i class="fa fa-star mr-2" aria-hidden="true"></i>Clearly state the asking price for the
                                                                                            bike.
                                                                                        </li>
                                                                                        <li><i class="fa fa-star mr-2" aria-hidden="true"></i>Include contact information for
                                                                                            potential
                                                                                            buyers to reach you.</li>
                                                                                        <li><i class="fa fa-star mr-2" aria-hidden="true"></i>Ensure the bike is in good condition
                                                                                            and
                                                                                            ready
                                                                                            for transfer of ownership.</li>
                                                                                    </ul>
                                                                                </div>

                                                                                <!-- Sponsored ad -->
                                                                                <?php include 'sponsored_ad.php'; ?>

                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <div class="shadow-lg p-3 mb-5 bg-white rounded" style="margin-top: 30px;">
                                                                                    <form action="bike_sale_handler.php" method="POST" style="padding: 20px;">
                                                                                        <input type="hidden" class="form-control" name="bike_id" value="<?php echo htmlspecialchars($row['id']) ?>" required />
                                                                                        <input type="hidden" class="form-control" name="string_id" value="<?php echo htmlspecialchars($row['random_string_id']) ?>" required />
                                                                                        <div class="form-group">
                                                                                            <label>Name of the bike</label>
                                                                                            <input type="text" class="form-control" name="bike_name" value="<?php echo htmlspecialchars($row['bike_name']) ?>" required />
                                                                                        </div>
                                                                                        <br>
                                                                                        <div class="form-group">
                                                                                            <label>Year</label>
                                                                                            <input type="number" class="form-control" name="bike_year" value="<?php echo htmlspecialchars($row['bike_year']) ?>" required />
                                                                                        </div>
                                                                                        <br>
                                                                                        <div class="form-group">
                                                                                            <label>Bike cc</label>
                                                                                            <input type="number" class="form-control" name="bike_cc" value="<?php echo htmlspecialchars($row['bike_cc']) ?>" required />
                                                                                        </div>
                                                                                        <br>
                                                                                        <div class="form-group">
                                                                                            <label>Mileage (in KMs)</label>
                                                                                            <input type="number" class="form-control" name="bike_mileage" value="<?php echo htmlspecialchars($row['bike_mileage']) ?>" required />
                                                                                        </div>
                                                                                        <br>
                                                                                        <div class="form-group">
                                                                                            <label>Viewing location</label>
                                                                                            <input type="text" class="form-control" name="bike_location" value="<?php echo htmlspecialchars($row['bike_location']) ?>" required />
                                                                                        </div>
                                                                                        <br>
                                                                                        <div class="form-group">
                                                                                            <label>Price</label>
                                                                                            <input type="number" class="form-control" name="bike_price" value="<?php echo htmlspecialchars($row['bike_price']) ?>" required />
                                                                                        </div>
                                                                                        <br>
                                                                                        <div class="form-group">
                                                                                            <label>Color</label>
                                                                                            <input type="text" class="form-control" name="bike_color" value="<?php echo htmlspecialchars($row['bike_color']) ?>" required />
                                                                                        </div>
                                                                                        <br>
                                                                                        <div class="form-group">
                                                                                            <label>Logbook availability</label>
                                                                                            <select class="form-control" name="logbook_availability" required>
                                                                                                <option value="" selected disabled> </option>
                                                                                                <option value="available" <?php if ($row['logbook_availability'] == 'available')
                                                                                                                                echo 'selected'; ?>>Available</option>
                                                                                                <option value="pending" <?php if ($row['logbook_availability'] == 'pending')
                                                                                                                            echo 'selected'; ?>>Pending from previous owner</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <br>
                                                                                        <div class="form-group">
                                                                                            <label>Condition</label>
                                                                                            <select class="form-control" name="bike_condition" required>
                                                                                                <option value="" selected disabled> </option>
                                                                                                <option value="new" <?php if ($row['bike_condition'] == 'new')
                                                                                                                        echo 'selected'; ?>>New</option>
                                                                                                <option value="used" <?php if ($row['bike_condition'] == 'used')
                                                                                                                            echo 'selected'; ?>>Used</option>
                                                                                                <option value="project" <?php if ($row['bike_condition'] == 'project')
                                                                                                                            echo 'selected'; ?>>Project</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <br>
                                                                                        <div class="cart-note">
                                                                                            <div class="form-group">
                                                                                                <label for="cart-note">Other bike details</label>
                                                                                                <textarea class="form-control" name="bike_details" id="cart-note" rows="15" required><?php echo htmlspecialchars($row['bike_details']) ?>"</textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                        <br>
                                                                                        <div class="cart-checkout">
                                                                                            <button type="submit" name="edit_post" class="btn btn-primary btn-block">Submit
                                                                                                data</button>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- End edit post -->


                                                                <!-- Delete -->
                                                                <li class="actions-wishlist" data-toggle="tooltip" data-placement="top" title="Delete post"><a href="#delete_post<?php echo htmlspecialchars($row['id']); ?>" class="btn open-wishlist-popup"><i class="icon ti-trash"></i></a>
                                                                </li>

                                                                <div id="delete_post<?php echo htmlspecialchars($row['id']); ?>" class="quickview-popup magnific-popup mfp-hide">
                                                                    <div class="quickview-content">
                                                                        <h3 class="text-center">Are you sure you want to delete this post?</h3>
                                                                        <hr>
                                                                        <div class="row">
                                                                            <div class="col-md-3">

                                                                            </div>
                                                                            <div class="col-md-6" style="text-align:center">
                                                                                <div class="shadow-lg p-3 mb-5 bg-white rounded" style="margin-top: 30px;">
                                                                                    <form action="bike_sale_handler.php" method="POST" style="padding: 20px;">
                                                                                        <input type="hidden" class="form-control" name="bike_id" value="<?php echo htmlspecialchars($row['id']) ?>" required />
                                                                                        <input type="hidden" class="form-control" name="string_id" value="<?php echo htmlspecialchars($row['random_string_id']) ?>" required />
                                                                                        <input type="hidden" class="form-control" name="deleted" value="deleted" required />
                                                                                        <div class="form-group">
                                                                                            <p>You will not be able to recover this post.</p>
                                                                                        </div>

                                                                                        <div class="cart-checkout">
                                                                                            <button type="submit" name="delete_post" class="btn btn-danger btn-block">Delete
                                                                                                permanently</button>
                                                                                    </form>
                                                                                </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3">

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                    </div>
                                                    <!-- End delete post -->

                                                    <!-- Sold -->
                                                    <li class="actions-wishlist" data-toggle="tooltip" data-placement="top" title="Mark sold"><a href="#mark_sold<?php echo htmlspecialchars($row['id']); ?>" class="btn open-wishlist-popup"><i class="icon ti-receipt"></i></a>
                                                    </li>

                                                    <div id="mark_sold<?php echo htmlspecialchars($row['id']); ?>" class="quickview-popup magnific-popup mfp-hide">
                                                        <div class="quickview-content">
                                                            <h3 class="text-center">Confirm item has been sold</h3>
                                                            <hr>
                                                            <div class="row">
                                                                <div class="col-md-3">

                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="shadow-lg p-3 mb-5 bg-white rounded" style="margin-top: 30px;">
                                                                        <form action="bike_sale_handler.php" method="POST" style="padding: 20px;">
                                                                            <input type="hidden" class="form-control" name="bike_id" value="<?php echo htmlspecialchars($row['id']) ?>" required />
                                                                            <input type="hidden" class="form-control" name="string_id" value="<?php echo htmlspecialchars($row['random_string_id']) ?>" required />
                                                                            <input type="hidden" class="form-control" name="sold" value="sold" required />

                                                                            <div class="cart-checkout" style="text-align:center">
                                                                                <button type="submit" name="sold_bike" class="btn btn-danger btn-block">Item
                                                                                    sold</button>
                                                                        </form>
                                                                    </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End sold -->
                                                </ul>
                                            <?php }
                                            ?>

                                            </div>
                                        </div>
                                        <div class="product-details col-md-4">
                                            <div class="col-md-6">
                                                <h3 class="product-title">
                                                    <strong>
                                                        <?php echo htmlspecialchars($row['bike_name'], ENT_QUOTES, 'UTF-8'); ?>
                                                    </strong>
                                                </h3>
                                                <div class="product-description">
                                                    <p>
                                                        <strong>Year:</strong>
                                                        <?php echo htmlspecialchars($row['bike_year'], ENT_QUOTES, 'UTF-8'); ?>
                                                        <br>
                                                        <strong>Capacity:</strong>
                                                        <?php echo htmlspecialchars($row['bike_cc'], ENT_QUOTES, 'UTF-8'); ?>
                                                        cc
                                                        <br>
                                                        <strong>Mileage:</strong>
                                                        <?php echo htmlspecialchars($row['bike_mileage'], ENT_QUOTES, 'UTF-8'); ?>
                                                        KM
                                                        <br>
                                                        <strong>Condition:</strong>
                                                        <?php echo htmlspecialchars($row['bike_condition'], ENT_QUOTES, 'UTF-8'); ?>
                                                        <br>
                                                        <strong>Logbook:</strong>
                                                        <?php echo htmlspecialchars($row['logbook_availability'], ENT_QUOTES, 'UTF-8'); ?>
                                                        <br>
                                                        <strong>Color:</strong>
                                                        <?php echo htmlspecialchars($row['bike_color'], ENT_QUOTES, 'UTF-8'); ?>
                                                        <br>
                                                        <strong>Location:</strong>
                                                        <?php echo htmlspecialchars($row['bike_location'], ENT_QUOTES, 'UTF-8'); ?>
                                                        <br>
                                                        <br>
                                                        <strong>Price:</strong>
                                                        <b style="color:#F76D2b">
                                                            <?php echo number_format((float) $row['bike_price'], 2); ?>
                                                        </b>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-details col-md-4">
                                            <h4 class="product-vendor">
                                                <?php echo htmlspecialchars($row['date_posted'], ENT_QUOTES, 'UTF-8'); ?>
                                            </h4>
                                            <br>
                                            <?php echo nl2br(htmlspecialchars($row['bike_details'], ENT_QUOTES, 'UTF-8')); ?>
                                        </div>
                            </div>
                        </div>
                    <?php }
                                } else { ?>
                    <p style="text-align: left;  display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 20vh; ">
                        You havent posted anything. Start selling now!
                    </p>
                <?php } ?>
                    </div>
                    <!-- Start Pagination -->
                    <nav class="blog-pagination my-sm-5 my-3 d-flex justify-content-center" aria-label="Page navigation">
                        <ul class="pagination">
                            <?php if ($currentPage > $firstPage) : ?>
                                <li class="page-item"><a class="page-link rounded-0" href="?page=<?= $previousPage ?>"><i class="icon fa fa-long-arrow-left mr-1" aria-hidden="true"></i>
                                        Prev</a></li>
                            <?php endif; ?>

                            <?php for ($page = 1; $page <= $lastPage; $page++) : ?>
                                <li class="page-item <?= ($currentPage == $page) ? 'active' : '' ?>"><a class="page-link" href="?page=<?= $page ?>"><?= $page ?></a></li>
                            <?php endfor; ?>

                            <?php if ($currentPage < $lastPage) : ?>
                                <li class="page-item"><a class="page-link rounded-0" href="?page=<?= $nextPage ?>">Next <i class="icon fa fa-long-arrow-right ml-1" aria-hidden="true"></i></a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                    <!-- End Pagination -->
                </div>
            </div>
        </div>
    </div>
</main>
<!-- End Main Content -->



<!-- Pop ups -->
<!-- Sell Bike Popup -->
<div id="open-quickview-popups" class="quickview-popup magnific-popup mfp-hide">
    <div class="quickview-content">
        <h3 class="text-center">Read carefully these instructions before submitting your post</h3>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <div style="margin-top: 30px; border: 2px solid #ccc; padding: 20px;">
                    <ul class="d-flex flex-column pro-lists">
                        <li><i class="fa fa-star mr-2" aria-hidden="true"></i>Only Kenyan registered bikes are eligible
                            for
                            sale.</li>
                        <li><i class="fa fa-star mr-2" aria-hidden="true"></i>Provide accurate and detailed information
                            about
                            the bike's condition.</li>
                        <li><i class="fa fa-star mr-2" aria-hidden="true"></i>Mention any modifications or aftermarket
                            parts
                            installed on the bike.</li>
                        <li><i class="fa fa-star mr-2" aria-hidden="true"></i>Include clear images of the bike from
                            different
                            angles.</li>
                        <li><i class="fa fa-star mr-2" aria-hidden="true"></i>Specify the bike's make, model, year, and
                            engine capacity (cc).</li>
                        <li><i class="fa fa-star mr-2" aria-hidden="true"></i>Provide the current mileage of the bike in
                            kilometers.</li>
                        <li><i class="fa fa-star mr-2" aria-hidden="true"></i>Mention if the bike is still under
                            warranty,
                            and if so, provide warranty details.</li>
                        <li><i class="fa fa-star mr-2" aria-hidden="true"></i>Clearly state the asking price for the
                            bike.
                        </li>
                        <li><i class="fa fa-star mr-2" aria-hidden="true"></i>Include contact information for potential
                            buyers to reach you.</li>
                        <li><i class="fa fa-star mr-2" aria-hidden="true"></i>Ensure the bike is in good condition and
                            ready
                            for transfer of ownership.</li>
                    </ul>
                </div>

                <!-- Sponsored ad -->
                <?php include 'sponsored_ad.php'; ?>

            </div>

            <div class="col-md-6">
                <div class="shadow-lg p-3 mb-5 bg-white rounded" style="margin-top: 30px;">
                    <form action="bike_sale_handler.php" method="POST" style="padding: 20px;">
                        <input type="hidden" class="form-control" name="system_user" value="<?php echo htmlspecialchars($_SESSION['user_identity']) ?>" />

                        <div class="form-group">
                            <label>Name of the bike</label>
                            <input type="text" class="form-control" name="bike_name" required />
                        </div>
                        <br>
                        <div class="form-group">
                            <label>Year</label>
                            <input type="number" class="form-control" name="bike_year" required />
                        </div>
                        <br>
                        <div class="form-group">
                            <label>Bike cc</label>
                            <input type="number" class="form-control" name="bike_cc" required />
                        </div>
                        <br>
                        <div class="form-group">
                            <label>Mileage (in KMs)</label>
                            <input type="number" class="form-control" name="bike_mileage" required />
                        </div>
                        <br>
                        <div class="form-group">
                            <label>Viewing location</label>
                            <input type="text" class="form-control" name="bike_location" required />
                        </div>
                        <br>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" class="form-control" name="bike_price" required />
                        </div>
                        <br>
                        <div class="form-group">
                            <label>Color</label>
                            <input type="text" class="form-control" name="bike_color" required />
                        </div>
                        <br>
                        <div class="form-group">
                            <label>Logbook availability</label>
                            <select class="form-control" name="logbook_availability" required>
                                <option value="" selected disabled> </option>
                                <option value="available">Available</option>
                                <option value="pending">Pending from previous owner</option>
                            </select>
                        </div>
                        <br>
                        <div class="form-group">
                            <label>Condition</label>
                            <select class="form-control" name="bike_condition" required>
                                <option value="" selected disabled> </option>
                                <option value="new">New</option>
                                <option value="used">Used</option>
                                <option value="project">Project</option>
                            </select>
                        </div>
                        <br>
                        <div class="cart-note">
                            <div class="form-group">
                                <label for="cart-note">Other bike details</label>
                                <textarea class="form-control" name="bike_details" rows="15" required></textarea>
                            </div>
                        </div>
                        <br>
                        <div class="form-group form-check cart-agree-check">
                            <input type="checkbox" name="terms_agreement" class="form-check-input" id="agree-check" required />
                            <label class="form-check-label ml-3" for="agree-check"> I agree with the terms and
                                conditions.</label>
                        </div>
                        <br>
                        <div class="cart-checkout">
                            <button type="submit" name="submit_post" class="btn btn-primary btn-block">Submit
                                data</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- End of Popup -->


<?php include 'footer.php'; ?>