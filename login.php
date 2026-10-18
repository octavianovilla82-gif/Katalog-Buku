<?php
require 'config.php';
if (!empty($_SESSION['user'])) { header('Location: index.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = mysqli_prepare($db, 'SELECT password FROM users WHERE username = ?');
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    if ($row && password_verify($password, $row['password'])) {
        session_regenerate_id(true);
        $_SESSION['user'] = $username;
        header('Location: index.php');
        exit;
    }
    $error = t('login_failed');
}
page_header(); ?>
<h3><?= e(t('login')) ?></h3>
<?php if ($error): ?><p class="err"><?= e($error) ?></p><?php endif; ?>
<form method="post" style="max-width:320px">
  <label><?= e(t('username')) ?></label>
  <input type="text" name="username" required>
  <label><?= e(t('password')) ?></label>
  <input type="password" name="password" required>
  <button class="btn" type="submit"><?= e(t('login')) ?></button>
</form>
</body></html>
