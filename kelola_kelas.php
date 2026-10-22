
<?php
require_once 'koneksi.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// 1. PROSES SIMPAN (TAMBAH / EDIT)
if (isset($_POST['simpan'])) {
    $id = $_POST['id'];
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $tingkat = mysqli_real_escape_string($koneksi, $_POST['tingkat']);
    $jurusan = mysqli_real_escape_string($koneksi, $_POST['jurusan']);
    $status = $_POST['status_aktif'] == 'Aktif' ? 1 : 0;

    if ($id) {
        mysqli_query($koneksi, "UPDATE t_kelas SET nama='$nama', tingkat='$tingkat', jurusan='$jurusan', status_aktif='$status', updated_at=NOW() WHERE id='$id'");
    } else {
        mysqli_query($koneksi, "INSERT INTO t_kelas (nama, tingkat, jurusan, status_aktif, created_at, updated_at) VALUES ('$nama', '$tingkat', '$jurusan', '$status', NOW(), NOW())");
    }
    header("Location: kelola_kelas.php");
    exit();
}

// 2. PROSES HAPUS (KODE ASLI)
if (isset($_GET['hapus'])) {
    $id_kelas = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM t_pelanggaran_siswa WHERE kelas_id='$id_kelas'");
    mysqli_query($koneksi, "DELETE FROM t_kelas_siswa WHERE kelas_id='$id_kelas'");
    mysqli_query($koneksi, "DELETE FROM t_wali_kelas WHERE kelas_id='$id_kelas'");
    mysqli_query($koneksi, "DELETE FROM t_kelas WHERE id='$id_kelas'");
    header("Location: kelola_kelas.php");
    exit();
}

$aksi = $_GET['aksi'] ?? '';
$edit_id = $_GET['edit'] ?? '';
$edit = $edit_id ? mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM t_kelas WHERE id='$edit_id'")) : null;

$limit = 10;
$h = max(1, (int)($_GET['halaman'] ?? 1));
$offset = ($h - 1) * $limit;
$cari = mysqli_real_escape_string($koneksi, $_GET['cari'] ?? '');
$where = $cari ? "WHERE nama LIKE '%$cari%' OR tingkat LIKE '%$cari%' OR jurusan LIKE '%$cari%'" : "";

$total = mysqli_num_rows(mysqli_query($koneksi, "SELECT 1 FROM t_kelas $where"));
$pages = ceil($total / $limit);
$data = mysqli_query($koneksi, "SELECT * FROM t_kelas $where LIMIT $offset, $limit");
$no = $offset + 1;

// NILAI FORM
$form = $edit ?: [
    'id' => '',
    'nama' => '',
    'tingkat' => '',
    'jurusan' => '',
    'status_aktif' => 1
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kelas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
        }

        .sidebar {
            min-height: 100vh;
            background: #212529;
            color: #fff;
            position: fixed;
            width: 250px;
            top: 0;
            left: 0;
            overflow-y: auto;
        }

        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
            display: block;
            padding: 10px 20px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            color: #fff;
            background: #0d6efd;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
            min-width: 0;
        }

        .card-header {
            padding: 10px 15px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .main-content {
                margin-left: 200px;
                padding: 12px;
            }
        }
    </style>
</head>

