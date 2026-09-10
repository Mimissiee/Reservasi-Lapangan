<?php

use App\Database;
use App\Reservasi;

require_once "vendor/autoload.php";

$database = new Database("localhost", "reservasi_lapangan", "root", "11223344");
$db = $database->connect();

$id = (int) ($_GET['id'] ?? 0);

if ($id > 0) {
    $reservasiModel = new Reservasi($db);
    $reservasiModel->hapus($id);
}

header("Location: reservasi.php");
exit();

?>