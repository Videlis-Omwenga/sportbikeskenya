<a href="#" class="cart-btn" title="User login" data-toggle="modal" data-target="#mycartdrawer">
    User login
</a>

<!-- Start Cart Drawer -->
<div class="main-wrapper cart-drawer-push">
    <div class="minicart-wrapper">
        <div class="cart-drawer model fade right show cart-drawer-right">
            <div class="minicart-head">
                <a class="close-btn active">
                    <i class="ti-close"></i>
                </a>
            </div>
            <div class="minicart-details" style="text-align:left">
                <h5><b>Login form</b></h5>
                <hr>

                <br>
                <form action="user_entry_details.php" method="POST" class="form-horizontal">
                    <div class="form-group">
                        <label class="control-label">Registered email :</label>
                        <div class="control">
                            <input type="text" name="email" class="form-control" autocomplete="off" required />
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <label class="control-label">Password :</label>
                        <div class="control">
                            <input type="password" name="password" class="form-control" autocomplete="off" />
                        </div>
                    </div>
                    <br>
                    <br>
                    <div class="form-actions" style="text-align: right;">
                        <button type="reset" class="btn btn-small btn-secondary">Clear</button>
                        <button type="submit" name="user_login" class="btn btn-primary btn-small">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>