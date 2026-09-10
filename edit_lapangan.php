<?php

use App\Database;
use App\Lapangan;

require_once "vendor/autoload.php";

$database = new Database("localhost", "reservasi_lapangan", "root", "11223344");
$db = $database->connect();
$lapanganModel = new Lapangan($db);

$id = (int) ($_GET['id'] ?? 0);
$data = $lapanganModel->cari($id);

if (!$data) {
    die("Lapangan tidak ditemukan");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lapanganModel->ubah(
        $id,
        $_POST['nama_lapangan'],
        $_POST['jenis'],
        (int) $_POST['harga_per_jam'],
        $_POST['status']
    );

    header("Location: index.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lapangan</title>
    <style>
        :root {
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --border: #cbd5e1;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: system-ui, -apple-system, sans-serif; background-color: var(--bg-color); color: var(--text-main); padding: 32px 16px; }
        .container { max-width: 480px; margin: 0 auto; }
        .card { background: var(--card-bg); border: 1px solid #e2e8f0; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        
        h1 { font-size: 20px; font-weight: 700; margin-bottom: 20px; }
        .form-group { margin-bottom: 16px; }
        label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; color: var(--text-main); }
        input[type="text"], input[type="number"], select { width: 100%; padding: 10px 12px; font-size: 14px; border: 1px solid var(--border); border-radius: 6px; outline: none; }
        input:focus, select:focus { border-color: var(--primary); }

        .btn-group { display: flex; gap: 8px; justify-content: flex-end; margin-top: 24px; }
        .btn { padding: 9px 16px; border-radius: 6px; font-size: 14px; font-weight: 500; text-decoration: none; cursor: pointer; border: 1px solid transparent; }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-hover); }
        .btn-outline { background: #fff; border-color: #e2e8f0; color: var(--text-main); }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <h1>Edit Lapangan</h1>
        <form method="POST">
            <div class="form-group">
                <label for="nama_lapangan">Nama Lapangan</label>
                <input type="text" name="nama_lapangan" id="nama_lapangan" value="<?= htmlspecialchars($data['nama_lapangan']) ?>" required>
            </div>

            <div class="form-group">
                <label for="jenis">Jenis Olahraga</label>
                <input type="text" name="jenis" id="jenis" value="<?= htmlspecialchars($data['jenis']) ?>" required>
            </div>

            <div class="form-group">
                <label for="harga_per_jam">Harga per Jam (Rp)</label>
                <input type="number" name="harga_per_jam" id="harga_per_jam" value="<?= $data['harga_per_jam'] ?>" required>
            </div>

            <div class="form-group">
                <label for="status">Status Lapangan</label>
                <select name="status" id="status">
                    <option value="Tersedia" <?= $data['status'] === 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                    <option value="Maintenance" <?= $data['status'] === 'Maintenance' ? 'selected' : '' ?>>Maintenance</option>
                </select>
            </div>

            <div class="btn-group">
                <a href="index.php" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>