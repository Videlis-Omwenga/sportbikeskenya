<a href="#mark_sold" title="User registration" class="open-wishlist-popup">User registration</a>

<div id="mark_sold" class="quickview-popup magnific-popup mfp-hide">
    <div class="quickview-content">
        <h3 class="text-center">Registration form</h3>
        <hr>
        <div class="row">
            <div class="col-md-6">

                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                    <p class="subtitle mb-0" style="background-color: E8F0FE;">
                        Welcome to the new Sport Bikes Kenya e-commerce website, the first bikes
                        e-commerce
                        platform in Kenya! By creating an account, you gain access to a wide range of
                        services and exclusive offers on top-notch sports bikes. Explore our extensive
                        collection, place orders hassle-free, and enjoy a seamless shopping experience
                        tailored to meet all your biking needs.
                        <br>
                        <br>
                        Join us now and ride into the world of
                        adrenaline-pumping adventures!
                    </p>
                    <hr>
                </div>

                <!-- Sponsored ad -->
                <?php include 'sponsored_ad.php'; ?>
            </div>
            <div class="col-md-6">
                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                    <form action="user_entry_details.php" method="POST" class="form-horizontal">
                        <div class="form-group">
                            <label class="control-label">First Name :</label>
                            <div class="control">
                                <input type="text" minlength="3" name="first_name" class="form-control" autocomplete="off" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Second Name :</label>
                            <div class="control">
                                <input type="text" minlength="3" name="second_name" class="form-control" autocomplete="off" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Email Address : <small id="emailHelp" class="form-text text-muted">(We'll never share your
                                    email with anyone else.)</small></label>
                            <div class="control">
                                <input type="email" name="email" class="form-control" autocomplete="off" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Password : <small id="emailHelp" class="form-text text-muted">(Password should be 7 characters long.)</small></label>
                            <div class="control">
                                <input type="password" minlength="7" name="password" class="form-control" autocomplete="off" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Repeat Password :</label>
                            <div class="control">
                                <input type="password" minlength="7" name="repeat_password" class="form-control" autocomplete="off" required />
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="form-actions" style="text-align: right;">
                            <button type="reset" class="btn btn-small btn-secondary">Clear</button>
                            <button type="submit" name="user_registration" class="btn btn-primary btn-small">Register</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End sold -->