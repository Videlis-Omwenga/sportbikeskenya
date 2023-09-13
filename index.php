<?php
session_start();
include 'config.php';
?>


<?php include 'header.php'; ?>

<!-- Start Newsletter Popup -->
<div class="modal fade" id="newsletter-popup" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0">
            <div class="modal-body p-0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="ti-close"></i></button>
                <div class="row no-gutters">
                    <!-- Start Newsletter Content -->
                    <div class="col-md-6 d-flex flex-column justify-content-center text-left">
                        <div class="newsletter-details">
                            <h2 class="title">Upcoming system upgrades</h2>
                            <p class="message">Here are our future goals!</p>
                            <p>
                            <ul>
                                <li>1. Buyers and sellers, one selling point</li>
                                <li>2. Mechanics contacts and locations</li>
                                <li>3. Import gears, bikes and parts</li>
                                <li>4. Defensive riding tips </li>
                                <li>5. Riding events</li>
                                <li>6. Nyumba kumis and admins</li>
                            </ul>
                            </p>
                        </div>
                    </div>
                    <!-- End Newsletter Content -->
                    <!-- Start Newsletter Content -->
                    <div class="col-md-6 d-flex flex-column justify-content-center text-left">
                        <div class="newsletter-details">
                            <h2 class="title">Welcome!!</h2>
                            <p class="message">Want to sell a bike, gear or bike parts? Simple!</p>
                            <p>
                            <ul>
                                <li>Step 1. Register</li>
                                <li>Step 2. Log in</li>
                                <li>Step 3. Complete your profile</li>
                                <li>Step 4. Start posting 😇</li>
                            </ul>
                            </p>
                        </div>
                    </div>
                    <!-- End Newsletter Content -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Newsletter Popup -->

