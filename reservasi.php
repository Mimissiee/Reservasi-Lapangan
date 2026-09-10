<?php

use App\Database;
use App\Reservasi;

require_once "vendor/autoload.php";

$database = new Database("localhost", "reservasi_lapangan", "root", "11223344");
$db = $database->connect();

$reservasiModel = new Reservasi($db);
$semuaReservasi = $reservasiModel->semua();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Reservasi - Sistem Perpus Manual</title>
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
            --success: #16a34a;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: system-ui, -apple-system, sans-serif; background-color: var(--bg-color); color: var(--text-main); padding: 32px 16px; }
        .container { max-width: 1000px; margin: 0 auto; }
        
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .header h1 { font-size: 22px; font-weight: 700; }
        
        .btn-group { display: flex; gap: 8px; }
        .btn { padding: 8px 16px; border-radius: 6px; font-size: 14px; font-weight: 500; text-decoration: none; cursor: pointer; border: 1px solid transparent; transition: background 0.2s; display: inline-block; }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-hover); }
        .btn-outline { background: #fff; border-color: var(--border); color: var(--text-main); }
        .btn-outline:hover { background: #f1f5f9; }
        .btn-sm { padding: 4px 8px; font-size: 12px; margin-left: 2px; }
        .btn-success { background: #dcfce7; color: #166534; }
        .btn-success:hover { background: #bbf7d0; }
        .btn-danger { color: var(--danger); background: #fef2f2; }
        .btn-danger:hover { background: #fee2e2; }

        .card { background: var(--card-bg); border: 1px solid var(--border); border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; text-align: left; font-size: 14px; }
        th { background: #f1f5f9; padding: 12px 16px; font-weight: 600; color: var(--text-muted); border-bottom: 1px solid var(--border); }
        td { padding: 14px 16px; border-bottom: 1px solid var(--border); vertical-align: middle; }

        .badge { display: inline-block; padding: 2px 8px; border-radius: 12px; font-size: 12px; font-weight: 500; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-dikonfirmasi, .badge-dipakai { background: #dcfce7; color: #166534; }
        .badge-selesai { background: #e0f2fe; color: #075985; }
        .badge-dibatalkan { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <div>
            <h1>Daftar Transaksi Lapangan</h1>
            <p style="color: var(--text-muted); font-size: 14px;">Logika Peminjaman & Pengembalian Lapangan</p>
        </div>
        <div class="btn-group">
            <a href="tambah_reservasi.php" class="btn btn-primary">+ Pinjam / Sewa Lapangan</a>
            <a href="index.php" class="btn btn-outline">Kelola Lapangan</a>
        </div>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pemesan</th>
                    <th>Lapangan</th>
                    <th>Tanggal</th>
                    <th>Waktu Jam</th>
                    <th>Status Pemakaian</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($semuaReservasi)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 24px;">Belum ada riwayat transaksi.</td>
                </tr>
                <?php else: ?>
                    <?php $no = 1; ?>
                    <?php foreach ($semuaReservasi as $item): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><strong><?= htmlspecialchars($item['nama_pemesan']) ?></strong></td>
                        <td><?= htmlspecialchars($item['nama_lapangan']) ?></td>
                        <td><?= date('d-m-Y', strtotime($item['tanggal'])) ?></td>
                        <td><?= substr($item['jam_mulai'], 0, 5) ?> - <?= substr($item['jam_selesai'], 0, 5) ?></td>
                        <td>
                            <?php $statusClass = strtolower($item['status']); ?>
                            <span class="badge badge-<?= $statusClass ?>">
                                <?= htmlspecialchars($item['status']) ?>
                            </span>
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <!-- Tombol Edit -->
                            <a href="edit_reservasi.php?id=<?= $item['id'] ?>" class="btn btn-outline btn-sm">Edit</a>

                            <!-- Tombol Selesai (Muncul selama status bukan Selesai atau Dibatalkan) -->
                            <?php if ($item['status'] !== 'Selesai' && $item['status'] !== 'Dibatalkan'): ?>
                                <a href="selesai_reservasi.php?id=<?= $item['id'] ?>&lapangan_id=<?= $item['lapangan_id'] ?>" 
                                   class="btn btn-success btn-sm" 
                                   onclick="return confirm('Tandai pemakaian selesai? Lapangan akan menjadi Tersedia kembali.');">
                                   Selesai
                                </a>
                            <?php endif; ?>

                            <!-- Tombol Hapus -->
                            <a href="hapus_reservasi.php?id=<?= $item['id'] ?>" 
                               class="btn btn-danger btn-sm" 
                               onclick="return confirm('Hapus transaksi ini?');">
                               Hapus
                            </a>
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