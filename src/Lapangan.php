<?php

namespace App;

use PDO;

class Lapangan {
    public function __construct(private PDO $db) {}

    public function semua(): array {
        $stmt = $this->db->query("SELECT * FROM lapangan ORDER BY id ASC");
        return $stmt->fetchAll();
    }

    public function tambah(string $nama, string $jenis, int $harga, string $status): bool {
        $stmt = $this->db->prepare("INSERT INTO lapangan (nama_lapangan, jenis, harga_per_jam, status) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$nama, $jenis, $harga, $status]);
    }

    public function cari(int $id): array|false {
        $stmt = $this->db->prepare("SELECT * FROM lapangan WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function ubah(int $id, string $nama, string $jenis, int $harga, string $status): bool {
        $stmt = $this->db->prepare("UPDATE lapangan SET nama_lapangan = ?, jenis = ?, harga_per_jam = ?, status = ? WHERE id = ?");
        return $stmt->execute([$nama, $jenis, $harga, $status, $id]);
    }

    public function hapus(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM lapangan WHERE id = ?");
        return $stmt->execute([$id]);
    }
}