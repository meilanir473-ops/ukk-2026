<?php
require_once 'koneksi.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// SIMPAN & EDIT (OTOMATIS AKTIF JIKA TANGGAL HARI INI MASUK DALAM RENTANG WAKTU)
if (isset($_POST['simpan'])) {
    $id = (int)($_POST['id'] ?? 0);
    $nama = mysqli_real_escape_string($koneksi, trim($_POST['nama'] ?? ''));
    $mulai = mysqli_real_escape_string($koneksi, $_POST['tanggal_mulai'] ?? '');
    $selesai = mysqli_real_escape_string($koneksi, $_POST['tanggal_selesai'] ?? '');

    // Ambil tanggal hari ini (Format: YYYY-MM-DD)
    $today = date('Y-m-d');

    // Cek apakah tanggal hari ini berada di antara tanggal mulai dan tanggal selesai
    $status = ($today >= $mulai && $today <= $selesai) ? 1 : 0;

    // Jika status yang akan disimpan bernilai Aktif (1), nonaktifkan semua tahun ajaran lain
    if ($status === 1) {
        mysqli_query($koneksi, "UPDATE t_tahun_ajaran SET status_aktif = 0");
    }

    if ($id > 0) {
        $query = "UPDATE t_tahun_ajaran SET nama='$nama', tanggal_mulai='$mulai', tanggal_selesai='$selesai', status_aktif=$status WHERE id=$id";
    } else {
        $query = "INSERT INTO t_tahun_ajaran (nama, tanggal_mulai, tanggal_selesai, status_aktif) VALUES ('$nama', '$mulai', '$selesai', $status)";
    }

    if (mysqli_query($koneksi, $query)) {
        header("Location: kelola_tahun_ajaran.php");
        exit();
    } else {
        die("Gagal menyimpan data: " . htmlspecialchars(mysqli_error($koneksi)));
    }
}


// HAPUS
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    
    // Hapus relasi terkait terlebih dahulu agar tidak terkena Foreign Key Constraint
    mysqli_query($koneksi, "DELETE FROM t_wali_kelas WHERE tahun_ajaran_id='$id'");
    
    // Setelah relasi dibersihkan, hapus data tahun ajaran
    if (mysqli_query($koneksi, "DELETE FROM t_tahun_ajaran WHERE id=$id")) {
        header("Location: kelola_tahun_ajaran.php");
        exit();
    } else {
        die("Gagal menghapus data: " . htmlspecialchars(mysqli_error($koneksi)));
    }
}

// DATA EDIT ATAU TAMBAH
$aksi = $_GET['aksi'] ?? '';
$edit_id = $_GET['edit'] ?? '';
$edit = $edit_id ? mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM t_tahun_ajaran WHERE id='$edit_id'")) : null;

// AMBIL DATA UNTUK TABEL
$data = mysqli_query($koneksi, "SELECT * FROM t_tahun_ajaran ORDER BY id DESC");
if (!$data) {
    die("Gagal mengambil data: " . htmlspecialchars(mysqli_error($koneksi)));
}