<!-- Start Main Content -->
<main class="main-content">
    <!-- Start Banner Slidershow Section -->
    <div class="slideshow-side-banner position-relative sections-spacing">
        <div class="container">
            <!-- Session messages  -->
            <?php include 'session_messages.php'; ?>

            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-9 slideshow-left">
                    <!-- Start Slidershow Banner -->
                    <div class="slideshow slideshow-banner">
                        <div class="single-slide slider-height bg-style blur-up lazyload d-flex align-items-center" style="background-image:url(imgs/2.jpg);">
                            <div class="container slideshow-details">
                                <h3>Sport Bikes On Sale!</h3>
                                <p>Super Sport Bikes, Sport Touring Bikes, Adventure Sport Bikes, Cruiser Sport Bikes, Naked Bikes (Streetfighters)</p>
                                <a href="gears_on_sale.php" class="btn btn-primary">Buy now!</a>
                            </div>
                        </div>
                        <div class="single-slide slider-height bg-style blur-up lazyload d-flex align-items-center" style="background-image:url(imgs/1.jpg);">
                            <div class="container slideshow-details">
                                <h3>Helmets On Sale!</h3>
                                <p>Full-Face Helmets, Modular Helmets, Open-Face Helmets, Half Helmets</p>
                                <a href="bike_on_sale.php" class="btn btn-primary">Buy now!</a>
                            </div>
                        </div>
                        <div class="single-slide slider-height bg-style blur-up lazyload d-flex align-items-center" style="background-image:url(imgs/3.jpg);">
                            <div class="container slideshow-details">
                                <h3>Bike Parts On Sale!</h3>
                                <p>Shocks, brake pards, air filters, tyres, exhausts, side mirrors</p>
                                <a href="bike_parts_on_sale.php" class="btn btn-primary">Buy now!</a>
                            </div>
                        </div>
                    </div>
                    <!-- End Slidershow Banner -->
                </div>

                <div class="col-12 col-sm-12 col-md-12 col-lg-3 side-banner-right">
                    <div class="row collection-block">
                        <div class="col-12 col-sm-6 col-md-6 col-lg-12 collection-item">
                            <div class="slide-banner1 item-banner text-center">
                                <a class="collection-img animate-scale zoom-hover-effect" href="#!"><img class="img-fluid" src="imgs/side.jpg" alt="image" title="image" /></a>
                                <div class="slide-banner1-title collection-detail">
                                    <a href="#">
                                        <h3>Sport Bikes Kenya</h3>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-6 col-lg-12 collection-item">
                            <div class="slide-banner2 item-banner text-center">
                                <a class="collection-img animate-scale zoom-hover-effect" href="#!"><img class="img-fluid" src="imgs/side2.jpg" alt="image" title="image" /></a>
                                <div class="slide-banner1-title collection-detail">
                                    <a href="#">
                                        <h3>Sell and Buy at ease!</h3>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Banner Slidershow Section -->


    <!-- Start Products -->
    <div class="product-view-items product-grid" style="margin-bottom: 150px">
        <div class="container">
            <div class="tabs-header clearfix">
                <div class="section-header">
                    <h2>Bikes on sale</h2>
                    <p>Browse the collection of our best selling and top interresting products.<br>You'll definitely find what you are looking for.</p>
                </div>
            </div>

            <!-- Start Products Grid -->
            <div class="products products-grid">
                <div class="row row-sp row-eq-height">
                    <?php
                    $post_delete_status = 'deleted';

                    $query = "SELECT * FROM `bike_details_table` WHERE post_delete != ? ORDER BY id DESC LIMIT 12";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("s", $post_delete_status);

                    if ($stmt->execute()) {
                        $result = $stmt->get_result();
                    } else {
                        $_SESSION['error'] = "Error fetching bike details!";
                        header('Location: index.php');
                        exit;
                    }

                    $stmt->close();
                    ?>

                    <?php if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) { ?>
                            <div class="col-sp col-md-2 col-sm-6 col-6">
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
                                                    $imageName = $rows['image_name']; ?>
                                                    <a href="bike_details.php?post_id=<?php echo htmlspecialchars($row['random_string_id'], ENT_QUOTES, 'UTF-8') ?>&position=<?php echo htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?>&img=<?php echo htmlspecialchars($imageName); ?>&name=<?php echo htmlspecialchars($row['bike_name'], ENT_QUOTES, 'UTF-8') ?>&price=<?php echo htmlspecialchars($row['bike_price'], ENT_QUOTES, 'UTF-8') ?>">
                                                        <img class="img-fluid blur-up lazyload" src="seller_images/<?php echo htmlspecialchars($imageName); ?>" style="max-height: 130px; min-height: 130px;" />
                                                    </a>
                                                <?php } else { ?>
                                                    <img class="img-fluid blur-up lazyload" src="assets/images/usable/not_found.png" alt="" style="max-height: 130px; min-height: 130px;" title="No image!">

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
                                    <div class=" product-details">
                                        <h3 class="product-title"><?php echo htmlspecialchars($row['bike_name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                        <div class="product-price">
                                            <span class="sale-price"><?php echo number_format((float) $row['bike_price'], 2); ?></span>
                                        </div>
                                        <br>
                                        <h5 class="product-title">
                                            <?php

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

                                                            <?php echo '<i class="fa fa-user mr-1"></i> Seller:  ' . $business_name ?>

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
                                                header('Location: index.php');
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
                <!-- Start Load More Button -->
                <div class="product-readmore text-center">
                    <a href="bikes_on_sale.php"><button type="submit" class="btn btn-secondary">Load More</button></a>
                </div>
                <!-- End Load More Button -->
            </div>
        </div>
    </div>
    <!-- End Products -->




    <!-- Start Products -->
    <div class="product-view-items product-grid" style="margin-bottom: 150px">
        <div class="container">
            <div class="tabs-header clearfix">
                <div class="section-header">
                    <h2>Riding gears on sale</h2>
                    <p>Browse the collection of our best selling and top interresting products.<br>You'll definitely find what you are looking for.</p>
                </div>
            </div>

            <!-- Start Products Grid -->
            <div class="products products-grid">
                <div class="row row-sp row-eq-height">
                    <?php
                    $post_delete_status = 'deleted';

                    $querys = "SELECT * FROM `gears_details_table` WHERE post_delete != ? ORDER BY id DESC LIMIT 12";
                    $stmt = $conn->prepare($querys);
                    $stmt->bind_param("s", $post_delete_status);

                    if ($stmt->execute()) {
                        $resultt = $stmt->get_result();
                    } else {
                        $_SESSION['error'] = "Error fetching bike details!";
                        header('Location: index.php');
                        exit;
                    }

                    $stmt->close();
                    ?>

                    <?php if ($resultt->num_rows > 0) {
                        while ($roww = $resultt->fetch_assoc()) { ?>
                            <div class="col-sp col-md-2 col-sm-6 col-6">
                                <div class="product-item">
                                    <div class="product-image-action">
                                        <div class="product-image">
                                            <?php
                                            $gear_identy = htmlspecialchars($roww['id']);
                                            $stmt = $conn->prepare("SELECT `image_name` FROM `gears_images` WHERE post_id = ? AND delete_image = '' ORDER BY id DESC LIMIT 1");
                                            $stmt->bind_param("s", $gear_identy);
                                            if ($stmt->execute()) {
                                                $gear_results = $stmt->get_result();
                                                if ($gear_results->num_rows > 0) {
                                                    $rowss = $gear_results->fetch_assoc();
                                                    $imageName = $rowss['image_name']; ?>
                                                    <a href="bike_details.php?post_id=<?php echo htmlspecialchars($roww['random_string_id'], ENT_QUOTES, 'UTF-8') ?>&position=<?php echo htmlspecialchars($roww['id'], ENT_QUOTES, 'UTF-8') ?>&img=<?php echo htmlspecialchars($imageName); ?>&name=<?php echo htmlspecialchars($row['gear_name'], ENT_QUOTES, 'UTF-8') ?>&price=<?php echo htmlspecialchars($row['gear_price'], ENT_QUOTES, 'UTF-8') ?>">
                                                        <img class="img-fluid blur-up lazyload" src="seller_images/<?php echo htmlspecialchars($imageName); ?>" style="max-height: 180px; min-height: 180px;" />
                                                    </a>
                                                <?php } else { ?>
                                                    <img class="img-fluid blur-up lazyload" src="assets/images/usable/not_found.png" alt="" style="max-height: 180px; min-height: 180px;" title="No image!">
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
                                        <h3 class="product-title"><?php echo htmlspecialchars($roww['gear_name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                        <div class="product-price">
                                            <span class="sale-price"><?php echo number_format((float) $roww['gear_price'], 2); ?></span>
                                        </div>
                                        <br>
                                        <h5 class="product-title">
                                            <?php

                                            $gear_posted = htmlspecialchars($roww['posted_by'], ENT_QUOTES, 'UTF-8');

                                            try {
                                                $querr = "SELECT business_name FROM `user_profile` WHERE user_id = ?";
                                                $stmt = $conn->prepare($querr);
                                                $stmt->bind_param("s", $gear_posted);

                                                if ($stmt->execute()) {
                                                    $get_data = $stmt->get_result();

                                                    if ($get_data->num_rows > 0) {
                                                        while ($person = $get_data->fetch_assoc()) {
                                                            $business_name = htmlspecialchars($person['business_name']); ?>

                                                            <?php echo '<i class="fa fa-user mr-1"></i> Seller:  ' . $business_name ?>

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
                                                header('Location: index.php');
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
                <!-- Start Load More Button -->
                <div class="product-readmore text-center">
                    <a href="gears_on_sale.php"><button type="submit" class="btn btn-secondary">Load More</button></a>
                </div>
                <!-- End Load More Button -->
            </div>
        </div>
    </div>
    <!-- End Products -->




    <!-- Start Products -->
    <div class="product-view-items product-grid" style="margin-bottom: 150px">
        <div class="container">
            <div class="tabs-header clearfix">
                <div class="section-header">
                    <h2>Bike parts on sale</h2>
                    <p>Browse the collection of our best selling and top interresting products.<br>You'll definitely find what you are looking for.</p>
                </div>
            </div>

            <!-- Start Products Grid -->
            <div class="products products-grid">
                <div class="row row-sp row-eq-height">
                    <?php
                    $post_delete_status = 'deleted';

                    $query = "SELECT * FROM `bike_parts_details_table` WHERE post_delete != ? ORDER BY id DESC LIMIT 12";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("s", $post_delete_status);

                    if ($stmt->execute()) {
                        $results = $stmt->get_result();
                    } else {
                        $_SESSION['error'] = "Error fetching bike details!";
                        header('Location: index.php');
                        exit;
                    }

                    $stmt->close();
                    ?>

                    <?php if ($results->num_rows > 0) {
                        while ($rows = $results->fetch_assoc()) { ?>
                            <div class="col-sp col-md-2 col-sm-6 col-6">
                                <div class="product-item">
                                    <div class="product-image-action">
                                        <div class="product-image">
                                            <?php
                                            $post_identy_parts = htmlspecialchars($rows['id']);
                                            $stmt = $conn->prepare("SELECT `image_name` FROM `parts_images` WHERE post_id = ? AND delete_image = '' ORDER BY id DESC LIMIT 1");
                                            $stmt->bind_param("s", $post_identy_parts);
                                            if ($stmt->execute()) {
                                                $resulted = $stmt->get_result();
                                                if ($resulted->num_rows > 0) {
                                                    $rowed = $resulted->fetch_assoc();
                                                    $imageName = $rowed['image_name']; ?>
                                                    <a href="bike_parts_details.php?post_id=<?php echo htmlspecialchars($rows['random_string_id'], ENT_QUOTES, 'UTF-8') ?>&position=<?php echo htmlspecialchars($rows['id'], ENT_QUOTES, 'UTF-8') ?>&img=<?php echo htmlspecialchars($imageName); ?>&name=<?php echo htmlspecialchars($row['part_name'], ENT_QUOTES, 'UTF-8') ?>&price=<?php echo htmlspecialchars($row['part_price'], ENT_QUOTES, 'UTF-8') ?>">
                                                        <img class="img-fluid blur-up lazyload" src="seller_images/<?php echo htmlspecialchars($imageName); ?>" style="max-height: 180px; min-height: 180px;" />
                                                    </a>
                                                <?php } else { ?>
                                                    <img class="img-fluid blur-up lazyload" src="assets/images/usable/not_found.png" alt="" style="max-height: 180px; min-height: 180px;">

                                            <?php }
                                            } else {
                                                // Log the error in a secure log file
                                                error_log("Error fetching image!");

                                                echo "An error occurred while fetching the image. Please try again later.";
                                            }

                                            // Close the statement
                                            $stmt->close();
                                            ?>
                                        </div>
                                    </div>
                                    <div class="product-details">
                                        <h3 class="product-title"><?php echo htmlspecialchars($rows['part_name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                        <div class="product-price">
                                            <span class="sale-price"><?php echo number_format((float) $rows['part_price'], 2); ?></span>
                                        </div>
                                        <br>
                                        <h5 class="product-title">
                                            <?php

                                            $part_posted = htmlspecialchars($rows['posted_by'], ENT_QUOTES, 'UTF-8');

                                            try {
                                                $querr = "SELECT business_name FROM `user_profile` WHERE user_id = ?";
                                                $stmt = $conn->prepare($querr);
                                                $stmt->bind_param("s", $part_posted);

                                                if ($stmt->execute()) {
                                                    $get_data = $stmt->get_result();

                                                    if ($get_data->num_rows > 0) {
                                                        while ($person = $get_data->fetch_assoc()) {
                                                            $business_name = htmlspecialchars($person['business_name']); ?>

                                                            <?php echo '<i class="fa fa-user mr-1"></i> Seller:  ' . $business_name ?>

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
                                                header('Location: index.php');
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
                <!-- Start Load More Button -->
                <div class="product-readmore text-center">
                    <a href="bike_parts_on_sale.php"><button type="submit" class="btn btn-secondary">Load More</button></a>
                </div>
                <!-- End Load More Button -->
            </div>
        </div>
    </div>
    <!-- End Products -->
</main>
<!-- End Main Content -->

<?php include 'footer.php'; ?>