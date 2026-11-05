<?php
require_once 'koneksi.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$role  = strtolower($_SESSION['role'] ?? '');
$is_wk = $_SESSION['is_wali_kelas'] ?? false;

// =========================
// PROSES SIMPAN (TAMBAH / EDIT)
// =========================
if (isset($_POST['simpan'])) {
    $id             = $_POST['id'] ?? '';
    $siswa_id       = (int) ($_POST['siswa_id'] ?? 0);
    $pelanggaran_id = (int) ($_POST['pelanggaran_id'] ?? 0);
    $tanggal        = $_POST['tanggal'] ?? date('Y-m-d');

    // 1. Ambil tahun ajaran aktif dari t_tahun_ajaran
    $tahun_ajaran_id = 51; // Fallback default ID
    $q_ta = mysqli_query($koneksi, "SELECT id FROM t_tahun_ajaran WHERE status_aktif = 1 LIMIT 1");
    if ($q_ta && mysqli_num_rows($q_ta) > 0) {
        $d_ta = mysqli_fetch_assoc($q_ta);
        $tahun_ajaran_id = (int) $d_ta['id'];
    }

    // 2. Ambil data siswa & kelas dari relasi t_kelas_siswa atau t_siswa
    $nama_siswa = '-';
    $kelas_id   = 0;
    $nama_kelas = '-';

    $q_s = mysqli_query($koneksi, "SELECT s.nama, k.id AS kelas_id, k.nama AS nama_kelas 
                                   FROM t_siswa s 
                                   LEFT JOIN t_kelas_siswa ks ON s.id = ks.siswa_id AND ks.status_aktif = 1 
                                   LEFT JOIN t_kelas k ON ks.kelas_id = k.id 
                                   WHERE s.id = $siswa_id LIMIT 1");
    if ($q_s && mysqli_num_rows($q_s) > 0) {
        $s = mysqli_fetch_assoc($q_s);
        $nama_siswa = $s['nama'] ?? '-';
        $kelas_id   = (int) ($s['kelas_id'] ?? 0);
        $nama_kelas = $s['nama_kelas'] ?? '-';
    }

    // 3. Ambil data pelanggaran, poin, dan kategori
    $nama_pelanggaran = '-';
    $kategori_id      = 0;
    $poin             = 0;
    $q_p = mysqli_query($koneksi, "SELECT nama, poin, pelanggaran_kategori_id FROM t_pelanggaran WHERE id = $pelanggaran_id LIMIT 1");
    if ($q_p && mysqli_num_rows($q_p) > 0) {
        $p = mysqli_fetch_assoc($q_p);
        $nama_pelanggaran = $p['nama'] ?? '-';
        $kategori_id      = (int) ($p['pelanggaran_kategori_id'] ?? 0);
        $poin             = (int) ($p['poin'] ?? 0);
    }

    // 4. Ambil data guru pelapor (mengambil guru pertama atau dari session user yang login jika ada)
    $guru_id   = 1;
    $nama_guru = 'Administrator';
    $user_id_sekarang = $_SESSION['user_id'] ?? 0;
    
    $q_g = mysqli_query($koneksi, "SELECT id, nama FROM t_guru WHERE user_id = $user_id_sekarang LIMIT 1");
    if ($q_g && mysqli_num_rows($q_g) > 0) {
        $g = mysqli_fetch_assoc($q_g);
        $guru_id   = (int) $g['id'];
        $nama_guru = $g['nama'];
    } else {
        $q_g2 = mysqli_query($koneksi, "SELECT id, nama FROM t_guru LIMIT 1");
        if ($q_g2 && mysqli_num_rows($q_g2) > 0) {
            $g2 = mysqli_fetch_assoc($q_g2);
            $guru_id   = (int) $g2['id'];
            $nama_guru = $g2['nama'];
        }
    }

    $keterangan = '-';
    $tindakan   = '-';
    $status     = 'Pending';

    if ($siswa_id > 0 && $pelanggaran_id > 0) {
        if (!empty($id)) {
            // UPDATE DATA (13 parameter & 13 tipe data)
            $stmt = mysqli_prepare($koneksi, "UPDATE t_pelanggaran_siswa SET 
                tahun_ajaran_id=?, siswa_id=?, nama_siswa=?, kelas_id=?, nama_kelas=?, 
                pelanggaran_id=?, nama_pelanggaran=?, pelanggaran_kategori_id=?, 
                guru_id=?, nama_guru=?, tanggal=?, poin=? WHERE id=?");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "iisisisiisssi", 
                    $tahun_ajaran_id, $siswa_id, $nama_siswa, $kelas_id, $nama_kelas, 
                    $pelanggaran_id, $nama_pelanggaran, $kategori_id, 
                    $guru_id, $nama_guru, $tanggal, $poin, $id
                );
            }
        } else {
            // INSERT DATA BARU (15 parameter & 15 tipe data yang cocok)
            $stmt = mysqli_prepare($koneksi, "INSERT INTO t_pelanggaran_siswa (
                tahun_ajaran_id, siswa_id, nama_siswa, kelas_id, nama_kelas, 
                pelanggaran_id, nama_pelanggaran, pelanggaran_kategori_id, 
                guru_id, nama_guru, tanggal, keterangan, poin, tindakan, status, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "iisisisiisssiss", 
                    $tahun_ajaran_id, $siswa_id, $nama_siswa, $kelas_id, $nama_kelas, 
                    $pelanggaran_id, $nama_pelanggaran, $kategori_id, 
                    $guru_id, $nama_guru, $tanggal, $keterangan, $poin, $tindakan, $status
                );
            }
        }

        if (isset($stmt) && $stmt && mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: catat_pelanggaran.php?msg=sukses");
            exit();
        } else {
            die("Query Error: " . mysqli_error($koneksi));
        }
    }
    header("Location: catat_pelanggaran.php?msg=gagal");
    exit();
}

