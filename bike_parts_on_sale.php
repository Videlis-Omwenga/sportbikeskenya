<?php
session_start();

include 'config.php';

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
                    <?php include 'side_bar_nav_sponsored.php'; ?>
                    <div class="product-view-items product-grid col-12 col-sm-12 col-lg-9 sidebar-right">
                        <?php include 'top_images.php' ?>
                        <br>
                        <br>
                        <div class="tabs-header clearfix">
                            <div class="section-header">
                                <h2>Bike parts on sale</h2>
                                <p>Browse the collection of our best selling and top interresting products.<br>You'll definitely find what you are looking for.</p>
                            </div>
                        </div>
                        <hr>
                        <!-- Start Products Grid -->
                        <div class="products products-grid">
                            <div class="row row-sp row-eq-height">
                                <?php
                                $post_delete_status = 'deleted';

                                $showRecordPerPage = 48;
                                if (isset($_GET['page']) && !empty($_GET['page'])) {
                                    $currentPage = $_GET['page'];
                                } else {
                                    $currentPage = 1;
                                }
                                $startFrom = ($currentPage * $showRecordPerPage) - $showRecordPerPage;

                                $totalEmpSQL = "SELECT COUNT(*) AS total FROM `bike_parts_details_table` WHERE post_delete != ?";
                                $totalStmt = $conn->prepare($totalEmpSQL);
                                $totalStmt->bind_param("s", $post_delete_status);

                                $totalStmt->execute();
                                $totalResult = $totalStmt->get_result();
                                $totalData = $totalResult->fetch_assoc()['total'];

                                $lastPage = ceil($totalData / $showRecordPerPage);
                                $firstPage = 1;
                                $nextPage = $currentPage + 1;
                                $previousPage = $currentPage - 1;

                                $query = "SELECT * FROM `bike_parts_details_table` WHERE post_delete != ? ORDER BY id DESC LIMIT ?, ?";
                                $stmt = $conn->prepare($query);
                                $stmt->bind_param("sii", $post_delete_status, $startFrom, $showRecordPerPage);

                                if ($stmt->execute()) {
                                    $result = $stmt->get_result();
                                } else {
                                    $_SESSION['error'] = "Error fetching bike details!";
                                    header('Location: gears_on_sale.php');
                                    exit;
                                }

                                $stmt->close();
                                ?>

                                <?php if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $part_availability = htmlspecialchars($row['part_availability'], ENT_QUOTES, 'UTF-8');
                                ?>
                                        <div class="col-sp col-md-3 col-sm-6 col-6">
                                            <div class="product-item">
                                                <div class="product-image-action">
                                                    <div class="product-image">
                                                            <?php
                                                            $post_identy = htmlspecialchars($row['id']);
                                                            $stmt = $conn->prepare("SELECT `image_name` FROM `parts_images` WHERE post_id = ? AND delete_image = '' ORDER BY id DESC LIMIT 1");
                                                            $stmt->bind_param("s", $post_identy);
                                                            if ($stmt->execute()) {
                                                                $results = $stmt->get_result();
                                                                if ($results->num_rows > 0) {
                                                                    $rows = $results->fetch_assoc();
                                                                    $imageName = $rows['image_name']; ?>
                                                                    <div class="product-label">
                                                                        <?php if ('' == $part_availability) { ?>

                                                                        <?php } else { ?>
                                                                            <span class="label sale">Sold</span>
                                                                        <?php } ?>
                                                                    </div>
                                                                    <a href="bike_parts_details.php?post_id=<?php echo htmlspecialchars($row['random_string_id'], ENT_QUOTES, 'UTF-8') ?>&position=<?php echo htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?>&img=<?php echo htmlspecialchars($imageName); ?>&name=<?php echo htmlspecialchars($row['part_name'], ENT_QUOTES, 'UTF-8') ?>&price=<?php echo htmlspecialchars($row['part_price'], ENT_QUOTES, 'UTF-8') ?>">
                                                                        <img class="img-fluid blur-up lazyload" src="seller_images/<?php echo htmlspecialchars($imageName); ?>" style="max-height: 180px; min-height: 180px;" />
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
                                                    <h3 class="product-title"><?php echo htmlspecialchars($row['part_name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                                    <div class="product-price">
                                                        <span class="sale-price"><?php echo number_format((float) $row['part_price'], 2); ?></span>
                                                    </div>
                                                    <br>
                                                    <h5 class="product-title">
                                                        <?php

                                                        $gear_posted = htmlspecialchars($row['posted_by'], ENT_QUOTES, 'UTF-8');

                                                        try {
                                                            $querr = "SELECT business_name FROM `user_profile` WHERE user_id = ?";
                                                            $stmt = $conn->prepare($querr);
                                                            $stmt->bind_param("s", $gear_posted);

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
                                                            header('Location: gears_on_sale.php');
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

<?php include 'footer.php'; ?>