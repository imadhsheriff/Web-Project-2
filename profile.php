<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_tourist();
$page_title = 'My profile';
$userId = (int)$_SESSION['tourist_id'];
$stmt = $pdo->prepare('SELECT tourist_id, first_name, last_name, email, mobile_number, address FROM tourists WHERE tourist_id = ? LIMIT 1');
$stmt->execute([$userId]);
$user = $stmt->fetch();
if (!$user) {
    unset($_SESSION['tourist_id'], $_SESSION['tourist_name']);
    flash('Tourist profile was not found. Please log in again.', 'error');
    header('Location: login.php');
    exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first = trim($_POST['first_name'] ?? '');
    $last = trim($_POST['last_name'] ?? '');
    $phone = trim($_POST['mobile_number'] ?? '');
    $address = trim($_POST['address'] ?? '');
    if ($first === '') {
        $error = 'First name is required.';
    } else {
        $update = $pdo->prepare('UPDATE tourists SET first_name = ?, last_name = ?, mobile_number = ?, address = ? WHERE tourist_id = ?');
        $update->execute([$first, $last, $phone, $address, $userId]);
        $_SESSION['tourist_name'] = trim($first . ' ' . $last);
        flash('Profile updated successfully.');
        header('Location: profile.php');
        exit;
    }
    $user['first_name'] = $first;
    $user['last_name'] = $last;
    $user['mobile_number'] = $phone;
    $user['address'] = $address;
}
require 'includes/header.php';
?>
<section class="section section-soft account-page-section"><div class="container account-layout"><div class="account-sidebar-card"><?php require 'includes/account_sidebar.php'; ?></div><div class="account-content-card"><form class="form-shell account-inner-form" method="post"><span class="eyebrow">Your account</span><h2>Profile settings</h2><?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?><div class="form-grid"><div class="field"><label for="first_name">First name</label><input id="first_name" name="first_name" required maxlength="80" value="<?= e($user['first_name']) ?>"></div><div class="field"><label for="last_name">Last name</label><input id="last_name" name="last_name" maxlength="100" value="<?= e($user['last_name']) ?>"></div><div class="field"><label for="email">Email address</label><input id="email" type="email" value="<?= e($user['email']) ?>" disabled></div><div class="field"><label for="mobile_number">Mobile number</label><input id="mobile_number" name="mobile_number" maxlength="30" value="<?= e($user['mobile_number']) ?>"></div><div class="field full"><label for="address">Address</label><textarea id="address" name="address" maxlength="255"><?= e($user['address']) ?></textarea></div></div><div class="form-actions"><button class="btn" type="submit">Save changes</button></div></form></div></div></section>
<?php require 'includes/footer.php'; ?>
