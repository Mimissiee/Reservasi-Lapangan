<?php

use App\Database;
use App\Lapangan;

require_once "vendor/autoload.php";

$database = new Database("localhost", "reservasi_lapangan", "root", "11223344");
$db = $database->connect();

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    die("Lapangan tidak valid");
}

$lapanganModel = new Lapangan($db);
$lapanganModel->hapus($id);

header("Location: index.php");
exit();

?>