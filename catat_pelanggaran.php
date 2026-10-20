<?php
session_start();
$koneksi = mysqli_connect("localhost", "root", "", "db_ukk_2026");
if (!$koneksi) die("Koneksi gagal: " . mysqli_connect_error());
function e($v) { return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8'); }

if (isset($_POST['simpan'])) {
    $s = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nama FROM t_siswa WHERE id=".(int)$_POST['siswa_id']));
    $k = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nama FROM t_kelas WHERE id=".(int)$_POST['kelas_id']));
    $p = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nama, poin, pelanggaran_kategori_id FROM t_pelanggaran WHERE id=".(int)$_POST['pelanggaran_id']));
    $g = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nama FROM t_guru WHERE id=".(int)$_POST['guru_id']));

    if ($s && $k && $p && $g) {
        $stmt = mysqli_prepare($koneksi, "INSERT INTO t_pelanggaran_siswa (tahun_ajaran_id, siswa_id, nama_siswa, kelas_id, nama_kelas, pelanggaran_id, nama_pelanggaran, pelanggaran_kategori_id, guru_id, nama_guru, tanggal, keterangan, poin, tindakan, status, created_at) VALUES (46, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        mysqli_stmt_bind_param($stmt, "isisisisssssiss", $_POST['siswa_id'], $s['nama'], $_POST['kelas_id'], $k['nama'], $_POST['pelanggaran_id'], $p['nama'], $p['pelanggaran_kategori_id'], $_POST['guru_id'], $g['nama'], $_POST['tanggal'], $_POST['keterangan'], $p['poin'], $_POST['tindakan'], $_POST['status']);
        mysqli_stmt_execute($stmt);
        header("Location: catat_pelanggaran.php?msg=sukses"); exit();
    }
}
if (isset($_GET['hapus'])) {
    mysqli_query($koneksi, "DELETE FROM t_pelanggaran_siswa WHERE id=".(int)$_GET['hapus']);
    header("Location: catat_pelanggaran.php?msg=hapus"); exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catat Pelanggaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .sidebar { min-height: 100vh; background: #212529; color: #fff; position: fixed; width: 250px; top: 0; left: 0; overflow-y: auto; }
        .sidebar a { color: #adb5bd; text-decoration: none; display: block; padding: 10px 20px; border-radius: 5px; }
        .sidebar a:hover, .sidebar a.active { color: #fff; background: #0d6efd; }
        .main { margin-left: 250px; padding: 20px; }
        @media (max-width: 767.98px) { .sidebar { position: relative; width: 100%; min-height: auto; } .main { margin-left: 0; } }
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
        <li class="nav-item mb-1"><a href="about_me.php">About Me</a></li>
    </ul>
    <hr><a href="logout.php" class="text-danger">Logout</a>
</div>

<div class="main">
    <h3 class="fw-bold mb-3">Catat Pelanggaran</h3>
    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-info py-2"><?= $_GET['msg'] === 'hapus' ? 'Data dihapus!' : 'Data diproses!'; ?></div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-primary text-white">Form Tambah Catatan Pelanggaran</div>
        <div class="card-body">
            <form method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Siswa</label>
                        <select name="siswa_id" class="form-select" required>
                            <option value="">-- Pilih Siswa --</option>
                            <?php $q = mysqli_query($koneksi, "SELECT id, nis, nama FROM t_siswa ORDER BY nama"); while ($r = mysqli_fetch_assoc($q)): ?>
                                <option value="<?= (int)$r['id']; ?>"><?= e($r['nis'].' - '.$r['nama']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kelas</label>
                        <select name="kelas_id" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php $q = mysqli_query($koneksi, "SELECT id, nama FROM t_kelas ORDER BY nama"); while ($r = mysqli_fetch_assoc($q)): ?>
                                <option value="<?= (int)$r['id']; ?>"><?= e($r['nama']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pelanggaran</label>
                        <select name="pelanggaran_id" class="form-select" required>
                            <option value="">-- Pilih Pelanggaran --</option>
                            <?php $q = mysqli_query($koneksi, "SELECT id, kode, nama, poin FROM t_pelanggaran ORDER BY nama"); while ($r = mysqli_fetch_assoc($q)): ?>
                                <option value="<?= (int)$r['id']; ?>"><?= e($r['kode'].' - '.$r['nama'].' ('.$r['poin'].' Poin)'); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Guru Pelapor</label>
                        <select name="guru_id" class="form-select" required>
                            <option value="">-- Pilih Guru --</option>
                            <?php $q = mysqli_query($koneksi, "SELECT id, nama FROM t_guru ORDER BY nama"); while ($r = mysqli_fetch_assoc($q)): ?>
                                <option value="<?= (int)$r['id']; ?>"><?= e($r['nama']); ?></option>
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
                    <input type="text" name="keterangan" class="form-control mb-2" placeholder="Keterangan kejadian...">
                    <label class="form-label">Tindakan / Sanksi</label>
                    <input type="text" name="tindakan" class="form-control" placeholder="Tindakan / sanksi...">
                </div>

                <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>