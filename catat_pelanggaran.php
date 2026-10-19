<?php
require_once 'koneksi.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header("Location: login.php"); exit(); }

if (isset($_POST['simpan'])) {
    $id = $_POST['id'];
    $tahun_ajaran_id = $_POST['tahun_ajaran_id'];
    $siswa_id = $_POST['siswa_id'];
    $kelas_id = $_POST['kelas_id'];
    $pelanggaran_id = $_POST['pelanggaran_id'];
    $guru_id = $_POST['guru_id'];
    $tanggal = $_POST['tanggal'];
    $keterangan = $_POST['keterangan'];
    $tindakan = $_POST['tindakan'];
    $status = $_POST['status'];

    // Ambil data pendukung untuk menyimpan nama-nama sesuai struktur tabel Anda
    $s_siswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nama FROM t_siswa WHERE id='$siswa_id'"));
    $s_kelas = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nama FROM t_kelas WHERE id='$kelas_id'"));
    $s_pelanggaran = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nama, poin, pelanggaran_kategori_id FROM t_pelanggaran WHERE id='$pelanggaran_id'"));
    $s_guru = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nama FROM t_guru WHERE id='$guru_id'"));

    $nama_siswa = $s_siswa['nama'] ?? '';
    $nama_kelas = $s_kelas['nama'] ?? '';
    $nama_pelanggaran = $s_pelanggaran['nama'] ?? '';
    $poin = $s_pelanggaran['poin'] ?? 0;
    $pelanggaran_kategori_id = $s_pelanggaran['pelanggaran_kategori_id'] ?? 0;
    $nama_guru = $s_guru['nama'] ?? '';

    if ($id) {
        $query = "UPDATE t_pelanggaran_siswa SET 
                    tahun_ajaran_id='$tahun_ajaran_id', siswa_id='$siswa_id', nama_siswa='$nama_siswa', 
                    kelas_id='$kelas_id', nama_kelas='$nama_kelas', pelanggaran_id='$pelanggaran_id', 
                    nama_pelanggaran='$nama_pelanggaran', pelanggaran_kategori_id='$pelanggaran_kategori_id', 
                    guru_id='$guru_id', nama_guru='$nama_guru', tanggal='$tanggal', 
                    keterangan='$keterangan', poin='$poin', tindakan='$tindakan', status='$status' 
                  WHERE id='$id'";
        mysqli_query($koneksi, $query);
    } else {
        $query = "INSERT INTO t_pelanggaran_siswa 
                    (tahun_ajaran_id, siswa_id, nama_siswa, kelas_id, nama_kelas, pelanggaran_id, nama_pelanggaran, pelanggaran_kategori_id, guru_id, nama_guru, tanggal, keterangan, poin, tindakan, status, created_at) 
                  VALUES 
                    ('$tahun_ajaran_id', '$siswa_id', '$nama_siswa', '$kelas_id', '$nama_kelas', '$pelanggaran_id', '$nama_pelanggaran', '$pelanggaran_kategori_id', '$guru_id', '$nama_guru', '$tanggal', '$keterangan', '$poin', '$tindakan', '$status', NOW())";
        mysqli_query($koneksi, $query);
    }
    header("Location: catatan_pelanggaran.php"); exit();
}

if (isset($_GET['hapus'])) {
    mysqli_query($koneksi, "DELETE FROM t_pelanggaran_siswa WHERE id='{$_GET['hapus']}'");
    header("Location: catatan_pelanggaran.php"); exit();
}

$edit = isset($_GET['edit']) ? mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM t_pelanggaran_siswa WHERE id='{$_GET['edit']}'")) : [
    'id'=>'','tahun_ajaran_id'=>'','siswa_id'=>'','kelas_id'=>'','pelanggaran_id'=>'','guru_id'=>'',
    'tanggal'=>date('Y-m-d'),'keterangan'=>'','tindakan'=>'','status'=>'Proses'
];

