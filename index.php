<?php

use App\Database;
use App\Lapangan;

require_once "vendor/autoload.php";

$database = new Database("localhost", "reservasi_lapangan", "root", "11223344");
$db = $database->connect();

$lapanganModel = new Lapangan($db);
$semuaLapangan = $lapanganModel->semua();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Reservasi Lapangan</title>
    <style>
        :root {
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --border: #e2e8f0;
            --danger: #ef4444;
            --success-bg: #dcfce7;
            --success-text: #166534;
            --warning-bg: #fef3c7;
            --warning-text: #92400e;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: system-ui, -apple-system, sans-serif; background-color: var(--bg-color); color: var(--text-main); padding: 32px 16px; }
        .container { max-width: 960px; margin: 0 auto; }
        
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .header h1 { font-size: 22px; font-weight: 700; }
        
        .btn-group { display: flex; gap: 8px; }
        .btn { padding: 8px 16px; border-radius: 6px; font-size: 14px; font-weight: 500; text-decoration: none; cursor: pointer; border: 1px solid transparent; transition: background 0.2s; }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-hover); }
        .btn-outline { background: #fff; border-color: var(--border); color: var(--text-main); }
        .btn-outline:hover { background: #f1f5f9; }
        .btn-sm { padding: 4px 8px; font-size: 12px; }
        .btn-danger { color: var(--danger); background: #fef2f2; }
        .btn-danger:hover { background: #fee2e2; }

        .card { background: var(--card-bg); border: 1px solid var(--border); border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; text-align: left; font-size: 14px; }
        th { background: #f1f5f9; padding: 12px 16px; font-weight: 600; color: var(--text-muted); border-bottom: 1px solid var(--border); }
        td { padding: 14px 16px; border-bottom: 1px solid var(--border); vertical-align: middle; }
        tr:last-child td { border-bottom: none; }

        .badge { display: inline-block; padding: 2px 8px; border-radius: 12px; font-size: 12px; font-weight: 500; }
        .badge-success { background: var(--success-bg); color: var(--success-text); }
        .badge-warning { background: var(--warning-bg); color: var(--warning-text); }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <div>
            <h1>Daftar Lapangan</h1>
            <p style="color: var(--text-muted); font-size: 14px;">Kelola unit lapangan olahraga dan ketersediaan</p>
        </div>
        <div class="btn-group">
            <a href="tambah_lapangan.php" class="btn btn-primary">+ Tambah Lapangan</a>
            <a href="reservasi.php" class="btn btn-outline">Lihat Reservasi</a>
        </div>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Lapangan</th>
                    <th>Jenis</th>
                    <th>Harga / Jam</th>
                    <th>Status</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($semuaLapangan)): ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 24px;">Belum ada data lapangan.</td>
                </tr>
                <?php else: ?>
                    <?php $no = 1; ?>
                    <?php foreach ($semuaLapangan as $item): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><strong><?= htmlspecialchars($item['nama_lapangan']) ?></strong></td>
                        <td><?= htmlspecialchars($item['jenis']) ?></td>
                        <td>Rp <?= number_format($item['harga_per_jam'], 0, ',', '.') ?></td>
                        <td>
                            <span class="badge <?= $item['status'] === 'Tersedia' ? 'badge-success' : 'badge-warning' ?>">
                                <?= $item['status'] ?>
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <a href="edit_lapangan.php?id=<?= $item['id'] ?>" class="btn btn-outline btn-sm">Edit</a>
                            <a href="hapus_lapangan.php?id=<?= $item['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus lapangan ini?');">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>