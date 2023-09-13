<!doctype html>
<html class="no-js" lang="en">

<head>
    <!-- Required meta tags -->
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content=" " />
    <!-- Title -->
    <title>Sport Bikes Kenya</title>
    <!-- Favicon  -->
    <link rel="shortcut icon" href="assets/images/fevicon.png" />

    <!-- *********** CSS Files *********** -->
    <!-- Plugin CSS -->
    <link rel="stylesheet" href="assets/css/plugins.css" />
    <!-- Styles CSS -->
    <link rel="stylesheet" href="assets/css/styles.css" />
    <link rel="stylesheet" href="assets/css/responsive.css" />

    <?php
    if (isset($_GET['img']) && isset($_GET['name'])&& isset($_GET['price']))
     {
        $img = isset($_GET['img']) ? $_GET['img'] : '';
        $price = isset($_GET['price']) ? $_GET['price'] : '';
        $name = isset($_GET['name']) ? $_GET['name'] : '';
    }

    // Get the current page URL dynamically
    $url_to_share = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $encoded_url = urlencode($url_to_share);
    $api_url = "https://graph.facebook.com/v12.0/?id=$encoded_url&fields=engagement&access_token=$access_token";
    $response = file_get_contents($api_url);
    $data = json_decode($response, true);
    $share_count = $data['engagement']['share_count'] ?? 0;

    // Set default Open Graph values
    $og_type = "website"; // Set the appropriate type (e.g., "website", "article", etc.)
    $fb_app_id = 999154054552887; // Replace with your Facebook App ID
    $og_title = "Name: ". $name;
    $og_description = "Price: ". $price. ".00";
    $og_image = 'https://www.sportbikeskenya.com/seller_images/' . basename($img); // Update with your domain
    ?>

    <!-- Open Graph tags -->
    <meta property="og:type" content="<?php echo $og_type; ?>" />
    <meta property="fb:app_id" content="<?php echo $fb_app_id; ?>" />
    <meta property="og:url" content="<?php echo $url_to_share; ?>" />
    <meta property="og:title" content="<?php echo $og_title; ?>" />
    <meta property="og:description" content="<?php echo $og_description; ?>" />
    <meta property="og:image" content="<?php echo $og_image; ?>" />

</head>

