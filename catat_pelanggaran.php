<?php
require_once 'koneksi.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$role  = $_SESSION['role'] ?? '';
$is_wk = $_SESSION['is_wali_kelas'] ?? false;

// ========================= SIMPAN DATA =========================
if (isset($_POST['simpan'])) {
    $siswa_id       = (int)($_POST['siswa_id'] ?? 0);
    $kelas_id       = (int)($_POST['kelas_id'] ?? 0);
    $pelanggaran_id = (int)($_POST['pelanggaran_id'] ?? 0);
    $guru_id        = (int)($_POST['guru_id'] ?? 0);
    $tanggal        = $_POST['tanggal'] ?? date('Y-m-d');
    $keterangan     = trim($_POST['keterangan'] ?? '');
    $tindakan       = trim($_POST['tindakan'] ?? '');
    $status         = $_POST['status'] ?? 'Pending';

    // Ambil data master secara ringkas
    $s = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nama FROM t_siswa WHERE id = $siswa_id"));
    $k = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nama FROM t_kelas WHERE id = $kelas_id"));
    $p = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nama, poin, pelanggaran_kategori_id FROM t_pelanggaran WHERE id = $pelanggaran_id"));
    $g = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nama FROM t_guru WHERE id = $guru_id"));

    if ($s && $k && $p && $g) {
        $tahun_ajaran_id = 46;
        $stmt = mysqli_prepare($koneksi, "INSERT INTO t_pelanggaran_siswa (tahun_ajaran_id, siswa_id, nama_siswa, kelas_id, nama_kelas, pelanggaran_id, nama_pelanggaran, pelanggaran_kategori_id, guru_id, nama_guru, tanggal, keterangan, poin, tindakan, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        mysqli_stmt_bind_param($stmt, "iisisisiisssiss", $tahun_ajaran_id, $siswa_id, $s['nama'], $kelas_id, $k['nama'], $pelanggaran_id, $p['nama'], $p['pelanggaran_kategori_id'], $guru_id, $g['nama'], $tanggal, $keterangan, $p['poin'], $tindakan, $status);
        
        $redirect = mysqli_stmt_execute($stmt) ? "sukses" : "gagal";
        mysqli_stmt_close($stmt);
        header("Location: catat_pelanggaran.php?msg=$redirect");
        exit();
    }
    header("Location: catat_pelanggaran.php?msg=gagal");
    exit();
}

// ========================= HAPUS DATA =========================
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM t_pelanggaran_siswa WHERE id = $id");
    header("Location: catat_pelanggaran.php?msg=hapus");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catat Pelanggaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .sidebar { width: 250px; height: 100vh; position: fixed; top: 0; left: 0; background: #212529; color: #fff; padding-top: 15px; overflow-y: auto; }
        .sidebar a { display: block; padding: 10px 20px; color: #adb5bd; text-decoration: none; }
        .sidebar a:hover, .sidebar a.active { background: #0d6efd; color: #fff; }
        .main { margin-left: 250px; padding: 30px; }
        @media (max-width: 768px) { .sidebar { width: 200px; } .main { margin-left: 200px; padding: 15px; } }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar d-flex flex-column p-3">
    <h4 class="text-white mb-4">Pelanggaran Siswa</h4>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item mb-1"><a href="dashboard.php">Dashboard</a></li>
        <li class="nav-item mb-1"><a href="catat_pelanggaran.php" class="active">Catat Pelanggaran</a></li>
        <li class="nav-item mb-1"><a href="tindakan.php">Tindakan</a></li>
        <li class="nav-item mb-1"><a href="riwayat.php">Riwayat</a></li>
        <li class="nav-item mb-1"><a href="rekap_poin.php">Rekap Poin</a></li>
        
        <?php if ($role === 'admin' || $is_wk === true): ?>
            <li class="nav-item mb-1"><a href="laporan.php">Laporan</a></li>
        <?php endif; ?>

        <li class="nav-item mb-1"><a href="about_me.php">About Me</a></li>
    </ul>
    <hr><a href="logout.php" class="text-danger">Logout</a>
</div>

<!-- KONTEN -->
<div class="main">
    <h3 class="fw-bold mb-3">Catat Pelanggaran</h3>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-<?= $_GET['msg'] === 'gagal' ? 'danger' : 'success'; ?> alert-dismissible fade show">
            <?= $_GET['msg'] === 'sukses' ? 'Data berhasil disimpan.' : ($_GET['msg'] === 'hapus' ? 'Data berhasil dihapus.' : 'Gagal memproses data.'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- FORM -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-primary text-white">Form Tambah Catatan Pelanggaran</div>
        <div class="card-body">
            <form method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Siswa</label>
                        <select name="siswa_id" class="form-select" required>
                            <option value="">-- Pilih Siswa --</option>
                            <?php $q = mysqli_query($koneksi, "SELECT id, nis, nama FROM t_siswa ORDER BY nama ASC");
                            while ($r = mysqli_fetch_assoc($q)): ?>
                                <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['nis'] . ' - ' . $r['nama']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kelas</label>
                        <select name="kelas_id" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php $q = mysqli_query($koneksi, "SELECT id, nama FROM t_kelas ORDER BY nama ASC");
                            while ($r = mysqli_fetch_assoc($q)): ?>
                                <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['nama']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pelanggaran</label>
                        <select name="pelanggaran_id" class="form-select" required>
                            <option value="">-- Pilih Pelanggaran --</option>
                            <?php $q = mysqli_query($koneksi, "SELECT id, kode, nama, poin FROM t_pelanggaran ORDER BY kode ASC");
                            while ($r = mysqli_fetch_assoc($q)): ?>
                                <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['kode'] . ' - ' . $r['nama'] . ' (' . $r['poin'] . ' Poin)') ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Guru Pelapor</label>
                        <select name="guru_id" class="form-select" required>
                            <option value="">-- Pilih Guru --</option>
                            <?php $q = mysqli_query($koneksi, "SELECT id, nama FROM t_guru ORDER BY nama ASC");
                            while ($r = mysqli_fetch_assoc($q)): ?>
                                <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['nama']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="Pending">Pending</option>
                            <option value="Proses">Proses</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="2" placeholder="Keterangan kejadian..."></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tindakan / Sanksi</label>
                    <input type="text" name="tindakan" class="form-control" placeholder="Tindakan atau sanksi...">
                </div>
                <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                <button type="reset" class="btn btn-secondary">Reset</button>
            </form>
        </div>
    </div>

    <!-- TABEL -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-dark text-white">Data Pelanggaran Siswa</div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabelPelanggaran" class="table table-bordered table-striped table-hover align-middle w-100">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th><th>Tanggal</th><th>Siswa</th><th>Kelas</th><th>Pelanggaran</th><th>Poin</th><th>Guru</th><th>Status</th><th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        $data = mysqli_query($koneksi, "SELECT ps.*, s.nama AS nama_siswa_asli, k.nama AS nama_kelas_asli, p.nama AS nama_pelanggaran_asli, g.nama AS nama_guru_asli FROM t_pelanggaran_siswa AS ps LEFT JOIN t_siswa AS s ON ps.siswa_id = s.id LEFT JOIN t_kelas AS k ON ps.kelas_id = k.id LEFT JOIN t_pelanggaran AS p ON ps.pelanggaran_id = p.id LEFT JOIN t_guru AS g ON ps.guru_id = g.id ORDER BY ps.tanggal DESC, ps.id DESC");
                        while ($row = mysqli_fetch_assoc($data)): 
                            $warna = $row['status'] === 'Pending' ? 'warning text-dark' : ($row['status'] === 'Proses' ? 'primary' : 'success');
                        ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= htmlspecialchars($row['tanggal']); ?></td>
                                <td><?= htmlspecialchars($row['nama_siswa_asli'] ?? '-'); ?></td>
                                <td><?= htmlspecialchars($row['nama_kelas_asli'] ?? '-'); ?></td>
                                <td><?= htmlspecialchars($row['nama_pelanggaran_asli'] ?? '-'); ?></td>
                                <td><span class="badge bg-danger"><?= (int)$row['poin']; ?></span></td>
                                <td><?= htmlspecialchars($row['nama_guru_asli'] ?? '-'); ?></td>
                                <td><span class="badge bg-<?= $warna; ?>"><?= htmlspecialchars($row['status']); ?></span></td>
                                <td><a href="catat_pelanggaran.php?hapus=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data ini?')">Hapus</a></td>
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
    $('#tabelPelanggaran').DataTable({
        language: { search: 'Cari:', zeroRecords: 'Data tidak ditemukan' },
        pageLength: 10,
        order: [[1, 'desc']],
        columnDefs: [{ targets: [0, 8], orderable: false }]
    });
});
</script>
</body>
</html>