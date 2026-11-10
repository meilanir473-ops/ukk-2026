<?php
require_once 'koneksi.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];
$role = $_SESSION['role'] ?? 'user';
$nama = $_SESSION['name'] ?? ($_SESSION['nama_user'] ?? ($_SESSION['nama'] ?? 'Pengguna'));

// DETEKSI KELAS WALI KELAS (Hanya disimpan variabelnya jika nanti dibutuhkan, tapi tidak memfilter tabel)
$kelas_wali_filter = null;
if ($role === 'guru') {
    $q_wk = mysqli_query($koneksi, "SELECT k.nama AS nama_kelas FROM t_wali_kelas wk JOIN t_guru g ON wk.guru_id = g.id JOIN t_kelas k ON wk.kelas_id = k.id WHERE g.user_id = $user_id AND wk.status_aktif = 1 LIMIT 1");
    if ($wk_row = mysqli_fetch_assoc($q_wk)) {
        $kelas_wali_filter = $wk_row['nama_kelas'];
    } elseif (stripos($nama, 'Ari Sandriana') !== false) {
        $kelas_wali_filter = 'XII RPL 1';
    } elseif (stripos($nama, 'Aldi Maulana') !== false || stripos($nama, 'Aldi') !== false) {
        $kelas_wali_filter = 'XII RPL 2';
    }
}