// NILAI FORM
$form = $edit ?: [
    'id' => '',
    'nama' => '',
    'tanggal_mulai' => '',
    'tanggal_selesai' => ''
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Tahun Ajaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: #212529; color: #fff; position: fixed; width: 250px; top: 0; left: 0; overflow-y: auto; z-index: 1000; }
        .sidebar a { color: #adb5bd; text-decoration: none; display: block; padding: 10px 20px; border-radius: 5px; }
        .sidebar a:hover, .sidebar a.active { color: #fff; background-color: #0d6efd; }
        .main-content { margin-left: 250px; padding: 20px; }
        @media (max-width: 767.98px) {
            .sidebar { position: relative; width: 100%; min-height: auto; }
            .main-content { margin-left: 0; padding: 15px; }
        }
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
        <li class="nav-item mb-1"><a href="kelola_tahun_ajaran.php" class="active">Kelola Tahun Ajaran</a></li>
        <li class="nav-item mb-1"><a href="penempatan_siswa.php">Penempatan Siswa</a></li>
        <li class="nav-item mb-1"><a href="kelola_wali_kelas.php">Kelola Wali Kelas</a></li>
        <li class="nav-item mb-1"><a href="kelola_kategori_pelanggaran.php">Kelola Kategori Pelanggaran</a></li>
        <li class="nav-item mb-1"><a href="kelola_jenis_pelanggaran.php">Kelola Jenis Pelanggaran</a></li>
        <li class="nav-item mb-1"><a href="laporan.php">Laporan</a></li>
        <li class="nav-item mb-1"><a href="about_me.php">About Me</a></li>
    </ul>
    <hr>
    <a href="logout.php" class="text-danger">Logout</a>
</div>

<!-- KONTEN UTAMA -->
<div class="main-content">
    <h2 class="mb-3">
        <?= $edit ? 'Edit Tahun Ajaran' : ($aksi === 'tambah' ? 'Tambah Tahun Ajaran' : 'Kelola Tahun Ajaran'); ?>
    </h2>
    <div class="mb-3">
        <hr>
    </div>

    <?php if ($aksi === 'tambah' || $edit): ?>
        <!-- FORM TAMBAH / EDIT DALAM CARD -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <?= $edit ? 'Edit Tahun Ajaran' : 'Tambah Tahun Ajaran'; ?>
            </div>
            <div class="card-body">
                <form method="POST" action="kelola_tahun_ajaran.php">
                    <input type="hidden" name="id" value="<?= htmlspecialchars((string)$form['id']); ?>">

                    <div class="mb-3">
                        <label class="form-label">Tahun Ajaran</label>
                        <input type="text" name="nama" class="form-control" placeholder="Contoh: 2026/2027" value="<?= htmlspecialchars($form['nama'] ?? ''); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" value="<?= htmlspecialchars($form['tanggal_mulai'] ?? ''); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" value="<?= htmlspecialchars($form['tanggal_selesai'] ?? ''); ?>" required>
                    </div>

                    <button type="submit" name="simpan" class="btn btn-primary">
                        <?= $edit ? 'Update Data' : 'Simpan'; ?>
                    </button>
                    
                    <a href="kelola_tahun_ajaran.php" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>

    <?php else: ?>

        <!-- TOMBOL TAMBAH DATA -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>Data Tahun Ajaran</h5>
            <a href="kelola_tahun_ajaran.php?aksi=tambah" class="btn btn-primary">
                + Tambah Tahun Ajaran
            </a>
        </div>

        <!-- TABEL DATA -->
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">Data Tahun Ajaran</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabelTahunAjaran" class="table table-bordered table-striped table-hover align-middle w-100">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Tahun Ajaran</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $no = 1; while ($r = mysqli_fetch_assoc($data)): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= htmlspecialchars($r['nama'] ?? ''); ?></td>
                                <td><?= htmlspecialchars($r['tanggal_mulai'] ?? ''); ?></td>
                                <td><?= htmlspecialchars($r['tanggal_selesai'] ?? ''); ?></td>
                                <td>
                                    <?php if ((int)$r['status_aktif'] === 1): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center text-nowrap">
                                    <a href="kelola_tahun_ajaran.php?edit=<?= (int)$r['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="kelola_tahun_ajaran.php?hapus=<?= (int)$r['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus tahun ajaran ini?')">Hapus</a>
                                </td>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function () {
    // Inisialisasi DataTables
    $('#tabelTahunAjaran').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        order: [],
        columnDefs: [{ targets: [0, 5], orderable: false, searchable: false }],
        language: {
            search: "Cari tahun ajaran:",
            lengthMenu: "Tampilkan 10 data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(difilter dari _MAX_ data)",
            zeroRecords: "Data tidak ditemukan",
            emptyTable: "Belum ada data tahun ajaran",
            paginate: { first: "Pertama", last: "Terakhir", next: "Berikutnya", previous: "Sebelumnya" }
        }
    });

    // Validasi tanggal selesai minimal sama dengan tanggal mulai
    $('#tanggal_mulai').on('change', function() {
        $('#tanggal_selesai').attr('min', $(this).val());
    });
});
</script>
</body>
</html>