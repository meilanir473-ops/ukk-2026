<?php
require_once 'koneksi.php';

$pdo = new PDO("mysql:host=localhost;dbname=db_ukk_2026;charset=utf8mb4", "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

$tahun_id = $_GET['tahun_id'] ?? '';
$kelas_id = $_GET['kelas_id'] ?? '';
$action   = $_GET['action'] ?? '';

$where = []; $params = [];
if ($tahun_id !== '') { $where[] = "ks.tahun_ajaran_id = :th"; $params[':th'] = $tahun_id; }
if ($kelas_id !== '') { $where[] = "ks.kelas_id = :kl"; $params[':kl'] = $kelas_id; }
$sqlWhere = $where ? "WHERE " . implode(" AND ", $where) : "";

$query = "SELECT ks.*, s.nis, s.nama AS nama_siswa, s.jenis_kelamin, k.nama AS nama_kelas, ta.nama AS nama_tahun
          FROM t_kelas_siswa ks
          JOIN t_siswa s ON ks.siswa_id = s.id
          JOIN t_kelas k ON ks.kelas_id = k.id
          JOIN t_tahun_ajaran ta ON ks.tahun_ajaran_id = ta.id
          $sqlWhere ORDER BY ks.id DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$data = $stmt->fetchAll();

if ($action === 'excel') {
    header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
    header("Content-Disposition: attachment; filename=Penempatan_Siswa_" . date('Ymd') . ".xls");
    echo "\xEF\xBB\xBF<h3>Laporan Penempatan Siswa</h3><table border='1'><tr><th>No</th><th>Tahun Ajaran</th><th>NIS</th><th>Nama Siswa</th><th>L/P</th><th>Kelas</th><th>Status</th></tr>";
    foreach ($data as $i => $r) {
        echo "<tr><td>".($i+1)."</td><td>{$r['nama_tahun']}</td><td>'{$r['nis']}</td><td>{$r['nama_siswa']}</td><td>{$r['jenis_kelamin']}</td><td>{$r['nama_kelas']}</td><td>".(($r['status_aktif'] ?? 0) == 1 ? 'Aktif' : 'Non-Aktif')."</td></tr>";
    }
    echo "</table>";
    exit;
}

$listTahun = $pdo->query("SELECT id, nama FROM t_tahun_ajaran ORDER BY nama DESC")->fetchAll();
$listKelas = $pdo->query("SELECT id, nama FROM t_kelas ORDER BY nama ASC")->fetchAll();

function e($v) { return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8'); }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Penempatan Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; overflow-x: hidden; }
        .sidebar { min-height: 100vh; background-color: #212529; color: #fff; position: fixed; width: 250px; top: 0; left: 0; overflow-y: auto; z-index: 1000; }
        .sidebar a { color: #adb5bd; text-decoration: none; display: block; padding: 10px 20px; border-radius: 5px; }
        .sidebar a:hover, .sidebar a.active { color: #fff; background-color: #0d6efd; }
        .main-content { margin-left: 250px; padding: 20px; }
        @media print {
            .sidebar, .no-print, .dataTables_wrapper .dt-layout-row:not(:has(table)) { display: none !important; }
            .main-content { margin-left: 0; padding: 0; }
            body { background-color: #fff; }
        }
    </style>
</head>
<body <?= $action === 'print' ? 'onload="window.print()"' : '' ?>>

<!-- SIDEBAR -->
<div class="sidebar d-flex flex-column p-3">
    <h4 class="text-white mb-4">Pelanggaran Siswa</h4>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item mb-1"><a href="dashboard.php">Dashboard</a></li>
        <li class="nav-item mb-1"><a href="kelola_guru.php">Kelola Guru</a></li>
        <li class="nav-item mb-1"><a href="kelola_siswa.php">Kelola Siswa</a></li>
        <li class="nav-item mb-1"><a href="kelola_kelas.php">Kelola Kelas</a></li>
        <li class="nav-item mb-1"><a href="kelola_tahun_ajaran.php">Kelola Tahun Ajaran</a></li>
        <li class="nav-item mb-1"><a href="penempatan_siswa.php" class="active">Penempatan Siswa</a></li>
        <li class="nav-item mb-1"><a href="kelola_wali_kelas.php">Kelola Wali Kelas</a></li>
        <li class="nav-item mb-1"><a href="kelola_kategori_pelanggaran.php">Kelola Kategori Pelanggaran</a></li>
        <li class="nav-item mb-1"><a href="laporan.php">Laporan</a></li>
        <li class="nav-item mb-1"><a href="kelola_jenis_pelanggaran.php">Kelola Jenis Pelanggaran</a></li>
        <li class="nav-item mb-1"><a href="about_me.php">About Me</a></li>
    </ul>
    <hr>
    <a href="logout.php" class="text-danger">Logout</a>
</div>

<!-- KONTEN UTAMA -->
<div class="main-content">
    <h2 class="mb-3">Penempatan Siswa</h2>
    <div class="mb-3 no-print">
        <a href="dashboard.php" class="btn btn-secondary btn-sm">Kembali ke Dashboard</a>
        <hr>
    </div>

    <!-- FILTER & AKSI -->
    <div class="card shadow-sm mb-4 no-print">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Tahun Ajaran:</label>
                    <select name="tahun_id" class="form-select">
                        <option value="">-- Semua Tahun Ajaran --</option>
                        <?php foreach ($listTahun as $t): ?>
                            <option value="<?= e($t['id']); ?>" <?= $tahun_id == $t['id'] ? 'selected' : ''; ?>><?= e($t['nama']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kelas:</label>
                    <select name="kelas_id" class="form-select">
                        <option value="">-- Semua Kelas --</option>
                        <?php foreach ($listKelas as $k): ?>
                            <option value="<?= e($k['id']); ?>" <?= $kelas_id == $k['id'] ? 'selected' : ''; ?>><?= e($k['nama']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="penempatan_siswa.php" class="btn btn-secondary">Reset</a>
                </div>
            </form>
            <hr>

    <!-- TABEL DATA -->
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">Data Penempatan Siswa</div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabelPenempatan" class="table table-bordered table-striped table-hover align-middle w-100">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center">No</th>
                            <th>Tahun Ajaran</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th class="text-center">L/P</th>
                            <th>Kelas</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $i => $r): ?>
                            <tr>
                                <td class="text-center"><?= $i + 1; ?></td>
                                <td><?= e($r['nama_tahun']); ?></td>
                                <td><?= e($r['nis']); ?></td>
                                <td><?= e($r['nama_siswa']); ?></td>
                                <td class="text-center"><?= e($r['jenis_kelamin']); ?></td>
                                <td><?= e($r['nama_kelas']); ?></td>
                                <td class="text-center">
                                    <?php if (($r['status_aktif'] ?? 0) == 1): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Non-Aktif</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function () {
    $('#tabelPenempatan').DataTable({
        language: {
            search: "Cari data:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            zeroRecords: "Data tidak ditemukan",
            paginate: { first: "Pertama", last: "Terakhir", next: "Berikutnya", previous: "Sebelumnya" }
        },
        pageLength: 10,
        order: [[0, 'asc']]
    });
});
</script>
</body>
</html>