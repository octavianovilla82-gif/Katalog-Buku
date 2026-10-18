<?php
require 'config.php';
$lang = $_SESSION['lang'];
$_SESSION = [];
session_destroy();
session_start();
$_SESSION['lang'] = $lang; // bahasa tetap dipertahankan
header('Location: login.php');
exit;
