<?php
session_start();

$db = mysqli_connect('localhost', 'root', '', 'katalog_buku');
if (!$db) {
    die('Koneksi database gagal: ' . mysqli_connect_error());
}
mysqli_set_charset($db, 'utf8mb4');

// ---- Bahasa (disimpan di $_SESSION) ----
if (empty($_SESSION['lang'])) {
    $_SESSION['lang'] = 'id';
}
$translations = [
    'id' => [
        'title' => 'Katalog Buku', 'login' => 'Masuk', 'logout' => 'Keluar',
        'username' => 'Nama pengguna', 'password' => 'Kata sandi',
        'login_failed' => 'Nama pengguna atau kata sandi salah.',
        'add' => 'Tambah Buku', 'edit' => 'Ubah', 'delete' => 'Hapus', 'save' => 'Simpan',
        'cancel' => 'Batal', 'no' => 'No', 'book_title' => 'Judul', 'author' => 'Penulis',
        'publisher' => 'Penerbit', 'year' => 'Tahun', 'category' => 'Kategori',
        'action' => 'Aksi', 'empty' => 'Belum ada data.', 'confirm_delete' => 'Hapus buku ini?',
        'select_category' => '-- Pilih kategori --', 'fiksi' => 'Fiksi', 'nonfiksi' => 'Nonfiksi',
        'teknologi' => 'Teknologi', 'err_required' => 'Semua kolom wajib diisi.',
        'err_year' => 'Tahun harus berupa angka 1900 - tahun sekarang.',
        'err_category' => 'Kategori tidak valid.', 'saved' => 'Data berhasil disimpan.',
        'deleted' => 'Data berhasil dihapus.', 'welcome' => 'Halo', 'language' => 'Bahasa',
        'add_title' => 'Tambah Buku', 'edit_title' => 'Ubah Buku',
    ],
    'en' => [
        'title' => 'Book Catalog', 'login' => 'Login', 'logout' => 'Logout',
        'username' => 'Username', 'password' => 'Password',
        'login_failed' => 'Wrong username or password.',
        'add' => 'Add Book', 'edit' => 'Edit', 'delete' => 'Delete', 'save' => 'Save',
        'cancel' => 'Cancel', 'no' => 'No', 'book_title' => 'Title', 'author' => 'Author',
        'publisher' => 'Publisher', 'year' => 'Year', 'category' => 'Category',
        'action' => 'Action', 'empty' => 'No data yet.', 'confirm_delete' => 'Delete this book?',
        'select_category' => '-- Select category --', 'fiksi' => 'Fiction', 'nonfiksi' => 'Non-fiction',
        'teknologi' => 'Technology', 'err_required' => 'All fields are required.',
        'err_year' => 'Year must be a number from 1900 to the current year.',
        'err_category' => 'Invalid category.', 'saved' => 'Data saved successfully.',
        'deleted' => 'Data deleted successfully.', 'welcome' => 'Hello', 'language' => 'Language',
        'add_title' => 'Add Book', 'edit_title' => 'Edit Book',
    ],
];
function t($key) {
    global $translations;
    return $translations[$_SESSION['lang']][$key] ?? $key;
}
function e($s) {
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}
function require_login() {
    if (empty($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }
}
function csrf_token() {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}
function csrf_check() {
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(403);
        die('Invalid CSRF token');
    }
}
// Validasi form buku (dipakai create & update). Mengembalikan [data, error]
function validate_book($post) {
    $judul    = trim($post['judul'] ?? '');
    $penulis  = trim($post['penulis'] ?? '');
    $penerbit = trim($post['penerbit'] ?? '');
    $kategori = $post['kategori'] ?? '';
    $tahun    = filter_var($post['tahun'] ?? '', FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1900, 'max_range' => (int)date('Y')],
    ]);
    $data = compact('judul', 'penulis', 'penerbit', 'tahun', 'kategori');

    if ($judul === '' || $penulis === '' || $penerbit === '' || $kategori === '') {
        return [$data, t('err_required')];
    } elseif ($tahun === false) {
        return [$data, t('err_year')];
    }
    switch ($kategori) {
        case 'fiksi':
        case 'nonfiksi':
        case 'teknologi':
            return [$data, ''];
        default:
            return [$data, t('err_category')];
    }
}
function page_header() { ?>
<!DOCTYPE html>
<html lang="<?= e($_SESSION['lang']) ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e(t('title')) ?></title>
<style>
 body{font-family:Arial,sans-serif;max-width:860px;margin:30px auto;padding:0 15px;color:#222}
 table{border-collapse:collapse;width:100%} th,td{border:1px solid #ccc;padding:8px;text-align:left}
 th{background:#f2f2f2} .err{color:#b00020} .ok{color:#0a7d2c}
 input,select{padding:6px;width:100%;box-sizing:border-box;margin:4px 0 10px}
 .btn{display:inline-block;padding:6px 12px;background:#1a56db;color:#fff;border:0;border-radius:4px;text-decoration:none;cursor:pointer;width:auto}
 .btn.red{background:#c62828} .top{display:flex;justify-content:space-between;align-items:center}
 form.inline{display:inline}
</style>
</head>
<body>
<div class="top">
  <h2><?= e(t('title')) ?></h2>
  <div>
    <?= e(t('language')) ?>: <a href="set_lang.php?l=id">ID</a> | <a href="set_lang.php?l=en">EN</a>
    <?php if (!empty($_SESSION['user'])): ?>
      | <?= e(t('welcome')) ?>, <?= e($_SESSION['user']) ?> | <a href="logout.php"><?= e(t('logout')) ?></a>
    <?php endif; ?>
  </div>
</div>
<?php }
