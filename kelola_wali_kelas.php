<?php
/**
 * 1. KONEKSI & SESSION
 * Memulai sesi dan memastikan hanya admin/user yang sudah login yang bisa mengakses.
 */
require_once 'koneksi.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$role_login = $_SESSION['role'] ?? '';
$nama_user_login = $_SESSION['nama'] ?? $_SESSION['username'] ?? '';

// Fungsi enkripsi output HTML untuk mencegah XSS
function e($v) { return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8'); }

/**
 * 2. PROSES SIMPAN / EDIT DATA (KHUSUS ADMIN)
 */
if (isset($_POST['simpan'])) {
    if ($role_login !== 'admin') die("Akses ditolak.");

    $id = (int)($_POST['id'] ?? 0);
    $tahun = (int)$_POST['tahun_ajaran_id'];
    $kelas = (int)$_POST['kelas_id'];
    $guru = (int)$_POST['guru_id'];
    $mulai = $id ? ($_POST['tanggal_mulai'] ?: null) : date('Y-m-d');
    $selesai = $id ? ($_POST['tanggal_selesai'] ?: null) : null;
    $status = isset($_POST['status_aktif']) ? 1 : 0;

    if ($selesai && $mulai && $selesai < $mulai) {
        die("Tanggal selesai tidak boleh sebelum tanggal mulai.");
    }

    /**
     * CEK DUPLIKASI DATA
     * Mencegah 1 Kelas atau 1 Guru didaftarkan 2 kali pada Tahun Ajaran yang sama.
     */
    $cek_dup = mysqli_prepare($koneksi, "SELECT id FROM t_wali_kelas WHERE tahun_ajaran_id = ? AND (kelas_id = ? OR guru_id = ?) AND id != ?");
    mysqli_stmt_bind_param($cek_dup, "iiii", $tahun, $kelas, $guru, $id);
    mysqli_stmt_execute($cek_dup);
    mysqli_stmt_store_result($cek_dup);

    if (mysqli_stmt_num_rows($cek_dup) > 0) {
        echo "<script>alert('Gagal! Kelas atau Guru tersebut sudah terdaftar sebagai wali kelas pada tahun ajaran ini.'); window.history.back();</script>";
        exit();
    }

    // Eksekusi Simpan (Insert / Update)
    if ($id) {
        $stmt = mysqli_prepare($koneksi, "UPDATE t_wali_kelas SET tahun_ajaran_id=?, kelas_id=?, guru_id=?, tanggal_mulai=?, tanggal_selesai=?, status_aktif=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "iiissii", $tahun, $kelas, $guru, $mulai, $selesai, $status, $id);
    } else {
        $stmt = mysqli_prepare($koneksi, "INSERT INTO t_wali_kelas (tahun_ajaran_id, kelas_id, guru_id, tanggal_mulai, tanggal_selesai, status_aktif) VALUES (?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "iiissi", $tahun, $kelas, $guru, $mulai, $selesai, $status);
    }

    mysqli_stmt_execute($stmt);
    header("Location: kelola_wali_kelas.php");
    exit();
}

/**
 * 3. PROSES HAPUS DATA
 */
if (isset($_GET['hapus'])) {
    if ($role_login !== 'admin') die("Akses ditolak.");
    $stmt = mysqli_prepare($koneksi, "DELETE FROM t_wali_kelas WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $_GET['hapus']);
    mysqli_stmt_execute($stmt);
    header("Location: kelola_wali_kelas.php");
    exit();
}

/**
 * 4. AMBIL DATA FORM EDIT & DROPDOWN
 */
$aksi = $_GET['aksi'] ?? '';
$edit_id = (int)($_GET['edit'] ?? 0);
$edit = ['id'=>'', 'tahun_ajaran_id'=>'', 'kelas_id'=>'', 'guru_id'=>'', 'tanggal_mulai'=>'', 'tanggal_selesai'=>'', 'status_aktif'=>1];

if ($edit_id) {
    if ($role_login !== 'admin') die("Akses ditolak.");
    $stmt = mysqli_prepare($koneksi, "SELECT * FROM t_wali_kelas WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $edit_id);
    mysqli_stmt_execute($stmt);
    $edit = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)) ?: $edit;
}

$guru  = mysqli_query($koneksi, "SELECT id, nama FROM t_guru WHERE status_aktif=1 ORDER BY nama");
$kelas = mysqli_query($koneksi, "SELECT id, nama FROM t_kelas WHERE status_aktif=1 ORDER BY nama");
$tahun = mysqli_query($koneksi, "SELECT id, nama FROM t_tahun_ajaran WHERE status_aktif=1 ORDER BY nama DESC");

/**
 * 5. AMBIL DATA UTAMA TABEL WALI KELAS
 */
if ($role_login === 'admin') {
    $data = mysqli_query($koneksi, "SELECT wk.*, g.nama AS nama_guru, k.nama AS nama_kelas, ta.nama AS nama_tahun FROM t_wali_kelas wk LEFT JOIN t_guru g ON wk.guru_id=g.id LEFT JOIN t_kelas k ON wk.kelas_id=k.id LEFT JOIN t_tahun_ajaran ta ON wk.tahun_ajaran_id=ta.id ORDER BY wk.id DESC");
} else {
    $stmtData = mysqli_prepare($koneksi, "SELECT wk.*, g.nama AS nama_guru, k.nama AS nama_kelas, ta.nama AS nama_tahun FROM t_wali_kelas wk LEFT JOIN t_guru g ON wk.guru_id=g.id LEFT JOIN t_kelas k ON wk.kelas_id=k.id LEFT JOIN t_tahun_ajaran ta ON wk.tahun_ajaran_id=ta.id WHERE g.nama LIKE ? ORDER BY wk.id DESC");
    $cariNama = "%".$nama_user_login."%";
    mysqli_stmt_bind_param($stmtData, "s", $cariNama);
    mysqli_stmt_execute($stmtData);
    $data = mysqli_stmt_get_result($stmtData);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Kelola Wali Kelas</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
<style>
body { background:#f8f9fa; }
.sidebar { min-height:100vh; background:#212529; color:#fff; position:fixed; width:250px; top:0; left:0; }
.sidebar a { color:#adb5bd; text-decoration:none; display:block; padding:10px 20px; border-radius:5px; }
.sidebar a:hover, .sidebar a.active { color:#fff; background:#0d6efd; }
.main-content { margin-left:250px; padding:20px; }
.select2-container { width:100% !important; }
@media(max-width:767.98px) { .sidebar { position:relative; width:100%; min-height:auto; } .main-content { margin-left:0; } }
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
        <li class="nav-item mb-1"><a href="kelola_wali_kelas.php" class="active">Kelola Wali Kelas</a></li>
        <li class="nav-item mb-1"><a href="kelola_kategori_pelanggaran.php">Kategori Pelanggaran</a></li>
        <li class="nav-item mb-1"><a href="kelola_jenis_pelanggaran.php">Jenis Pelanggaran</a></li>
        <li class="nav-item mb-1"><a href="laporan.php">Laporan</a></li>
        <li class="nav-item mb-1"><a href="about_me.php">About Me</a></li>
    </ul>
    <hr><a href="logout.php" class="text-danger">Logout</a>
</div>

<!-- KONTEN UTAMA -->
<div class="main-content">
    <h2 class="mb-3"><?= $edit_id ? 'Edit Wali Kelas' : ($aksi === 'tambah' ? 'Tambah Wali Kelas' : 'Kelola Wali Kelas'); ?></h2>
    <hr>

    <?php if (($aksi === 'tambah' || $edit_id) && $role_login === 'admin'): ?>
    <!-- FORM TAMBAH / EDIT -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white"><?= $edit_id ? 'Edit' : 'Tambah'; ?> Wali Kelas</div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="id" value="<?= e($edit['id']); ?>">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tahun Ajaran</label>
                        <select name="tahun_ajaran_id" id="tahun_ajaran_id" class="form-select" required>
                            <option value="">-- Pilih Tahun Ajaran --</option>
                            <?php while($t=mysqli_fetch_assoc($tahun)): ?>
                                <option value="<?= $t['id']; ?>" <?= $edit['tahun_ajaran_id']==$t['id']?'selected':'' ?>><?= e($t['nama']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kelas</label>
                        <select name="kelas_id" id="kelas_id" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php while($k=mysqli_fetch_assoc($kelas)): ?>
                                <option value="<?= $k['id']; ?>" <?= $edit['kelas_id']==$k['id']?'selected':'' ?>><?= e($k['nama']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Guru Wali Kelas</label>
                        <select name="guru_id" id="guru_id" class="form-select" required>
                            <option value="">-- Pilih Guru --</option>
                            <?php while($g=mysqli_fetch_assoc($guru)): ?>
                                <option value="<?= $g['id']; ?>" <?= $edit['guru_id']==$g['id']?'selected':'' ?>><?= e($g['nama']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <?php if ($edit_id): ?>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label><br>
                        <input type="checkbox" name="status_aktif" value="1" <?= $edit['status_aktif']?'checked':'' ?>> Aktif
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="form-control" value="<?= e($edit['tanggal_mulai']); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" class="form-control" value="<?= e($edit['tanggal_selesai']); ?>">
                    </div>
                    <?php endif; ?>
                </div>
                <button type="submit" name="simpan" class="btn btn-primary"><?= $edit_id ? 'Update' : 'Simpan'; ?></button>
                <a href="kelola_wali_kelas.php" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
    <?php else: ?>
    <!-- TABEL DATA -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5>Daftar Wali Kelas</h5>
        <?php if ($role_login === 'admin'): ?>
            <a href="kelola_wali_kelas.php?aksi=tambah" class="btn btn-primary">+ Tambah Wali Kelas</a>
        <?php endif; ?>
    </div>
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">Daftar Wali Kelas</div>
        <div class="card-body table-responsive">
            <table id="tabelWaliKelas" class="table table-bordered table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th><th>Guru</th><th>Kelas</th><th>Tahun Ajaran</th><th>Mulai</th><th>Selesai</th><th>Status</th>
                        <?php if ($role_login === 'admin'): ?><th>Aksi</th><?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=1; while($r=mysqli_fetch_assoc($data)): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= e($r['nama_guru']); ?></td>
                        <td><?= e($r['nama_kelas']); ?></td>
                        <td><?= e($r['nama_tahun']); ?></td>
                        <td><?= e($r['tanggal_mulai'] ?: '-'); ?></td>
                        <td><?= e($r['tanggal_selesai'] ?: '-'); ?></td>
                        <td><span class="badge bg-<?= $r['status_aktif']?'success':'secondary'; ?>"><?= $r['status_aktif']?'Aktif':'Tidak Aktif'; ?></span></td>
                        <?php if ($role_login === 'admin'): ?>
                        <td class="text-center text-nowrap">
                            <a href="?edit=<?= (int)$r['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="?hapus=<?= (int)$r['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data ini?')">Hapus</a>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- JAVASCRIPT -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function () {
    $('#tahun_ajaran_id, #kelas_id, #guru_id').select2({ theme: 'bootstrap-5', width: '100%' });
    if ($('#tabelWaliKelas').length) { new DataTable('#tabelWaliKelas'); }
});
</script>
</body>
</html>