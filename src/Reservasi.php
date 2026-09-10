<?php

namespace App;

use PDO;

class Reservasi {
    public function __construct(private PDO $db) {}

    public function semua(): array {
        $sql = "SELECT r.*, l.nama_lapangan 
                FROM reservasi r 
                JOIN lapangan l ON r.lapangan_id = l.id 
                ORDER BY r.id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function isLapanganTersedia(int $lapanganId): bool {
        $stmt = $this->db->prepare("SELECT status FROM lapangan WHERE id = ?");
        $stmt->execute([$lapanganId]);
        $lapangan = $stmt->fetch();

        return $lapangan && $lapangan['status'] === 'Tersedia';
    }

    public function tambah(int $lapanganId, string $nama, string $tanggal, string $jamMulai, string $jamSelesai): bool {
        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare("INSERT INTO reservasi (lapangan_id, nama_pemesan, tanggal, jam_mulai, jam_selesai, status) VALUES (?, ?, ?, ?, ?, 'Dipakai')");
            $stmt->execute([$lapanganId, $nama, $tanggal, $jamMulai, $jamSelesai]);

            $stmtUpdate = $this->db->prepare("UPDATE lapangan SET status = 'Dipakai' WHERE id = ?");
            $stmtUpdate->execute([$lapanganId]);

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function Selesai(int $reservasiId, int $lapanganId): bool {
        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare("UPDATE reservasi SET status = 'Selesai' WHERE id = ?");
            $stmt->execute([$reservasiId]);

            $stmtUpdate = $this->db->prepare("UPDATE lapangan SET status = 'Tersedia' WHERE id = ?");
            $stmtUpdate->execute([$lapanganId]);

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function hapus(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM reservasi WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Method untuk mengambil 1 data reservasi berdasarkan ID
    public function cari(int $id): array|false {
        $stmt = $this->db->prepare("SELECT * FROM reservasi WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Method untuk memperbarui data reservasi
    public function ubah(int $id, int $lapanganId, string $nama, string $tanggal, string $jamMulai, string $jamSelesai): bool {
        $stmt = $this->db->prepare("UPDATE reservasi SET lapangan_id = ?, nama_pemesan = ?, tanggal = ?, jam_mulai = ?, jam_selesai = ? WHERE id = ?");
        return $stmt->execute([$lapanganId, $nama, $tanggal, $jamMulai, $jamSelesai, $id]);
    }
}