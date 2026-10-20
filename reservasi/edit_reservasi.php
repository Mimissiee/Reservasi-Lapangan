<?php

use App\Database;
use App\Lapangan;
use App\Reservasi;

require_once "vendor/autoload.php";

$database = new Database("localhost", "reservasi_lapangan", "root", "11223344");
$db = $database->connect();

$reservasiModel = new Reservasi($db);
$lapanganModel = new Lapangan($db);

$id = (int) ($_GET['id'] ?? 0);
$reservasi = $reservasiModel->cari($id);

if (!$reservasi) {
    header("Location: reservasi.php");
    exit();
}

$semuaLapangan = $lapanganModel->semua();
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $lapanganId = (int) $_POST['lapangan_id'];
    $namaPemesan = trim($_POST['nama_pemesan']);
    $tanggal = $_POST['tanggal'];
    $jamMulai = $_POST['jam_mulai'];
    $jamSelesai = $_POST['jam_selesai'];
    $status = $_POST['status'];

    if (empty($namaPemesan)) {
        $error = "Nama pemesan tidak boleh kosong.";
    } else {
        $reservasiModel->ubah($id, $lapanganId, $namaPemesan, $tanggal, $jamMulai, $jamSelesai, $status);
        header("Location: reservasi.php");
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Reservasi - Sistem Perpus Manual</title>
    <style>
        :root {
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --border: #e2e8f0;
            --danger-bg: #fef2f2;
            --danger-text: #ef4444;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: system-ui, -apple-system, sans-serif; background-color: var(--bg-color); color: var(--text-main); padding: 32px 16px; }
        .container { max-width: 480px; margin: 0 auto; }
        .card { background: var(--card-bg); border: 1px solid var(--border); border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        
        h1 { font-size: 20px; font-weight: 700; margin-bottom: 20px; }
        .alert { background: var(--danger-bg); color: var(--danger-text); padding: 12px; border-radius: 6px; font-size: 13px; margin-bottom: 16px; }
        
        .form-group { margin-bottom: 16px; }
        label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; color: var(--text-main); }
        input, select { width: 100%; padding: 10px 12px; font-size: 14px; border: 1px solid var(--border); border-radius: 6px; outline: none; background: #fff; }
        input:focus, select:focus { border-color: var(--primary); }

        .btn-group { display: flex; gap: 8px; justify-content: flex-end; margin-top: 24px; }
        .btn { padding: 9px 16px; border-radius: 6px; font-size: 14px; font-weight: 500; text-decoration: none; cursor: pointer; border: 1px solid transparent; }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-hover); }
        .btn-outline { background: #fff; border-color: var(--border); color: var(--text-main); }
        .btn-outline:hover { background: #f1f5f9; }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <h1>Edit Transaksi Reservasi</h1>

        <?php if ($error): ?>
            <div class="alert"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="lapangan_id">Lapangan</label>
                <select name="lapangan_id" id="lapangan_id" required>
                    <?php foreach ($semuaLapangan as $lap): ?>
                        <option value="<?= $lap['id'] ?>" <?= $lap['id'] == $reservasi['lapangan_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($lap['nama_lapangan']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="nama_pemesan">Nama Pemesan</label>
                <input type="text" name="nama_pemesan" id="nama_pemesan" value="<?= htmlspecialchars($reservasi['nama_pemesan']) ?>" required>
            </div>

            <div class="form-group">
                <label for="tanggal">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" value="<?= $reservasi['tanggal'] ?>" required>
            </div>

            <div class="form-group">
                <label for="jam_mulai">Jam Mulai</label>
                <input type="time" name="jam_mulai" id="jam_mulai" value="<?= $reservasi['jam_mulai'] ?>" required>
            </div>

            <div class="form-group">
                <label for="jam_selesai">Jam Selesai</label>
                <input type="time" name="jam_selesai" id="jam_selesai" value="<?= $reservasi['jam_selesai'] ?>" required>
            </div>

            <!-- Pilihan Status Pemakaian -->
            <div class="form-group">
                <label for="status">Status Pemakaian</label>
                <select name="status" id="status" required>
                    <option value="Pending" <?= $reservasi['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="Dikonfirmasi" <?= $reservasi['status'] === 'Dikonfirmasi' ? 'selected' : '' ?>>Dikonfirmasi</option>
                    <option value="Dipakai" <?= $reservasi['status'] === 'Dipakai' ? 'selected' : '' ?>>Dipakai</option>
                    <option value="Selesai" <?= $reservasi['status'] === 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                    <option value="Dibatalkan" <?= $reservasi['status'] === 'Dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
                </select>
            </div>

            <div class="btn-group">
                <a href="reservasi.php" class="btn btn-outline">Batal</a>
                <button type="submit" name="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>