<body class="template-index home-version-3">
    <!-- Start Page Loader -->
    <div class="page-loading"></div>
    <!-- End Page Loader -->

    <!--  Start Main Wrapper -->
    <div class="main-wrapper">
        <!-- Start Promotional Bar Section -->
        <div class="promotional-bar border-0 rounded-0 d-flex align-items-center alert alert-warning fade show" role="alert">
            <div class="container-fluid full-promotional-bar">
                <span>Welcome to the new <a href="login.php">Sport Bikes Kenya</a> platform!</span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="ti-close"></i></button>
            </div>
        </div>
        <!-- End Promotional Bar Section -->

        <!-- Start Header Section -->
        <header class="header bg-white">
            <!-- Start Main Header -->
            <div class="main-header">
                <div class="container">
                    <div class="d-flex justify-content-between align-items-center logo-right-menu">
                        <!-- Start Logo -->
                        <div class="col-3 col-sm-3 col-md-2 col-lg-2 navbar-brand logo p-0 m-0">
                            <!-- Start Sticky Menu Toggle -->
                            <button class="sticky-menu-button d-none" type="button"><span class="icon ti-menu"></span><span class="d-none menu-text">Menu</span></button>
                            <!-- End Sticky Menu Toggle -->
                            <a href="index.php">
                                <span class="h1 text-uppercase text-dark px-2">|Kenua</span>
                                <span class="h1 text-uppercase text-success px-2">Bikes</span>
                                <span class="h1 text-uppercase text-dark px-2">Kenya</span>
                            </a>

                        </div>
                        <!-- End Logo -->

                        <!-- Start Right Menu -->
                        <div class="col-9 col-sm-9 col-md-10 col-lg-10 p-0 right-side">
                            <div class="d-flex flex-row-reverse align-items-center right-side-menu">
                                <!-- Start Menu Toggle -->
                                <button class="navbar-toggler d-block d-lg-none" type="button" data-toggle="collapse" data-target="#navbar-collapse"><span class="icon ti-menu"></span><span class="d-none menu-text">Menu</span></button>
                                <!-- End Menu Toggle -->

                                <!-- Start Minicart -->
                                <div class="minicart minicart-v2 float-right" style="margin:40px">
                                    <a href="#" class="cart-btns" title="My account">
                                        <i class="icon ti-user"></i>
                                        <div class="settinglinks" style="display:none"></div>
                                    </a>

                                    <div class="minicart-toggle-popup">
                                        <div class="minicart-bottom-actions">
                                            <div class="my-links">
                                                <h5 style="display: flex; justify-content: left;">My account</h5>
                                                <hr>
                                                <ul class="p-0 m-0">
                                                    <?php
                                                    if (isset($_SESSION['random_access_key'])) { ?>

                                                        <li><a class="item" href="my_profile.php">My profile</a></li>
                                                        <li><a class="item" href="logout.php">Log out</a></li>
                                                    <?php } else {
                                                    ?>
                                                        <?php include 'login_form.php' ?>
                                                        <?php include 'registration_form.php' ?>
                                                    <?php }
                                                    ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Minicart -->
                            </div>
                        </div>
                        <!-- End Right Menu -->
                    </div>
                </div>
            </div>
            <!-- End Main Header -->

            <!-- Start Navigation -->
            <div class="main-navbar">
                <div class="container">
                    <nav class="navigation navbar position-static navbar-expand-lg p-0 col-lg-12">
                        <div id="navbar-collapse" class="navbar-collapse collapse dual-nav">
                            <a href="#" class="closeNav-btn d-lg-none clearfix" id="closeNav" title="Close"><span class="menu-close mr-2">Close</span><i class="ti-close" aria-hidden="true"></i></a>
                            <ul class="navbar-nav">
                                <li class="nav-item dropdown">
                                    <a class="nav-link" href="index.php">Home<span title="Click to show/hide"></span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="bikes_on_sale.php">Bikes on sale
                                        <span class="lbl hot">Bikes</span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="gears_on_sale.php">Riding gears on sale
                                        <span class="lbl new">Gears</span></a>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="bike_parts_on_sale.php">Bike parts on sale
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Mechanics
                                        <span title="Click to show/hide"></span>
                                    </a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link" href="#">Other pages
                                        <span class="arw plush" title="Click to show/hide"></span>
                                    </a>
                                    <div class="megamenu submenu dropdown">
                                        <ul>
                                            <li><a class="item" href="#!">About Us</a></li>
                                            <li><a class="item" href="#!">FAQ's</a></li>
                                            <li><a class="item" href="#!">Contact Us</a></li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link" href="#">Blog
                                        <span class="arw plush" title="Click to show/hide"></span>
                                    </a>
                                    <div class="megamenu submenu dropdown">
                                        <ul>
                                            <li><a class="item" href="#!">Youtube Videos</a></li>
                                            <li><a class="item" href="#!">Defensive riding</a></li>
                                            <li><a class="item" href="#!">Write a blog</a></li>
                                            <li><a class="item" href="#!">Upcoming events</a></li>
                                            <li><a class="item" href="#!">Nyumba kumi</a></li>
                                            <li><a class="item" href="#!">Pizza nights</a></li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </nav>

                    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                        <a class="navbar-brand" href="index.php">Sport Bikes Kenya</a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav ml-auto">
                                <li class="nav-item active"> <a class="nav-link" href="sell_bike.php">Sale Bike <span class="sr-only">(current)</span></a> </li>
                                <li class="nav-item active"> <a class="nav-link" href="sell_gears.php">Sale Gear <span class="sr-only">(current)</span></a> </li>
                                <li class="nav-item active"> <a class="nav-link" href="sell_bike_parts.php">Sale Parts
                                        <span class="sr-only">(current)</span></a> </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>

            <style>
                /* Add styles to the cart drawer */
                .cart-drawer {
                    background-color: #fff;
                    /* Set the background color */
                    padding: 20px;
                    /* Add padding to the cart drawer content */
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    /* Optional: Add a box shadow for a card-like effect */
                    max-width: 500px;
                    /* Optional: Limit the width of the cart drawer */
                }

                /* Customize the close button style */
                .cart-drawer .close-btn {
                    color: #999;
                    font-size: 18px;
                }
            </style>
            <!-- Add this to the <head> section of your HTML -->
            <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v12.0" nonce="YOUR_NONCE"></script>

        </header>
        <!-- End Header Section -->