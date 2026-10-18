<?php
require 'config.php';
require_login();

$data  = ['judul' => '', 'penulis' => '', 'penerbit' => '', 'tahun' => '', 'kategori' => ''];
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    [$data, $error] = validate_book($_POST);
    if ($error === '') {
        $stmt = mysqli_prepare($db, 'INSERT INTO books (judul, penulis, penerbit, tahun, kategori) VALUES (?,?,?,?,?)');
        mysqli_stmt_bind_param($stmt, 'sssis', $data['judul'], $data['penulis'], $data['penerbit'], $data['tahun'], $data['kategori']);
        mysqli_stmt_execute($stmt);
        header('Location: index.php?msg=saved');
        exit;
    }
    $data['tahun'] = $_POST['tahun'] ?? '';
}
$title = t('add_title');
require 'form_fields.php';
