<?php
require_once 'koneksi.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$role  = $_SESSION['role'] ?? '';
$is_wk = $_SESSION['is_wali_kelas'] ?? false;

function e($val) { return htmlspecialchars((string)($val ?? ''), ENT_QUOTES, 'UTF-8'); }

$query = mysqli_query($koneksi, "SELECT * FROM t_pelanggaran_siswa ORDER BY tanggal DESC, id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Riwayat Pelanggaran Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: #212529; color: #fff; position: fixed; width: 250px; top: 0; left: 0; overflow-y: auto; }
        .sidebar a { color: #adb5bd; text-decoration: none; display: block; padding: 10px 20px; border-radius: 5px; }
        .sidebar a:hover, .sidebar a.active { color: #fff; background-color: #0d6efd; }
        .main { margin-left: 250px; padding: 30px; }
        @media (max-width: 767.98px) { .sidebar { position: relative; width: 100%; min-height: auto; } .main { margin-left: 0; padding: 15px; } }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar d-flex flex-column p-3">
    <h4 class="text-white mb-4">Pelanggaran Siswa</h4>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item mb-1"><a href="dashboard.php">Dashboard</a></li>
        <li class="nav-item mb-1"><a href="catat_pelanggaran.php">Catat Pelanggaran</a></li>
        <li class="nav-item mb-1"><a href="tindakan.php">Tindakan</a></li>
        <li class="nav-item mb-1"><a href="riwayat.php" class="active">Riwayat</a></li>
        <li class="nav-item mb-1"><a href="rekap_poin.php">Rekap Poin</a></li>
        
        <!-- Menu Laporan: Hanya tampil jika Admin atau Wali Kelas -->
        <?php if ($role === 'admin' || $is_wk === true): ?>
            <li class="nav-item mb-1">
                <a href="laporan.php" class="<?= basename($_SERVER['PHP_SELF']) == 'laporan.php' ? 'active' : ''; ?>">Laporan</a>
            </li>
        <?php endif; ?>

        <li class="nav-item mb-1"><a href="about_me.php">About Me</a></li>
    </ul>
    <hr><a href="logout.php" class="text-danger" onclick="return confirm('Yakin ingin keluar?')">Logout</a>
</div>

<!-- KONTEN UTAMA -->
<div class="main">
    <h3 class="fw-bold mb-3">Riwayat Pelanggaran Siswa</h3>
   <hr>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-dark text-white">Daftar Riwayat Pelanggaran</div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabelRiwayat" class="table table-bordered table-striped align-middle w-100">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th><th>Tanggal</th><th>Siswa</th><th>Kelas</th>
                            <th>Pelanggaran</th><th>Poin</th><th>Guru Pelapor</th><th>Tindakan</th><th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $no = 1; while ($row = mysqli_fetch_assoc($query)): 
                        $status = $row['status'] ?? 'Pending';
                        $bg = match($status) { 'Selesai' => 'success', 'Proses' => 'primary', default => 'warning text-dark' };
                    ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= e($row['tanggal']); ?></td>
                            <td><?= e($row['nama_siswa']); ?></td>
                            <td><?= e($row['nama_kelas']); ?></td>
                            <td><?= e($row['nama_pelanggaran']); ?></td>
                            <td><span class="badge bg-danger"><?= e($row['poin']); ?></span></td>
                            <td><?= e($row['nama_guru']); ?></td>
                            <td><?= !empty($row['tindakan']) ? e($row['tindakan']) : '<span class="text-muted fst-italic">Belum ada</span>'; ?></td>
                            <td><span class="badge bg-<?= $bg; ?>"><?= e($status); ?></span></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function () {
    $('#tabelRiwayat').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        order: [[1, 'desc']],
        columnDefs: [{ targets: [0], orderable: false, searchable: false }],
        language: {
            search: "Cari data:", lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data", infoFiltered: "(difilter dari _MAX_ data)",
            zeroRecords: "Data tidak ditemukan", emptyTable: "Belum ada riwayat pelanggaran",
            paginate: { first: "Pertama", last: "Terakhir", next: "Berikutnya", previous: "Sebelumnya" }
        }
    });
});
</script>
</body>
</html>