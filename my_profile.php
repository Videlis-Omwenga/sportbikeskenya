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



<?php

$my_details = htmlspecialchars($_SESSION['user_identity']);

try {
    $querr = "SELECT * FROM `user_profile` WHERE user_id = ?";
    $stmt = $conn->prepare($querr);
    $stmt->bind_param("s", $my_details);

    if ($stmt->execute()) {
        $get_data = $stmt->get_result();

        if ($get_data->num_rows > 0) {
            while ($persons = $get_data->fetch_assoc()) {
                $first_name_user = htmlspecialchars($persons['first_name']);
                $second_name_user = htmlspecialchars($persons['second_name']);
                $email = htmlspecialchars($persons['email']);
                $user_contacts = htmlspecialchars($persons['user_contacts']);
                $business_name = htmlspecialchars($persons['business_name']);
                $user_location = htmlspecialchars($persons['user_location']);
                $user_description = htmlspecialchars($persons['user_description']);
                $date_registered = htmlspecialchars($persons['date_registered']); ?>
<?php }
        } else {
            echo "Account not found!";
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

<!-- Start My Account -->
<div class="my-account-content" style="margin-bottom:100px">
    <div class="container">
        <div class="account-upper-info">
            <div class="row align-items-center justify-content-center row-eq-height no-gutters">
                <div class="info-item col-12 col-sm-12 col-md-3 col-lg-3">
                    <p class="mb-1">Hello <?php echo $first_name_user . ' ' . $second_name_user ?></p>
                    <p>(<strong>not </strong><?php echo $first_name_user . ' ' . $second_name_user ?>?) </p>
                    <p class="mb-0"><a class=" link-color" href="logout.php">Log out</a></p>
                </div>
                <div class="info-item col-12 col-sm-12 col-md-3 col-lg-3">
                    <p class="mb-1">Need Assistance? </p>
                    <p> Admin service at: </p>
                    <p class="mb-0"><a href="mailto:sportbikeskenya@gmail.com">sportbikeskenya@gmail.com</a></p>
                </div>
                <div class="info-item col-12 col-sm-12 col-md-3 col-lg-3">
                    <p class="mb-1">WhatsApp us on </p>
                    <p><small>(Click on the number to start messaging)</small> </p>
                    <p class="mb-0"><a href="https://api.whatsapp.com/send?phone=254711414182">0711414182</a></p>
                </div>
                <div class="info-item col-12 col-sm-12 col-md-3 col-lg-3">
                    <p class="mb-1">Call us on </p>
                    <p><small>(Click on the call icon to start a call)</small> </p>
                    <form action="tel:+254711414182">
                        <button type="submit" class="btn btn-outline-warning btn-sm">
                            <i class="fa fa-phone"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="account-inner-info">
            <div class="row">
                <!-- Start My Account Nav -->
                <div class="account-nav mb-5 mb-sm-0 col-12 col-sm-3 col-md-3">
                    <div class="nav flex-row flex-sm-nowrap flex-sm-column nav-pills" id="v-pills-tab" role="tablist">
                        <a class="nav-link active" id="my-account-home-tab" data-toggle="pill" href="#my-account-home" role="tab" aria-controls="my-account-home" aria-selected="true">Dashboard</a>
                        <a class="nav-link" id="my-account-order-tab" data-toggle="pill" href="#my-account-order" role="tab" aria-controls="my-account-order" aria-selected="false">Edit profile</a>
                        <a class="nav-link" href="logout.php">Logout (end session)</a>
                    </div>
                </div>
                <!-- End My Account Nav -->

                <!-- Start My Account Details -->
                <div class="account-details col-12 col-sm-9 col-md-9">
                    <div class="tab-content" id="account-tabContent">
                        <!-- Start Dashboard -->
                        <div class="dashboard tab-pane fade show active" id="my-account-home" role="tabpanel" aria-labelledby="my-account-home-tab">
                            <div class="tab-pane fade active show" id="dashboard">
                                <?php include 'session_messages.php' ?>
                                <h3>Dashboard </h3>
                                <p>
                                <div class="group-product-tbl table-responsive">
                                    <table class="table table-bordered mb-4">
                                        <thead>
                                            <tr>
                                                <th>First name</th>
                                                <th><?php echo $first_name_user; ?></th>
                                            </tr>
                                            <tr>
                                                <th>Second name</th>
                                                <th><?php echo $second_name_user ?></th>
                                            </tr>
                                            <tr>
                                                <th>Email</th>
                                                <th><?php echo $email ?></th>
                                            </tr>
                                            <tr>
                                                <th>Business contacts</th>
                                                <th><?php echo $user_contacts ?></th>
                                            </tr>
                                            <tr>
                                                <th>Business name</th>
                                                <th><?php echo $business_name ?></th>
                                            </tr>
                                            <tr>
                                                <th>Business location</th>
                                                <th><?php echo $user_location ?></th>
                                            </tr>
                                            <tr>
                                                <th>Business description</th>
                                                <th><?php echo nl2br($user_description) ?></th>
                                            </tr>
                                            <tr style="color:green">
                                                <th>Date registered</th>
                                                <th><?php echo $date_registered; ?>
                                                </th>
                                            </tr>
                                        </thead>
                                        </tbody>
                                    </table>
                                    <br>
                                    <br>
                                    <br>
                                    <br>
                                    <br>
                                    <br>
                                    <br>
                                    <br>
                                    <br>
                                    <br>
                                    <br>
                                </div>
                                </p>
                            </div>
                        </div>
                        <!-- End Dashboard -->

                        <!-- Start Orders -->
                        <div class="tab-pane fade" id="my-account-order" role="tabpanel" aria-labelledby="my-account-order-tab">
                            <div class="tab-pane fade active show" id="orders">
                                <div class="order-table table-responsive">
                                    <!-- Start Change Password -->
                                    <div class="change-password">
                                        <div class="container">
                                            <div class="row row-sp">
                                                <div class="col-sp col-12 col-sm-12 col-md-12 col-lg-6 offset-lg-3">
                                                    <div class="page-title text-center">
                                                        <h1>Edit profile</h1>
                                                        <p class="subtitle">Use the form below to edit your profile.</p>
                                                    </div>

                                                    <form action="user_entry_details.php" class="password-change-form needs-validation" method="POST">
                                                        <input type="hidden" name="user_id" class="form-control" value="<?php echo $my_details; ?>" required />
                                                        <div class="form-group">
                                                            <label>First name *</label>
                                                            <input type="text" class="form-control" name="first_name" value="<?php echo $first_name_user; ?>" required />
                                                        </div>
                                                        <br>
                                                        <div class="form-group">
                                                            <label>Second name *</label>
                                                            <input type="text" class="form-control" name="second_name" value="<?php echo $second_name_user; ?>" required />
                                                        </div>
                                                        <br>
                                                        <div class="form-group">
                                                            <label>Email *</label>
                                                            <input type="email" name="email" class="form-control" value="<?php echo $email; ?>" required />
                                                            <small class="form-text text-muted">Use an email that we can reach you.</small>
                                                        </div>
                                                        <br>
                                                        <div class="control-group">
                                                            <label>Business contacts</label>
                                                            <small style="color: red;">Format: 254-712345678</small>
                                                            <input type="tel" class="form-control" name="user_contacts" value="<?php echo $user_contacts; ?>" pattern="[0-9]{3}-[0-9]{9}" required>
                                                        </div>
                                                        <br>
                                                        <div class="form-group">
                                                            <label>Business name * </label>
                                                            <input type="text" name="business_name" minlength="5" value="<?php echo $business_name ?>" class="form-control" required />
                                                        </div>
                                                        <br>
                                                        <div class="form-group">
                                                            <label>Your business location</label>
                                                            <input type="text" name="user_location" minlength="5" value="<?php echo $user_location ?>" class="form-control" required />
                                                        </div>
                                                        <br>
                                                        <div class="form-group">
                                                            <label>Your business description</label>
                                                            <textarea type="text" name="user_description" minlength="20" rows="15" class="form-control" required><?php echo $user_description ?></textarea>
                                                        </div>
                                                        <br>
                                                        <div class="form-group col-md-12">
                                                            <small>(By checking this box I agree to terms and condtions)</small>
                                                            <div class="form-check">
                                                                <input class="form-check-input" name="agreement" type="checkbox" value="agreed" required />
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="change-password-btn mt-5">
                                                            <button type="submit" name="edit_profile" class="btn btn-primary btn-block">Save data</button>
                                                        </div>
                                                    </form>
                                                    <br>
                                                    <br>
                                                    <br>
                                                    <br>
                                                    <br>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Change Password -->
                                </div>
                            </div>
                        </div>
                        <!-- End Orders -->
                    </div>
                </div>
                <!-- End My Account Details -->
            </div>
        </div>
    </div>
</div>
<!-- End My Account -->
</main>
<!-- End Main Content -->
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>

<?php include 'footer.php'; ?>