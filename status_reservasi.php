<?php

use App\Database;
use App\Reservasi;

require_once "vendor/autoload.php";

$database = new Database("localhost", "reservasi_lapangan", "root", "11223344");
$db = $database->connect();
$reservasiModel = new Reservasi($db);

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: reservasi.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'] ?? 'Pending';
    $reservasiModel->ubahStatus($id, $status);
    header("Location: reservasi.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Status Reservasi</title>
    <style>
        :root {
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --border: #cbd5e1;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: system-ui, -apple-system, sans-serif; background-color: var(--bg-color); color: var(--text-main); padding: 32px 16px; }
        .container { max-width: 400px; margin: 0 auto; }
        .card { background: var(--card-bg); border: 1px solid #e2e8f0; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        
        h1 { font-size: 18px; font-weight: 700; margin-bottom: 16px; }
        .form-group { margin-bottom: 16px; }
        label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; }
        select { width: 100%; padding: 10px 12px; font-size: 14px; border: 1px solid var(--border); border-radius: 6px; outline: none; }
        select:focus { border-color: var(--primary); }

        .btn-group { display: flex; gap: 8px; justify-content: flex-end; margin-top: 20px; }
        .btn { padding: 9px 16px; border-radius: 6px; font-size: 14px; font-weight: 500; text-decoration: none; cursor: pointer; border: 1px solid transparent; }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-hover); }
        .btn-outline { background: #fff; border-color: #e2e8f0; color: var(--text-main); }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <h1>Ubah Status Reservasi #<?= $id ?></h1>
        <form method="POST">
            <div class="form-group">
                <label for="status">Pilih Status Baru</label>
                <select name="status" id="status" required>
                    <option value="Pending">Pending</option>
                    <option value="Dikonfirmasi">Dikonfirmasi</option>
                    <option value="Selesai">Selesai</option>
                    <option value="Dibatalkan">Dibatalkan</option>
                </select>
            </div>

            <div class="btn-group">
                <a href="reservasi.php" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Update Status</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>