<body>

    <!-- SIDEBAR ASLI, TIDAK DIUBAH -->
    <div class="sidebar d-flex flex-column p-3">
        <h4 class="text-white mb-4">Pelanggaran Siswa</h4>

        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item mb-1">
                <a href="dashboard.php">Dashboard</a>
            </li>
            <li class="nav-item mb-1">
                <a href="kelola_guru.php">Kelola Guru</a>
            </li>
            <li class="nav-item mb-1">
                <a href="kelola_siswa.php">Kelola Siswa</a>
            </li>
            <li class="nav-item mb-1">
                <a href="kelola_kelas.php" class="active">Kelola Kelas</a>
            </li>
            <li class="nav-item mb-1">
                <a href="kelola_tahun_ajaran.php">Kelola Tahun Ajaran</a>
            </li>
            <li class="nav-item mb-1">
                <a href="penempatan_siswa.php">Penempatan Siswa</a>
            </li>
            <li class="nav-item mb-1">
                <a href="kelola_wali_kelas.php">Kelola Wali Kelas</a>
            </li>
            <li class="nav-item mb-1">
                <a href="kelola_kategori_pelanggaran.php">Kelola Kategori Pelanggaran</a>
            </li>
            <li class="nav-item mb-1">
                <a href="laporan.php">laporan</a>
            </li>
            <li class="nav-item mb-1">
                <a href="kelola_jenis_pelanggaran.php">Kelola Jenis Pelanggaran</a>
            </li>
            <li class="nav-item mb-1">
                <a href="about_me.php">About Me</a>
            </li>
        </ul>

        <hr>
        <a href="logout.php" class="text-danger">Logout</a>
    </div>

    <!-- KONTEN UTAMA: TAMPILAN DISESUAIKAN -->
    <div class="main-content">

        <h2 class="mb-3">Kelola Kelas</h2>

        <a href="dashboard.php" class="btn btn-secondary mb-1">
            Kembali ke Dashboard
        </a>

        <hr>

        <!-- FORM TAMBAH / EDIT -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <?= $edit ? 'Edit Data Kelas' : 'Tambah Data Kelas'; ?>
            </div>

            <div class="card-body">
                <form method="POST" action="kelola_kelas.php">
                    <input type="hidden" name="id"
                        value="<?= htmlspecialchars($form['id'] ?? ''); ?>">

                    <div class="mb-3">
                        <label class="form-label">Nama Kelas</label>
                        <input type="text" name="nama" class="form-control"
                            placeholder="Contoh: XII RPL 1"
                            value="<?= htmlspecialchars($form['nama'] ?? ''); ?>"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tingkat</label>
                        <input type="text" name="tingkat" class="form-control"
                            placeholder="Contoh: XII"
                            value="<?= htmlspecialchars($form['tingkat'] ?? ''); ?>"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jurusan</label>
                        <input type="text" name="jurusan" class="form-control"
                            placeholder="Contoh: RPL, TKJ"
                            value="<?= htmlspecialchars($form['jurusan'] ?? ''); ?>"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status Aktif</label>
                        <select name="status_aktif" class="form-select" required>
                            <option value="Aktif"
                                <?= (($form['status_aktif'] ?? 1) == 1 ||
                                    ($form['status_aktif'] ?? '') == 'Aktif')
                                    ? 'selected' : ''; ?>>
                                Aktif
                            </option>

                            <option value="Tidak Aktif"
                                <?= (($form['status_aktif'] ?? 1) == 0 ||
                                    ($form['status_aktif'] ?? '') == 'Tidak Aktif')
                                    ? 'selected' : ''; ?>>
                                Tidak Aktif
                            </option>
                        </select>
                    </div>

                    <button type="submit" name="simpan" class="btn btn-primary">
                        Simpan
                    </button>

                    <?php if ($edit): ?>
                        <a href="kelola_kelas.php" class="btn btn-secondary">
                            Batal
                        </a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <!-- TABEL DATA KELAS -->
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                Data Kelas
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle"
                        id="tabelKelas">

                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama Kelas</th>
                                <th>Tingkat</th>
                                <th>Jurusan</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php while ($r = mysqli_fetch_assoc($data)): ?>
                                <tr>
                                    <td><?= $no++; ?></td>

                                    <td>
                                        <?= htmlspecialchars($r['nama']); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($r['tingkat']); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($r['jurusan']); ?>
                                    </td>

                                    <td>
                                        <span class="badge bg-<?= (
                                            $r['status_aktif'] == 1 ||
                                            $r['status_aktif'] == 'Aktif'
                                        ) ? 'success' : 'secondary'; ?>">
                                            <?= (
                                                $r['status_aktif'] == 1 ||
                                                $r['status_aktif'] == 'Aktif'
                                            ) ? 'Aktif' : 'Tidak Aktif'; ?>
                                        </span>
                                    </td>

                                    <td class="text-center text-nowrap">
                                        <a href="?edit=<?= $r['id']; ?>"
                                            class="btn btn-sm btn-warning">
                                            Edit
                                        </a>

                                        <a href="?hapus=<?= $r['id']; ?>"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Hapus data kelas ini?')">
                                            Hapus
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>

                            <?php if (!$total): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        Data kelas tidak ditemukan.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- PENCARIAN DAN PAGINATION ASLI -->
                <form method="GET" class="d-flex justify-content-end mb-3">
                    <input type="text" name="cari"
                        class="form-control form-control-sm"
                        style="max-width:250px"
                        placeholder="Cari data..."
                        value="<?= htmlspecialchars($_GET['cari'] ?? ''); ?>">

                    <button class="btn btn-primary btn-sm ms-2">
                        Cari
                    </button>
                </form>

                <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                    <small class="text-muted">
                        Menampilkan <?= $total ? $offset + 1 : 0; ?>
                        s.d. <?= min($offset + $limit, $total); ?>
                        dari <?= $total; ?> data
                    </small>

                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item <?= $h <= 1 ? 'disabled' : ''; ?>">
                            <a class="page-link"
                                href="?halaman=1&cari=<?= urlencode($_GET['cari'] ?? ''); ?>">
                                Pertama
                            </a>
                        </li>

                        <li class="page-item <?= $h <= 1 ? 'disabled' : ''; ?>">
                            <a class="page-link"
                                href="?halaman=<?= max(1, $h - 1); ?>&cari=<?= urlencode($_GET['cari'] ?? ''); ?>">
                                Sebelumnya
                            </a>
                        </li>

                        <?php for ($i = 1; $i <= $pages; $i++): ?>
                            <li class="page-item <?= $h == $i ? 'active' : ''; ?>">
                                <a class="page-link"
                                    href="?halaman=<?= $i; ?>&cari=<?= urlencode($_GET['cari'] ?? ''); ?>">
                                    <?= $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <li class="page-item <?= $h >= $pages ? 'disabled' : ''; ?>">
                            <a class="page-link"
                                href="?halaman=<?= min($pages, $h + 1); ?>&cari=<?= urlencode($_GET['cari'] ?? ''); ?>">
                                Berikutnya
                            </a>
                        </li>

                        <li class="page-item <?= $h >= $pages ? 'disabled' : ''; ?>">
                            <a class="page-link"
                                href="?halaman=<?= max(1, $pages); ?>&cari=<?= urlencode($_GET['cari'] ?? ''); ?>">
                                Terakhir
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