// PROSES SIMPAN
if (isset($_POST['simpan'])) {
    $s_id = (int)$_POST['siswa_id'];
    $p_id = (int)$_POST['pelanggaran_id'];
    $tgl  = $_POST['tanggal'];

    $ta_query = mysqli_query($koneksi, "SELECT id FROM t_tahun_ajaran WHERE status_aktif = 1 LIMIT 1");
    $ta = ($ta_row = mysqli_fetch_assoc($ta_query)) ? $ta_row['id'] : 1;

    $ks_query = mysqli_query($koneksi, "SELECT kelas_id FROM t_kelas_siswa WHERE siswa_id = $s_id AND status_aktif = 1 LIMIT 1");
    $k_id = ($ks_row = mysqli_fetch_assoc($ks_query)) ? $ks_row['kelas_id'] : 1;

    $g_query = mysqli_query($koneksi, "SELECT id, nama FROM t_guru WHERE user_id = $user_id LIMIT 1");
    if ($g_row = mysqli_fetch_assoc($g_query)) {
        $g_id = $g_row['id'];
        $g_nama = $g_row['nama'];
    } else {
        $fallback_guru = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT id, nama FROM t_guru LIMIT 1"));
        $g_id = $fallback_guru['id'] ?? 1;
        $g_nama = $fallback_guru['nama'] ?? 'Administrator';
    }

    $s_row = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nama FROM t_siswa WHERE id = $s_id"));
    $s_nama = $s_row['nama'] ?? '';

    $k_row = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nama FROM t_kelas WHERE id = $k_id"));
    $k_nama = $k_row['nama'] ?? '';

    $p_row = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nama, poin, pelanggaran_kategori_id FROM t_pelanggaran WHERE id = $p_id"));
    $p_nama = $p_row['nama'] ?? '';
    $p_poin = $p_row['poin'] ?? 0;
    $p_kat  = $p_row['pelanggaran_kategori_id'] ?? 1;

    if ($p_poin <= 10) {
        $tindakan = 'Peringatan Lisan';
    } elseif ($p_poin <= 20) {
        $tindakan = 'Panggilan Orang Tua / SP 1';
    } elseif ($p_poin <= 50) {
        $tindakan = 'Skorsing & Pembinaan Khusus';
    } else {
        $tindakan = 'Dikembalikan kepada Orang Tua';
    }

    $keterangan = 'Pencatatan pelanggaran otomatis';
    $status     = 'Pending';

    $stmt = mysqli_prepare($koneksi, "INSERT INTO t_pelanggaran_siswa (tahun_ajaran_id, siswa_id, nama_siswa, kelas_id, nama_kelas, pelanggaran_id, nama_pelanggaran, pelanggaran_kategori_id, guru_id, nama_guru, tanggal, keterangan, poin, tindakan, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    
    mysqli_stmt_bind_param($stmt, "iisisisiisssiss", $ta, $s_id, $s_nama, $k_id, $k_nama, $p_id, $p_nama, $p_kat, $g_id, $g_nama, $tgl, $keterangan, $p_poin, $tindakan, $status);
    mysqli_stmt_execute($stmt);
    header("Location: catat_pelanggaran.php?msg=sukses"); 
    exit();
}

// PROSES HAPUS
if (isset($_GET['hapus'])) {
    mysqli_query($koneksi, "DELETE FROM t_pelanggaran_siswa WHERE id = " . (int)$_GET['hapus']);
    header("Location: catat_pelanggaran.php?msg=hapus"); 
    exit();
}

$aksi = $_GET['aksi'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><title>Catat Pelanggaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Tambahan CSS Select2 untuk fitur pencarian dropdown -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <style>
        body { background: #f8f9fa; }
        .sidebar { width: 250px; height: 100vh; position: fixed; top: 0; left: 0; background: #212529; color: #fff; padding-top: 15px; overflow-y: auto; }
        .sidebar a { display: block; padding: 10px 20px; color: #adb5bd; text-decoration: none; }
        .sidebar a:hover, .sidebar a.active { background: #0d6efd; color: #fff; }
        .main { margin-left: 250px; padding: 30px; }
    </style>
</head>
<body>
<div class="sidebar d-flex flex-column p-3">
    <h4 class="text-white mb-4">Pelanggaran Siswa</h4>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item mb-1"><a href="dashboard.php">Dashboard</a></li>
        <li class="nav-item mb-1"><a href="catat_pelanggaran.php" class="active">Catat Pelanggaran</a></li>
        <li class="nav-item mb-1"><a href="tindakan.php">Tindakan</a></li>
        <li class="nav-item mb-1"><a href="riwayat.php">Riwayat</a></li>
        <li class="nav-item mb-1"><a href="rekap_poin.php">Rekap Poin</a></li>
        <li class="nav-item mb-1"><a href="laporan.php">Laporan</a></li>
        <li class="nav-item mb-1"><a href="about_me.php">About Me</a></li>
    </ul>
    <hr><a href="logout.php" class="text-danger">Logout</a>
</div>

<div class="main">
    <h3 class="fw-bold mb-3">Catat Pelanggaran</h3>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'sukses'): ?>
        <div class="alert alert-success alert-dismissible fade show">
            Pelanggaran siswa berhasil dicatat. <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'hapus'): ?>
        <div class="alert alert-success alert-dismissible fade show">
            Data catatan pelanggaran berhasil dihapus. <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($aksi === 'tambah'): ?>
        <!-- FORM TAMBAH CATATAN PELANGGARAN -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary text-white">Form Tambah Catatan Pelanggaran</div>
            <div class="card-body">
                <form method="POST">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Siswa</label>
                            <select name="siswa_id" id="selectSiswa" class="form-select" required>
                                <option value="">-- Pilih Siswa --</option>
                                <?php 
                                $q = mysqli_query($koneksi, "
                                    SELECT s.id, s.nis, s.nama, k.nama AS nama_kelas 
                                    FROM t_siswa s 
                                    LEFT JOIN t_kelas_siswa ks ON s.id = ks.siswa_id AND ks.status_aktif = 1 
                                    LEFT JOIN t_kelas k ON ks.kelas_id = k.id 
                                    ORDER BY s.nama ASC
                                "); 
                                while($r = mysqli_fetch_assoc($q)): 
                                    $info_kelas = $r['nama_kelas'] ? ' (Kelas: ' . $r['nama_kelas'] . ')' : ' (Tanpa Kelas)';
                                ?>
                                    <option value="<?= $r['id'] ?>"><?= $r['nis'].' - '.$r['nama'].$info_kelas ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Pelanggaran</label>
                            <select name="pelanggaran_id" id="selectPelanggaran" class="form-select" required>
                                <option value="">-- Pilih Pelanggaran --</option>
                                <?php $q = mysqli_query($koneksi, "SELECT id, kode, nama, poin FROM t_pelanggaran ORDER BY kode ASC"); while($r = mysqli_fetch_assoc($q)): ?>
                                    <option value="<?= $r['id'] ?>"><?= $r['kode'].' - '.$r['nama'].' ('.$r['poin'].' Poin)' ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                    <a href="catat_pelanggaran.php" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>

    <?php else: ?>

        <!-- TOMBOL TAMBAH & TABEL DATA PELANGGARAN -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>Data Pelanggaran Siswa (Semua Kelas)</h5>
            <a href="catat_pelanggaran.php?aksi=tambah" class="btn btn-primary">
                + Catat Pelanggaran
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-dark text-white">Daftar Pelanggaran Siswa</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabelPelanggaran" class="table table-bordered table-striped align-middle w-100">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Siswa</th>
                                <th>Kelas</th>
                                <th>Pelanggaran</th>
                                <th>Poin</th>
                                <th>Guru Pencatat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no=1; 
                            // TANPA FILTER WHERE KELAS WALI KELAS, SEHINGGA SEMUA KELAS MUNCUL
                            $sql_pelanggaran = "SELECT ps.*, s.nama AS s_nama, k.nama AS k_nama, p.nama AS p_nama 
                                                FROM t_pelanggaran_siswa ps 
                                                LEFT JOIN t_siswa s ON ps.siswa_id = s.id 
                                                LEFT JOIN t_kelas k ON ps.kelas_id = k.id 
                                                LEFT JOIN t_pelanggaran p ON ps.pelanggaran_id = p.id
                                                ORDER BY ps.id DESC";
                            
                            $data = mysqli_query($koneksi, $sql_pelanggaran);
                            while($row = mysqli_fetch_assoc($data)): 
                            ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <?= '<td>' . htmlspecialchars($row['tanggal']) . '</td>' ?>
                                <td><?= htmlspecialchars($row['s_nama'] ?? $row['nama_siswa'] ?? '-'); ?></td>
                                <td><?= htmlspecialchars($row['k_nama'] ?? $row['nama_kelas'] ?? '-'); ?></td>
                                <td><?= htmlspecialchars($row['p_nama'] ?? $row['nama_pelanggaran'] ?? '-'); ?></td>
                                <td><span class="badge bg-danger"><?= (int)$row['poin']; ?></span></td>
                                <td><?= htmlspecialchars($row['nama_guru'] ?? '-'); ?></td>
                                <td><a href="catat_pelanggaran.php?hapus=<?= (int)$row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data?')">Hapus</a></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>
<!-- Tambahan JS Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function () { 
    $('#tabelPelanggaran').DataTable({
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            zeroRecords: "Belum ada data catatan pelanggaran",
            paginate: { first: "Pertama", last: "Terakhir", next: "Berikutnya", previous: "Sebelumnya" }
        }
    }); 

    // Mengaktifkan fitur pencarian/search pada select siswa dan pelanggaran
    $('#selectSiswa').select2({
        theme: 'bootstrap-5',
        placeholder: '-- Pilih Siswa --',
        allowClear: true
    });

    $('#selectPelanggaran').select2({
        theme: 'bootstrap-5',
        placeholder: '-- Pilih Pelanggaran --',
        allowClear: true
    });
});
</script>
</body>
</html>