// =========================
// PROSES HAPUS
// =========================
if (isset($_GET['hapus'])) {
    $hapus_id = (int) $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM t_pelanggaran_siswa WHERE id = $hapus_id");
    header("Location: catat_pelanggaran.php?msg=hapus");
    exit();
}

$aksi    = $_GET['aksi'] ?? '';
$edit_id = $_GET['edit'] ?? '';
$edit    = $edit_id ? mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM t_pelanggaran_siswa WHERE id='$edit_id'")) : null;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catat Pelanggaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .sidebar { width: 250px; height: 100vh; position: fixed; top: 0; left: 0; background: #212529; color: #fff; padding-top: 15px; overflow-y: auto; z-index: 1000; }
        .sidebar a { display: block; padding: 10px 20px; color: #adb5bd; text-decoration: none; border-radius: 5px; }
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
    <hr><a href="logout.php" class="text-danger" onclick="return confirm('Keluar dari aplikasi?')">Logout</a>
</div>

<!-- KONTEN UTAMA -->
<div class="main">
    <h3 class="fw-bold mb-3"><?= $edit ? 'Edit Catatan Pelanggaran' : ($aksi === 'tambah' ? 'Tambah Catatan Pelanggaran' : 'Catat Pelanggaran'); ?></h3>
    <hr>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-<?= $_GET['msg'] === 'gagal' ? 'danger' : 'success'; ?> alert-dismissible fade show">
            <?= $_GET['msg'] === 'sukses' ? 'Data berhasil disimpan.' : ($_GET['msg'] === 'hapus' ? 'Data berhasil dihapus.' : 'Gagal memproses data.'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($aksi === 'tambah' || $edit): ?>
        <!-- FORM INPUT / EDIT (Tanggal, Siswa, Pelanggaran) -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary text-white">Form Pelanggaran</div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($edit['id'] ?? ''); ?>">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="<?= htmlspecialchars($edit['tanggal'] ?? date('Y-m-d')); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Siswa</label>
                        <select name="siswa_id" class="form-select" required>
                            <option value="">-- Pilih Siswa --</option>
                            <?php
                            $q = mysqli_query($koneksi, "SELECT id, nis, nama FROM t_siswa ORDER BY nama ASC");
                            if ($q) {
                                while ($r = mysqli_fetch_assoc($q)):
                                    $sel = (($edit['siswa_id'] ?? '') == $r['id']) ? 'selected' : '';
                            ?>
                                    <option value="<?= (int)$r['id']; ?>" <?= $sel; ?>><?= htmlspecialchars(($r['nis'] ?? '') . ' - ' . ($r['nama'] ?? '')); ?></option>
                            <?php 
                                endwhile;
                            } 
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Pelanggaran</label>
                        <select name="pelanggaran_id" class="form-select" required>
                            <option value="">-- Pilih Pelanggaran --</option>
                            <?php
                            $q = mysqli_query($koneksi, "SELECT id, kode, nama, poin FROM t_pelanggaran ORDER BY kode ASC");
                            if ($q) {
                                while ($r = mysqli_fetch_assoc($q)):
                                    $sel = (($edit['pelanggaran_id'] ?? '') == $r['id']) ? 'selected' : '';
                            ?>
                                    <option value="<?= (int)$r['id']; ?>" <?= $sel; ?>><?= htmlspecialchars(($r['kode'] ?? '') . ' - ' . ($r['nama'] ?? '') . ' (' . ($r['poin'] ?? 0) . ' Poin)'); ?></option>
                            <?php 
                                endwhile;
                            } 
                            ?>
                        </select>
                    </div>

                    <button type="submit" name="simpan" class="btn btn-primary"><?= $edit ? 'Update Data' : 'Simpan'; ?></button>
                    <a href="catat_pelanggaran.php" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    <?php else: ?>
        <!-- TABEL DATA -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Data Pelanggaran Siswa</h5>
            <a href="catat_pelanggaran.php?aksi=tambah" class="btn btn-primary btn-sm">+ Tambah Data</a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-dark text-white">Daftar Pelanggaran</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabelPelanggaran" class="table table-bordered table-striped table-hover align-middle w-100">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Siswa</th>
                                <th>Kelas</th>
                                <th>Pelanggaran</th>
                                <th>Poin</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $data = mysqli_query($koneksi, "SELECT ps.id, ps.tanggal, ps.poin, s.nama AS nama_siswa_asli, k.nama AS nama_kelas_asli, p.nama AS nama_pelanggaran_asli FROM t_pelanggaran_siswa AS ps LEFT JOIN t_siswa AS s ON ps.siswa_id = s.id LEFT JOIN t_kelas AS k ON ps.kelas_id = k.id LEFT JOIN t_pelanggaran AS p ON ps.pelanggaran_id = p.id ORDER BY ps.tanggal DESC, ps.id DESC");
                            if ($data) {
                                while ($row = mysqli_fetch_assoc($data)):
                            ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= htmlspecialchars($row['tanggal'] ?? ''); ?></td>
                                        <td><?= htmlspecialchars($row['nama_siswa_asli'] ?? '-'); ?></td>
                                        <td><?= htmlspecialchars($row['nama_kelas_asli'] ?? '-'); ?></td>
                                        <td><?= htmlspecialchars($row['nama_pelanggaran_asli'] ?? '-'); ?></td>
                                        <td><span class="badge bg-danger"><?= (int)($row['poin'] ?? 0); ?></span></td>
                                        <td>
                                            <a href="catat_pelanggaran.php?edit=<?= (int)$row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <a href="catat_pelanggaran.php?hapus=<?= (int)$row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data ini?')">Hapus</a>
                                        </td>
                                    </tr>
                            <?php 
                                endwhile;
                            } 
                            ?>
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
<script>
$(document).ready(function () {
    $('#tabelPelanggaran').DataTable({
        language: { search: 'Cari:', zeroRecords: 'Data tidak ditemukan' },
        pageLength: 10,
        order: [[1, 'desc']],
        columnDefs: [{ targets: [0, 6], orderable: false }]
    });
});
</script>
</body>
</html>