// Data untuk dropdown
$listTahun = mysqli_query($koneksi, "SELECT id, nama FROM t_tahun_ajaran ORDER BY nama DESC");
$listSiswa = mysqli_query($koneksi, "SELECT id, nis, nama FROM t_siswa WHERE status_aktif = 1 ORDER BY nama ASC");
$listKelas = mysqli_query($koneksi, "SELECT id, nama FROM t_kelas WHERE status_aktif = 1 ORDER BY nama ASC");
$listPelanggaran = mysqli_query($koneksi, "SELECT id, kode, nama, poin FROM t_pelanggaran WHERE status_aktif = 1 ORDER BY nama ASC");
$listGuru = mysqli_query($koneksi, "SELECT id, nama FROM t_guru WHERE status_aktif = 1 ORDER BY nama ASC");

// Data tabel utama
$data = mysqli_query($koneksi, "SELECT * FROM t_pelanggaran_siswa ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Catatan Pelanggaran Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; overflow: hidden; }
        .sidebar { min-height: 100vh; background-color: #212529; color: #fff; position: fixed; width: 250px; top: 0; left: 0; overflow-y: auto; z-index: 1000; }
        .sidebar a { color: #adb5bd; text-decoration: none; display: block; padding: 10px 20px; }
        .sidebar a:hover, .sidebar a.active { color: #fff; background-color: #0d6efd; }
        .main-content { margin-left: 250px; height: 100vh; overflow-y: auto; padding: 20px; }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar d-flex flex-column p-3">
        <h4 class="text-white mb-4">Pelanggaran Siswa</h4>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item mb-1"><a href="dashboard.php">Dashboard</a></li>
            <li class="nav-item mb-1"><a href="kelola_guru.php">Kelola Guru</a></li>
            <li class="nav-item mb-1"><a href="kelola_siswa.php">Kelola Siswa</a></li>
            <li class="nav-item mb-1"><a href="kelola_kelas.php">Kelola Kelas</a></li>
            <li class="nav-item mb-1"><a href="kelola_tahun_ajaran.php">Kelola Tahun Ajaran</a></li>
            <li class="nav-item mb-1"><a href="penempatan_siswa.php">Penempatan Siswa</a></li>
            <li class="nav-item mb-1"><a href="kelola_wali_kelas.php">Kelola Wali Kelas</a></li>
            <li class="nav-item mb-1"><a href="kelola_kategori_pelanggaran.php">Kelola Kategori Pelanggaran</a></li>
            <li class="nav-item mb-1"><a href="laporan.php">laporan</a></li>
            <li class="nav-item mb-1"><a href="kelola_jenis_pelanggaran.php">Kelola Jenis Pelanggaran</a></li>
            <li class="nav-item mb-1"><a href="catatan_pelanggaran.php" class="active">Catatan Pelanggaran</a></li>
            <li class="nav-item mb-1"><a href="cetak_export.php">Cetak / Export</a></li>
            <li class="nav-item mb-1"><a href="about.php">About Me</a></li>
        </ul>
        <hr>
        <a href="logout.php" class="text-danger">Logout</a>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <h2>Catatan Pelanggaran Siswa</h2>
        <a href="dashboard.php">Dashboard</a> | <a href="logout.php">Logout</a><hr>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="id" value="<?= $edit['id']; ?>">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tahun Ajaran:</label>
                            <select name="tahun_ajaran_id" class="form-control" required>
                                <option value="">-- Pilih Tahun Ajaran --</option>
                                <?php while($t = mysqli_fetch_assoc($listTahun)): ?>
                                    <option value="<?= $t['id'] ?>" <?= $edit['tahun_ajaran_id'] == $t['id'] ? 'selected':'' ?>><?= $t['nama'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Siswa:</label>
                            <select name="siswa_id" class="form-control" required>
                                <option value="">-- Pilih Siswa --</option>
                                <?php while($s = mysqli_fetch_assoc($listSiswa)): ?>
                                    <option value="<?= $s['id'] ?>" <?= $edit['siswa_id'] == $s['id'] ? 'selected':'' ?>><?= $s['nis'] ?> - <?= $s['nama'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Kelas:</label>
                            <select name="kelas_id" class="form-control" required>
                                <option value="">-- Pilih Kelas --</option>
                                <?php while($k = mysqli_fetch_assoc($listKelas)): ?>
                                    <option value="<?= $k['id'] ?>" <?= $edit['kelas_id'] == $k['id'] ? 'selected':'' ?>><?= $k['nama'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Pelanggaran:</label>
                            <select name="pelanggaran_id" class="form-control" required>
                                <option value="">-- Pilih Pelanggaran --</option>
                                <?php while($p = mysqli_fetch_assoc($listPelanggaran)): ?>
                                    <option value="<?= $p['id'] ?>" <?= $edit['pelanggaran_id'] == $p['id'] ? 'selected':'' ?>><?= $p['kode'] ?> - <?= $p['nama'] ?> (Poin: <?= $p['poin'] ?>)</option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Guru Pelapor:</label>
                            <select name="guru_id" class="form-control" required>
                                <option value="">-- Pilih Guru --</option>
                                <?php while($g = mysqli_fetch_assoc($listGuru)): ?>
                                    <option value="<?= $g['id'] ?>" <?= $edit['guru_id'] == $g['id'] ? 'selected':'' ?>><?= $g['nama'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tanggal Kejadian:</label>
                            <input type="date" name="tanggal" class="form-control" value="<?= $edit['tanggal']; ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status:</label>
                            <select name="status" class="form-control" required>
                                <option value="Proses" <?= $edit['status'] == 'Proses' ? 'selected':'' ?>>Proses</option>
                                <option value="Pending" <?= $edit['status'] == 'Pending' ? 'selected':'' ?>>Pending</option>
                                <option value="Selesai" <?= $edit['status'] == 'Selesai' ? 'selected':'' ?>>Selesai</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan Tambahan:</label>
                        <textarea name="keterangan" class="form-control" rows="2"><?= $edit['keterangan']; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tindakan / Sanksi yang Diberikan:</label>
                        <textarea name="tindakan" class="form-control" rows="2"><?= $edit['tindakan']; ?></textarea>
                    </div>

                    <button type="submit" name="simpan" class="btn btn-primary">Simpan Catatan</button>
                    <?php if($edit['id']): ?><a href="catatan_pelanggaran.php" class="btn btn-secondary">Batal</a><?php endif; ?>
                </form>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Siswa</th>
                            <th>Kelas</th>
                            <th>Pelanggaran</th>
                            <th>Poin</th>
                            <th>Guru Pelapor</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($data && mysqli_num_rows($data) > 0): ?>
                            <?php $no=1; while($r = mysqli_fetch_assoc($data)): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $r['tanggal']; ?></td>
                                <td><?= $r['nama_siswa']; ?></td>
                                <td><?= $r['nama_kelas']; ?></td>
                                <td><?= $r['nama_pelanggaran']; ?></td>
                                <td class="text-center"><span class="badge bg-danger"><?= $r['poin']; ?></span></td>
                                <td><?= $r['nama_guru']; ?></td>
                                <td class="text-center">
                                    <?php 
                                        $bg = 'bg-secondary';
                                        if($r['status'] == 'Selesai') $bg = 'bg-success';
                                        else if($r['status'] == 'Proses') $bg = 'bg-warning text-dark';
                                        else if($r['status'] == 'Pending') $bg = 'bg-danger';
                                    ?>
                                    <span class="badge <?= $bg; ?>"><?= $r['status']; ?></span>
                                </td>
                                <td>
                                    <a href="catatan_pelanggaran.php?edit=<?= $r['id']; ?>" class="btn btn-sm btn-warning">Edit</a> | 
                                    <a href="catatan_pelanggaran.php?hapus=<?= $r['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus catatan ini?')">Hapus</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center">Belum ada data catatan pelanggaran siswa.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>