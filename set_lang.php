<?php
require 'config.php';
$l = $_GET['l'] ?? 'id';
$_SESSION['lang'] = in_array($l, ['id', 'en'], true) ? $l : 'id';
$back = $_SERVER['HTTP_REFERER'] ?? 'index.php';
header('Location: ' . $back);
exit;
