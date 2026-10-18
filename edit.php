<?php
require 'config.php';
require_login();

$id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);
if ($id === false) { header('Location: index.php'); exit; }

$stmt = mysqli_prepare($db, 'SELECT * FROM books WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$data) { header('Location: index.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    [$data, $error] = validate_book($_POST);
    if ($error === '') {
        $stmt = mysqli_prepare($db, 'UPDATE books SET judul=?, penulis=?, penerbit=?, tahun=?, kategori=? WHERE id=?');
        mysqli_stmt_bind_param($stmt, 'sssisi', $data['judul'], $data['penulis'], $data['penerbit'], $data['tahun'], $data['kategori'], $id);
        mysqli_stmt_execute($stmt);
        header('Location: index.php?msg=saved');
        exit;
    }
    $data['tahun'] = $_POST['tahun'] ?? '';
}
$title = t('edit_title');
require 'form_fields.php';
