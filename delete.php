<?php
require 'config.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
    if ($id !== false) {
        $stmt = mysqli_prepare($db, 'DELETE FROM books WHERE id = ?');
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
    }
}
header('Location: index.php?msg=deleted');
exit;
