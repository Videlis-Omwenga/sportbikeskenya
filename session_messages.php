<?php
if (isset($_SESSION['error'])) {
?>
    <div class="alert alert-danger" role="alert">
        <?= $_SESSION['error']; ?>
    </div>
<?php
    unset($_SESSION['error']);
}
?>

<?php
if (isset($_SESSION['done'])) {
?>
    <div class="alert alert-success" role="alert">
        <?= $_SESSION['done']; ?>
    </div>
<?php
    unset($_SESSION['done']);
}
?>

<?php
if (isset($_SESSION['success'])) {
?>
    <div class="alert alert-success" role="alert">
        <?= $_SESSION['success'];
        include 'login_form.php'; ?>
    </div>
<?php
    unset($_SESSION['success']);
}
?>

<?php
if (isset($_SESSION['successs'])) {
?>
    <div class="alert alert-success" role="alert">
        <?= $_SESSION['successs'];?>
    </div>
<?php
    unset($_SESSION['successs']);
}
?>

<?php
if (isset($_SESSION['login_success'])) {
?>
    <div class="alert alert-success" role="alert">
        <?= $_SESSION['login_success']; ?>
    </div>
<?php
    unset($_SESSION['login_success']);
}
?>

<?php
if (isset($_SESSION['registration_error'])) {
?>
    <div class="alert alert-danger" role="alert">
        <?= $_SESSION['registration_error'];
        include 'login_form.php'; ?>
    </div>
<?php
    unset($_SESSION['registration_error']);
}
?>

<?php
if (isset($_SESSION['error'])) {
?>
    <div class="alert alert-danger" role="alert">
        <?= $_SESSION['error']; ?>
    </div>
<?php
    unset($_SESSION['error']);
}
?>


<?php
if (isset($_SESSION['img_success'])) {
?>
    <div class="alert alert-success" role="alert">
        <?= $_SESSION['img_success']; ?>
    </div>
<?php
    unset($_SESSION['img_success']);
}
?>